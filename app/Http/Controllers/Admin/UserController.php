<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CenterClass;
use App\Models\ClassSession;
use App\Models\Role;
use App\Models\TeacherSalaryPayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Danh sách tài khoản.
     */
    public function index(Request $request): View
    {
        $users = User::query()
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->role))
            ->when($request->filled('q'), fn ($q) => $q->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('email', 'like', '%' . $request->q . '%');
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $roles = Role::ordered()->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Thống kê lớp của giáo viên: tổng giờ dạy theo 12 tháng (lọc theo năm), danh sách lớp.
     */
    public function statistics(Request $request, User $user): View
    {
        $year = (int) $request->get('year', Carbon::now()->year);
        $year = max(2000, min(2100, $year));

        $monthlyHours = array_fill(1, 12, 0.0);
        $monthlySalary = array_fill(1, 12, 0.0);
        $totalHours = 0;
        $totalSalary = 0;
        $hoursByClass = [];
        $paidMonths = array_fill(1, 12, false);
        $salaryPerHour = (float) ($user->salary_per_hour ?? 0);

        if ($user->role === User::ROLE_TEACHER) {
            $sessions = ClassSession::where('teacher_id', $user->id)
                ->whereYear('session_date', $year)
                ->whereHas('attendances')
                ->with('centerClass:id,hours_per_session')
                ->get();

            foreach ($sessions as $session) {
                $hours = (float) ($session->hours_per_session ?? $session->centerClass?->hours_per_session ?? 0);
                $month = (int) $session->session_date->format('n');
                $monthlyHours[$month] = ($monthlyHours[$month] ?? 0) + $hours;
                $totalHours += $hours;
                $hoursByClass[$session->center_class_id] = ($hoursByClass[$session->center_class_id] ?? 0) + $hours;
            }

            // Tính tổng lương theo tháng (giờ * lương/giờ)
            if ($salaryPerHour > 0) {
                for ($m = 1; $m <= 12; $m++) {
                    $monthlySalary[$m] = (float) ($monthlyHours[$m] ?? 0) * $salaryPerHour;
                    $totalSalary += $monthlySalary[$m];
                }
            }

            $payments = TeacherSalaryPayment::where('teacher_id', $user->id)
                ->where('year', $year)
                ->get(['month', 'is_paid']);
            foreach ($payments as $p) {
                $m = (int) $p->month;
                if ($m >= 1 && $m <= 12) {
                    $paidMonths[$m] = (bool) $p->is_paid;
                }
            }
        }

        $orderStatus = implode(',', [
            "'" . CenterClass::STATUS_IN_PROGRESS . "'",
            "'" . CenterClass::STATUS_NOT_STARTED . "'",
            "'" . CenterClass::STATUS_PAUSED . "'",
            "'" . CenterClass::STATUS_COMPLETED . "'",
        ]);
        $classes = $user->teachingClasses()
            ->with(['center:id,name', 'course:id,name'])
            ->orderByRaw("FIELD(status, {$orderStatus})")
            ->orderBy('name')
            ->get();

        return view('admin.users.statistics', compact('user', 'classes', 'year', 'monthlyHours', 'monthlySalary', 'totalHours', 'totalSalary', 'salaryPerHour', 'hoursByClass', 'paidMonths'));
    }

    public function updateSalaryPaid(Request $request, User $user): \Illuminate\Http\JsonResponse
    {
        if ($user->role !== User::ROLE_TEACHER) {
            abort(422, 'Chỉ áp dụng cho tài khoản giáo viên.');
        }

        $validated = $request->validate([
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'is_paid' => ['required', 'boolean'],
        ]);

        $isPaid = (bool) $validated['is_paid'];

        TeacherSalaryPayment::updateOrCreate(
            [
                'teacher_id' => $user->id,
                'year' => (int) $validated['year'],
                'month' => (int) $validated['month'],
            ],
            [
                'is_paid' => $isPaid,
                'paid_at' => $isPaid ? now() : null,
            ]
        );

        return response()->json(['ok' => true]);
    }

    /**
     * Form tạo tài khoản.
     */
    public function create(): View
    {
        $roles = Role::ordered()->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Lưu tài khoản mới.
     */
    public function store(Request $request): RedirectResponse
    {
        $roleNames = Role::pluck('name')->toArray();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:' . implode(',', $roleNames)],
            'salary_per_hour' => ['nullable', 'numeric', 'min:0', 'max:100000000'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['salary_per_hour'] = array_key_exists('salary_per_hour', $validated) && $validated['salary_per_hour'] !== null && $validated['salary_per_hour'] !== ''
            ? (float) $validated['salary_per_hour']
            : null;
        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Đã thêm tài khoản thành công.');
    }

    /**
     * Form sửa tài khoản.
     */
    public function edit(User $user): View
    {
        $roles = Role::ordered()->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Cập nhật tài khoản.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $roleNames = Role::pluck('name')->toArray();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:' . implode(',', $roleNames)],
            'salary_per_hour' => ['nullable', 'numeric', 'min:0', 'max:100000000'],
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if (array_key_exists('salary_per_hour', $validated)) {
            $validated['salary_per_hour'] = $validated['salary_per_hour'] !== null && $validated['salary_per_hour'] !== ''
                ? (float) $validated['salary_per_hour']
                : null;
        }
        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Đã cập nhật tài khoản thành công.');
    }

    /**
     * Xóa tài khoản.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Không thể xóa chính tài khoản của bạn.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Đã xóa tài khoản.');
    }
}

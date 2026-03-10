<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\CenterClass;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CenterClassController extends Controller
{
    use AuthorizesAdminTrait;

    public function index(Center $center): View
    {
        $user = auth()->user();
        if (! $user->isAdmin()) {
            // Giáo viên chỉ được xem trung tâm có ít nhất một lớp mình được gán
            $hasAccess = $center->classes()->whereHas('teachers', fn ($q) => $q->where('users.id', $user->id))->exists();
            if (! $hasAccess) {
                abort(403, 'Bạn không có quyền xem lớp học của trung tâm này.');
            }
            // Chỉ hiển thị các lớp mà giáo viên được gán
            $classes = $center->classes()
                ->whereHas('teachers', fn ($q) => $q->where('users.id', $user->id))
                ->withCount('students')
                ->withCount(['students as students_paid_count' => fn ($q) => $q->where('tuition_paid', true)])
                ->with(['course', 'teachers'])
                ->ordered()
                ->paginate(15);
        } else {
            $classes = $center->classes()
                ->withCount('students')
                ->withCount(['students as students_paid_count' => fn ($q) => $q->where('tuition_paid', true)])
                ->with(['course', 'teachers'])
                ->ordered()
                ->paginate(15);
        }

        return view('admin.center-classes.index', compact('center', 'classes'));
    }

    public function create(Center $center): View
    {
        $this->authorizeAdmin();
        $courses = Course::ordered()->get();
        $teachers = User::where('role', User::ROLE_TEACHER)->orderBy('name')->get();
        return view('admin.center-classes.create', compact('center', 'courses', 'teachers'));
    }

    public function store(Request $request, Center $center): RedirectResponse
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'course_id' => ['nullable', 'integer', 'exists:courses,id'],
            'teacher_id' => ['nullable', 'integer', 'exists:users,id'],
            'description' => ['nullable', 'string'],
            'schedule' => ['nullable', 'string', 'max:255'],
            'hours_per_session' => ['required', 'numeric', 'min:0.25', 'max:24'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'status' => ['nullable', 'string', 'in:not_started,in_progress,paused,completed'],
            'tuition_collection_status' => ['nullable', 'string', 'in:not_collected,collecting,completed'],
        ], [
            'name.required' => 'Tên lớp học không được để trống.',
        ]);
        $validated['center_id'] = $center->id;
        $validated['course_id'] = $validated['course_id'] ?? null;
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['hours_per_session'] = (float) $validated['hours_per_session'];
        $validated['status'] = $validated['status'] ?? CenterClass::STATUS_NOT_STARTED;
        $validated['tuition_collection_status'] = $validated['tuition_collection_status'] ?? CenterClass::TUITION_NOT_COLLECTED;

        $center_class = $center->classes()->create($validated);
        $this->syncTeachers($center_class, $request->filled('teacher_id') ? [(int) $request->input('teacher_id')] : []);

        return redirect()->route('admin.centers.classes.index', $center)
            ->with('success', 'Đã thêm lớp học.');
    }

    public function edit(Center $center, CenterClass $class): View
    {
        $this->authorizeAdmin();
        $center_class = $class;
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }
        $courses = Course::ordered()->get();
        $teachers = User::where('role', User::ROLE_TEACHER)->orderBy('name')->get();
        return view('admin.center-classes.edit', compact('center', 'center_class', 'courses', 'teachers'));
    }

    public function update(Request $request, Center $center, CenterClass $class): RedirectResponse
    {
        $this->authorizeAdmin();
        $center_class = $class;
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'course_id' => ['nullable', 'integer', 'exists:courses,id'],
            'teacher_id' => ['nullable', 'integer', 'exists:users,id'],
            'description' => ['nullable', 'string'],
            'schedule' => ['nullable', 'string', 'max:255'],
            'hours_per_session' => ['required', 'numeric', 'min:0.25', 'max:24'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'status' => ['nullable', 'string', 'in:not_started,in_progress,paused,completed'],
            'tuition_collection_status' => ['nullable', 'string', 'in:not_collected,collecting,completed'],
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['course_id'] = $validated['course_id'] ?? null;
        $validated['hours_per_session'] = (float) $validated['hours_per_session'];
        $validated['status'] = $validated['status'] ?? CenterClass::STATUS_NOT_STARTED;
        $validated['tuition_collection_status'] = $validated['tuition_collection_status'] ?? CenterClass::TUITION_NOT_COLLECTED;

        $center_class->update($validated);
        $this->syncTeachers($center_class, $request->filled('teacher_id') ? [(int) $request->input('teacher_id')] : []);

        return redirect()->route('admin.centers.classes.index', $center)
            ->with('success', 'Đã cập nhật lớp học.');
    }

    private function syncTeachers(CenterClass $center_class, array $teacherIds): void
    {
        $teacherIds = array_filter(array_map('intval', is_array($teacherIds) ? $teacherIds : []));
        $valid = User::where('role', User::ROLE_TEACHER)->whereIn('id', $teacherIds)->pluck('id')->toArray();
        $center_class->teachers()->sync($valid);
    }

    public function destroy(Center $center, CenterClass $class): RedirectResponse
    {
        $this->authorizeAdmin();
        $center_class = $class;
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }
        $center_class->delete();
        return redirect()->route('admin.centers.classes.index', $center)
            ->with('success', 'Đã xóa lớp học.');
    }
}

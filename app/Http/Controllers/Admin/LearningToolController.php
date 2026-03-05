<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\LearningTool;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LearningToolController extends Controller
{
    use AuthorizesAdminTrait;

    public function index(Request $request): View
    {
        $query = LearningTool::query()->with(['center', 'centerClass', 'managedBy']);

        if ($request->filled('center_id')) {
            $query->where('center_id', $request->center_id);
        }
        if ($request->filled('center_class_id')) {
            $query->where('center_class_id', $request->center_class_id);
        }
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        $tools = $query->latest()->paginate(15)->withQueryString();
        $centers = Center::ordered()->get();

        return view('admin.learning-tools.index', compact('tools', 'centers'));
    }

    public function create(): View
    {
        $this->authorizeAdmin();
        $centers = Center::with('classes')->ordered()->get();
        $managers = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_TEACHER])->orderBy('name')->get();

        return view('admin.learning-tools.create', compact('centers', 'managers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:0'],
            'note' => ['nullable', 'string', 'max:1000'],
            'center_id' => ['required', 'exists:centers,id'],
            'center_class_id' => ['nullable', 'exists:center_classes,id'],
            'managed_by_user_id' => ['nullable', 'exists:users,id'],
        ], [
            'name.required' => 'Tên công cụ không được để trống.',
        ]);

        LearningTool::create($validated);

        return redirect()->route('admin.learning-tools.index')
            ->with('success', 'Đã thêm công cụ học.');
    }

    public function edit(LearningTool $learning_tool): View
    {
        $this->authorizeAdmin();
        $tool = $learning_tool;
        $centers = Center::with('classes')->ordered()->get();
        $managers = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_TEACHER])->orderBy('name')->get();

        return view('admin.learning-tools.edit', compact('tool', 'centers', 'managers'));
    }

    public function update(Request $request, LearningTool $learning_tool): RedirectResponse
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:0'],
            'note' => ['nullable', 'string', 'max:1000'],
            'center_id' => ['required', 'exists:centers,id'],
            'center_class_id' => ['nullable', 'exists:center_classes,id'],
            'managed_by_user_id' => ['nullable', 'exists:users,id'],
        ], [
            'name.required' => 'Tên công cụ không được để trống.',
        ]);

        $learning_tool->update($validated);

        return redirect()->route('admin.learning-tools.index')
            ->with('success', 'Đã cập nhật công cụ học.');
    }

    public function destroy(LearningTool $learning_tool): RedirectResponse
    {
        $this->authorizeAdmin();
        $learning_tool->delete();

        return redirect()->route('admin.learning-tools.index')
            ->with('success', 'Đã xóa công cụ học.');
    }
}

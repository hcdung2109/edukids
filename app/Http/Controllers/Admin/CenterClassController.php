<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\CenterClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CenterClassController extends Controller
{
    public function index(Center $center): View
    {
        $classes = $center->classes()->withCount('students')->ordered()->paginate(15);
        return view('admin.center-classes.index', compact('center', 'classes'));
    }

    public function create(Center $center): View
    {
        return view('admin.center-classes.create', compact('center'));
    }

    public function store(Request $request, Center $center): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'schedule' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ], [
            'name.required' => 'Tên lớp học không được để trống.',
        ]);
        $validated['center_id'] = $center->id;
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $center->classes()->create($validated);

        return redirect()->route('admin.centers.classes.index', $center)
            ->with('success', 'Đã thêm lớp học.');
    }

    public function edit(Center $center, CenterClass $class): View
    {
        $center_class = $class;
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }
        return view('admin.center-classes.edit', compact('center', 'center_class'));
    }

    public function update(Request $request, Center $center, CenterClass $class): RedirectResponse
    {
        $center_class = $class;
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'schedule' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $center_class->update($validated);

        return redirect()->route('admin.centers.classes.index', $center)
            ->with('success', 'Đã cập nhật lớp học.');
    }

    public function destroy(Center $center, CenterClass $class): RedirectResponse
    {
        $center_class = $class;
        if ($center_class->center_id !== $center->id) {
            abort(404);
        }
        $center_class->delete();
        return redirect()->route('admin.centers.classes.index', $center)
            ->with('success', 'Đã xóa lớp học.');
    }
}

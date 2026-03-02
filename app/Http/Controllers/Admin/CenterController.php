<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Center;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CenterController extends Controller
{
    public function index(Request $request): View
    {
        $centers = Center::query()
            ->withCount('classes')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->q . '%')
                ->orWhere('address', 'like', '%' . $request->q . '%'))
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.centers.index', compact('centers'));
    }

    public function create(): View
    {
        return view('admin.centers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:500'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ], [
            'name.required' => 'Tên trung tâm không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'website.url' => 'Website phải là URL hợp lệ.',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        unset($validated['image']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('centers', 'public');
        }

        Center::create($validated);

        return redirect()->route('admin.centers.index')->with('success', 'Đã thêm trung tâm.');
    }

    public function edit(Center $center): View
    {
        return view('admin.centers.edit', compact('center'));
    }

    public function update(Request $request, Center $center): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:500'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        unset($validated['image']);

        if ($request->hasFile('image')) {
            if ($center->image) {
                Storage::disk('public')->delete($center->image);
            }
            $validated['image'] = $request->file('image')->store('centers', 'public');
        }

        $center->update($validated);

        return redirect()->route('admin.centers.index')->with('success', 'Đã cập nhật trung tâm.');
    }

    public function destroy(Center $center): RedirectResponse
    {
        if ($center->image) {
            Storage::disk('public')->delete($center->image);
        }
        $center->delete();
        return redirect()->route('admin.centers.index')->with('success', 'Đã xóa trung tâm.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        $roles = Role::ordered()->get()->map(function (Role $role) {
            $role->users_count = User::where('role', $role->name)->count();
            return $role;
        });

        return view('admin.roles.index', compact('roles'));
    }

    public function create(): View
    {
        return view('admin.roles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:roles,name', 'regex:/^[a-z0-9_]+$/'],
            'label' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'name.regex' => 'Mã vai trò chỉ được chứa chữ thường, số và dấu gạch dưới.',
        ]);

        $validated['is_system'] = false;
        $validated['sort_order'] = Role::max('sort_order') + 10;
        Role::create($validated);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Đã thêm vai trò.');
    }

    public function edit(Role $role): View
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $rules = [
            'label' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
        ];
        if (! $role->is_system) {
            $rules['name'] = ['required', 'string', 'max:50', 'unique:roles,name,' . $role->id, 'regex:/^[a-z0-9_]+$/'];
        }

        $validated = $request->validate($rules, [
            'name.regex' => 'Mã vai trò chỉ được chứa chữ thường, số và dấu gạch dưới.',
        ]);

        if ($role->is_system) {
            unset($validated['name']);
        }
        $role->update($validated);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Đã cập nhật vai trò.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->is_system) {
            return back()->with('error', 'Không thể xóa vai trò hệ thống.');
        }

        $count = User::where('role', $role->name)->count();
        if ($count > 0) {
            return back()->with('error', "Không thể xóa vai trò. Đang có {$count} tài khoản sử dụng vai trò này.");
        }

        \Illuminate\Support\Facades\DB::table('role_permissions')->where('role', $role->name)->delete();
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Đã xóa vai trò.');
    }
}

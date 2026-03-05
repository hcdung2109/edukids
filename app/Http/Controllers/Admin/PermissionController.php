<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PermissionController extends Controller
{
    /** Danh sách vai trò có thể chỉnh quyền (tất cả trừ admin). */
    public static function editableRoles(): array
    {
        return Role::where('name', '!=', User::ROLE_ADMIN)->ordered()->get()->pluck('label', 'name')->toArray();
    }

    /** Tất cả vai trò hiển thị trong dropdown. */
    public static function allRoles(): array
    {
        return Role::ordered()->get()->pluck('label', 'name')->toArray();
    }

    public function index(Request $request): View
    {
        $permissions = Permission::ordered()->get()->groupBy('group');
        $allRoles = static::allRoles();
        $selectedRole = $request->input('role', User::ROLE_TEACHER);
        if (! array_key_exists($selectedRole, $allRoles)) {
            $selectedRole = ! empty($allRoles) ? array_key_first($allRoles) : User::ROLE_TEACHER;
        }

        $permissionIdsForRole = $selectedRole === User::ROLE_ADMIN
            ? Permission::pluck('id')->toArray()
            : RolePermission::permissionIdsForRole($selectedRole);

        $isEditable = array_key_exists($selectedRole, static::editableRoles());

        return view('admin.permissions.index', [
            'permissions' => $permissions,
            'allRoles' => $allRoles,
            'selectedRole' => $selectedRole,
            'permissionIdsForRole' => $permissionIdsForRole,
            'isEditable' => $isEditable,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $editable = static::editableRoles();
        $request->validate([
            'role' => ['required', 'string', 'in:' . implode(',', array_keys($editable))],
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ]);

        $role = $request->input('role');
        $permissionIds = $request->input('permission_ids', []);
        $permissionIds = array_map('intval', array_filter($permissionIds));

        DB::table('role_permissions')->where('role', $role)->delete();
        foreach ($permissionIds as $pid) {
            DB::table('role_permissions')->insert(['role' => $role, 'permission_id' => $pid]);
        }

        $roleLabel = $editable[$role] ?? Role::where('name', $role)->value('label') ?? $role;

        return redirect()->route('admin.permissions.index', ['role' => $role])
            ->with('success', "Đã cập nhật quyền cho vai trò {$roleLabel}.");
    }
}

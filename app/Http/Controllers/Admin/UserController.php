<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
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
        ]);

        $validated['password'] = Hash::make($validated['password']);
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
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
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

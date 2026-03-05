<?php

namespace App\Http\Controllers\Admin;

trait AuthorizesAdminTrait
{
    protected function authorizeAdmin(): void
    {
        if (! auth()->user()?->isAdmin()) {
            abort(403, 'Chỉ quản trị viên mới có quyền thực hiện thao tác này.');
        }
    }
}

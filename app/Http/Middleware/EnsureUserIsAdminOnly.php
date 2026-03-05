<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdminOnly
{
    /**
     * Chỉ cho phép tài khoản có role admin. Giáo viên (teacher) bị từ chối.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Chỉ quản trị viên mới có quyền thực hiện thao tác này.');
        }

        return $next($request);
    }
}

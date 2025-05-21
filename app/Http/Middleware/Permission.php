<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class Permission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = Auth::user();

        if (!in_array($permission, $user->roles->permissions ?? [])) {
            Log::warning('user tidak ditemukan'. $permission);
            return redirect()->to('admin/dashboard');
        }

        return $next($request);
    }

}

<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectCashierHome
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user
            && $user->hasRole('cashier')
            && !$user->hasAnyRole(['owner', 'manager'])
            && in_array($request->path(), ['admin', 'admin/dashboard'])
        ) {
            return redirect('/admin/cashier');
        }

        return $next($request);
    }
}
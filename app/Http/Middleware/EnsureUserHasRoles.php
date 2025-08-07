<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class EnsureUserHasRoles
{
    public function handle(Request $request, Closure $next, string $roles = '')
    {
        $user = $request->user();
        if (!$user) {
            throw new AccessDeniedHttpException('Unauthorized.');
        }

        $requiredRoles = array_filter(array_map('trim', preg_split('/[|,]/', $roles)));
        if (empty($requiredRoles)) {
            return $next($request);
        }

        if (method_exists($user, 'hasAnyRole')) {
            if ($user->hasAnyRole($requiredRoles)) {
                return $next($request);
            }
        }

        throw new AccessDeniedHttpException('Insufficient role permissions.');
    }
}



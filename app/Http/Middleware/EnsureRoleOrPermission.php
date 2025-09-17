<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRoleOrPermission
{
    public function handle(Request $request, Closure $next, ...$args): Response
    {
        $user = $request->user();
        if (! $user) {
            abort(401);
        }

        // usage example: role_or_permission:manager|admin,edit articles|publish posts
        $list = collect(explode('|', implode('|', $args)))->filter()->values();

        $ok = $user->hasAnyRole($list->all()) || $user->hasAnyPermission($list->all());
        if (! $ok) {
            abort(403);
        }

        return $next($request);
    }
}

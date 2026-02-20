<?php

namespace App\Http\Middleware;

use App\Support\CalendarPermissionResolver;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EnsureCalendarAdmin
{
    public function __construct(private readonly CalendarPermissionResolver $permissionResolver)
    {
    }

    public function handle(Request $request, Closure $next): mixed
    {
        if (! $this->permissionResolver->canManage($request->user())) {
            return new RedirectResponse(route('calendar.index'));
        }

        return $next($request);
    }
}

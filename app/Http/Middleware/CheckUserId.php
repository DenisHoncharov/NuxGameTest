<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $userIdFromUrl = $request->route('user')->id;

        $userIdFromSession = session('user_id');

        if ($userIdFromUrl !== $userIdFromSession) {
            abort(403, 'Access denied');
        }

        return $next($request);
    }
}

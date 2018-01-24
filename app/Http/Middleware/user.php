<?php

namespace App\Http\Middleware;

use Confide;
use Closure;
use Redirect;

class user
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $a = Confide::user();
        if ($a === null) {
            return Redirect::to(route('user.login'));
        }
        return $next($request);
    }
}

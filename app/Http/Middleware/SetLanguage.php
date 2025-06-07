<?php

namespace App\Http\Middleware;

use Closure;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $lang = $request->header('X-LANGUAGE', config('app.locale'));

        \App::setLocale($lang ?? \App::getLocale());
        return $next($request);
    }
}

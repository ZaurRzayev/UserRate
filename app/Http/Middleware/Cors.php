<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    public function handle($request, Closure $next)
    {
        // Perform middleware logic before passing the request further
        return $next($request);
    }
}

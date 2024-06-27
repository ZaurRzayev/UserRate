<?php

// File: vendor/laravel/sanctum/src/Http/Middleware/EnsureFrontendRequestsAreStateful.php

namespace Laravel\Sanctum\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class EnsureFrontendRequestsAreStateful
{
    public function handle($request, Closure $next)
    {
        if ($this->isReading($request) ||
            $this->runningUnitTests() ||
            $this->inExceptArray($request) ||
            $this->shouldPassThrough($request)) {
            return $next($request);
        }

        if ($this->shouldBeStateless($request)) {
            config(['session.driver' => 'array']);
        }

        return $next($request);
    }

    // Other methods...
}

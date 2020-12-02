<?php

namespace Ironopolis\Skeleton\Middleware;

use Closure;
use Request;

class SkeletonMiddleware
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}

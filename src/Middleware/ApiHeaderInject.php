<?php

namespace lararest\RestApiGenerator\Middleware;

use Closure;

class ApiHeaderInject
{
    public function handle($request, Closure $next)
    {
        if (config('restapi-generator.json_response')) {
            $request->headers->add([
                'Accept'=>'application/json',
            ]);
        }
        if (config('restapi-generator.allow_cross_origin')) {
            $request->headers->add([
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            ]);
        }

        return $next($request);
    }
}

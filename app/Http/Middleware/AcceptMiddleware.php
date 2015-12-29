<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

use App\Traits\ResponseJsonTrait;

class AcceptMiddleware
{
    use ResponseJsonTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (env('APP_DEBUG') === false) {

            if (strpos(mb_strtolower($request->header('Accept')), 'application/json') !== 0) {
                return $this->setStatusCode(400)->failResponse('Header: Accept must be specified as application/json');
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Request;
use App;

class LocalMiddleware
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
      if (Request::ip() == "192.168.0.17"){
        return $next($request);
      }
      return App::abort(403, "Forbidden");
    }
}

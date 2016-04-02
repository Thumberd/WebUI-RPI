<?php

namespace App\Http\Middleware;

use Closure;
use App;

class APIMiddleware
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
        $user = App\User::where('token_id', $request->header('Token_Id'))->first();
        if($user) {
          if($user->token_key == $request->header('token-key')){
            return $next($request);
          }
        }
        return App::abort(403, "Forbidden");
    }
}

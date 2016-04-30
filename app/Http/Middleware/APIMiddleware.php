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
      if (!empty($request->header('Token_Id'))) {
        $user = App\User::where('token_id', $request->header('Token_Id'))->first();
        if($user) {
          if($user->token_key == $request->header('token-key')){
            return $next($request);
          }
        }
	else {
	        return App::abort(403, "Forbidden");
	}
      }
      else if (!empty($request->header('Device-Id'))){
	$device = App\Device::where('token_id', $request->header('Device-Id'))->first();
	if ($device AND $device->token_key == $request->header('Device-Key')) {
		return $next($request);
	}
      }
      else {
	return App::abort(403, "Forbidden");
      }
    }
}

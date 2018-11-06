<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Route;


class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
  
    public function handle($request, Closure $next, $guard = null)
    {
    	
      $currentPath = Route::getFacadeRoot()->current()->uri();
	  	
      switch ($guard) {
        case 'site':
          if (!Auth::guard($guard)->check()) {
            return redirect('/signup');
          }
          break;
          
        default:
          if (Auth::guard($guard)->check()) {
              return redirect('/admin/dashboard');
          }
          break;
      }
      return $next($request);
    }
}

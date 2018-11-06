<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Route;


class SiteAuthenticated
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
		    if (Auth::guard($guard)->check() && $currentPath!=='dashboard') {
            return redirect('/signup');
        }

        return $next($request);
    }
    
    // public function handle($request, Closure $next, $guard = null)
    // {
//     	
      // $currentPath = Route::getFacadeRoot()->current()->uri();
// 	  	
      // switch ($guard) {
        // case 'site':
          // if (Auth::guard($guard)->check() && $currentPath!=='site/dashboard') {
            // return redirect('/site/dashboard');
          // }
          // break;
//           
        // default:
          // if (Auth::guard($guard)->check()) {
              // return redirect('/admin/dashboard');
          // }
          // break;
      // }
      // return $next($request);
    // }
}

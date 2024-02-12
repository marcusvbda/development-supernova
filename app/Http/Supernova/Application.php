<?php

namespace App\Http\Supernova;

use marcusvbda\supernova\Application as SupernovaApplication;
use Auth;

class Application extends SupernovaApplication
{
    // public function middleware($request, $next)
    // {
    //     if (Auth::check()) return $next($request);
    //     return redirect()->route('login', ["redirect" => request()->path()]);
    // }
}

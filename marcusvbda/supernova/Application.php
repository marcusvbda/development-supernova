<?php

namespace marcusvbda\supernova;

class Application
{
    public function middleware($request, $next)
    {
        return $next($request);
    }
}

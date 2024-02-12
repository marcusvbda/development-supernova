<?php

namespace marcusvbda\supernova;

class Application
{
    public function middleware($request, $next)
    {
        return $next($request);
    }

    public function title():string
    {
        return config("app.name");
    }

    public function styles():string
    {
        return <<<CSS
            /* styles here ... */
        CSS;
    }

    public function icon():string
    {
        return "favicon.ico";
    }
}

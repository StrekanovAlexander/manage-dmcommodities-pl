<?php

namespace App\Middleware;

class GlobalMiddleware extends Middleware {
    
    public function __invoke($req, $res, $next) {

        if (!$this->container->auth->user()->is_root) {
            return $res->withRedirect($this->container->router->pathFor('home.index'));
        }
        
        $res = $next($req, $res);
    
        return $res;
    }
}
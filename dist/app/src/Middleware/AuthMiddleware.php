<?php

namespace App\Middleware;

class AuthMiddleware extends Middleware {
    
    public function __invoke($req, $res, $next) {

        if (!$this->container->auth->check()) {
            return $res->withRedirect($this->container->router->pathFor('user.login'));
        }
        
        $res = $next($req, $res);
    
        return $res;
    }
}
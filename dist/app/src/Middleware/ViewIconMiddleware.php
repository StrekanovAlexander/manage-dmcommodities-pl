<?php

namespace App\Middleware;

class ViewIconMiddleware extends Middleware {
    
    public function __invoke($req, $res, $next) {

        $this->container->view->getEnvironment()->addGlobal('icon', [
            'check' => '<i class="fas fa-check text-success"></i>',
            'cog' => '<i class="fa fa-cog"></i>',
            'edit' => '<i class="fas fa-edit"></i>',
            'search' => '<i class="fas fa-search"></i>',
            'times' => '<i class="fas fa-times"></i>',
            'trash' => '<i class="fa fa-trash text-danger"></i>',
        ]);

        $res = $next($req, $res);
        return $res;
    }

}

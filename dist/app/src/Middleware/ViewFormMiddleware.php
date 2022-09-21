<?php

namespace App\Middleware;

class ViewFormMiddleware extends Middleware {
    
    public function __invoke($req, $res, $next) {

        $this->container->view->getEnvironment()->addGlobal('form', [
            'checkbox' => ['checked' => ' checked="checked"'],
            'radio' => ['checked' => ' checked="checked"'],
            'csrf' => '
                <input type="hidden" name="' . $this->container->csrf->getTokenNameKey() . '" value="' . $this->container->csrf->getTokenName() . '">
                <input type="hidden" name="' . $this->container->csrf->getTokenValueKey() . '" value="' . $this->container->csrf->getTokenValue() . '">',
        ]);

        $res = $next($req, $res);

        return $res;

    }

}
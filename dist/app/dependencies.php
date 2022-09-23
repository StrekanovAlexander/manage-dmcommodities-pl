<?php

$container = $app->getContainer();

$container['view'] = function($c) {
    $view = new \Slim\Views\Twig(__DIR__ . '/templates', [
        'cache' => false
    ]);
  
    $view->addExtension(new \Slim\Views\TwigExtension(
        $c->router,
        $c->request->getUri()
    ));

    $view->getEnvironment()->addGlobal('auth', [
        'check' => $c->auth->check(),
        'user' => $c->auth->user()
    ]);  
    
    $view->getEnvironment()->addGlobal('flash', $c->flash);

    return $view;

};

function db($c) {
    $db = new \Illuminate\Database\Capsule\Manager;
    $db->addConnection($c['settings']['db']);
    $db->setAsGlobal();
    $db->bootEloquent();
    
    return $db;
}

$container['db'] = db($container);

$container['auth'] = function() {
    return new App\Auth\Auth;
};

$container['csrf'] = function() {
    return new \Slim\Csrf\Guard;
};

$container['flash'] = function() {
    return new \Slim\Flash\Messages;
};

$container['BasePriceController'] = function($c) {
    return new App\Controllers\BasePriceController($c);
};

$container['HomeController'] = function($c) {
    return new App\Controllers\HomeController($c);
};

$container['CurrencyController'] = function($c) {
    return new App\Controllers\CurrencyController($c);
}; 

$container['LanguageController'] = function($c) {
    return new App\Controllers\LanguageController($c);
}; 

$container['PlaceController'] = function($c) {
    return new App\Controllers\PlaceController($c);
};

$container['PlaceTranslateController'] = function($c) {
    return new App\Controllers\PlaceTranslateController($c);
};

$container['ProductController'] = function($c) {
    return new App\Controllers\ProductController($c);
};

$container['ProductTranslateController'] = function($c) {
    return new App\Controllers\ProductTranslateController($c);
};

$container['UserController'] = function($c) {
    return new App\Controllers\UserController($c);
}; 

$container['notFoundHandler'] = function ($c) {
    return function ($req, $res) use ($c) {
        return $c['view']->render($res->withStatus(404), 'common/404.twig');
    };
};

$container['errorHandler'] = function () {
    return function ($req, $res) {
        return $res->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write('Wrong request');
    };
};

$app->add(new App\Middleware\ViewFormMiddleware($container));
$app->add(new App\Middleware\ViewIconMiddleware($container));
$app->add($container->csrf);
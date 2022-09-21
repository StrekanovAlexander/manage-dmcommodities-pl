<?php

$app->group('', function() {
    $this->get('/', 'HomeController:index')->setName('home.index');
})->add(new \App\Middleware\AuthMiddleware($container));

$app->group('', function() {
    $this->get('/currencies', 'CurrencyController:index')->setName('currency.index');
    $this->get('/currency/create', 'CurrencyController:create')->setName('currency.create');
    $this->post('/currency/create', 'CurrencyController:store');
    $this->get('/currency/details/{id}', 'CurrencyController:details')->setName('currency.details');
    $this->get('/currency/update[/{id}]', 'CurrencyController:edit')->setName('currency.update');
    $this->post('/currency/update', 'CurrencyController:update');
    $this->post('/currency/actuality', 'CurrencyController:actuality')->setName('currency.actuality');
})->add(new \App\Middleware\AuthMiddleware($container));

$app->group('', function() {
    $this->get('/languages', 'LanguageController:index')->setName('language.index');
    $this->get('/language/create', 'LanguageController:create')->setName('language.create');
    $this->post('/language/create', 'LanguageController:store');
    $this->get('/language/details/{id}', 'LanguageController:details')->setName('language.details');
    $this->get('/language/update[/{id}]', 'LanguageController:edit')->setName('language.update');
    $this->post('/language/update', 'LanguageController:update');
    $this->post('/language/actuality', 'LanguageController:actuality')->setName('language.actuality');
})->add(new \App\Middleware\AuthMiddleware($container));

$app->group('', function() {
    $this->get('/places', 'PlaceController:index')->setName('place.index');
    $this->get('/place/create', 'PlaceController:create')->setName('place.create');
    $this->post('/place/create', 'PlaceController:store');
    $this->get('/place/details/{id}', 'PlaceController:details')->setName('place.details');
    $this->get('/place/update[/{id}]', 'PlaceController:edit')->setName('place.update');
    $this->post('/place/update', 'PlaceController:update');
    $this->post('/place/actuality', 'PlaceController:actuality')->setName('place.actuality');
})->add(new \App\Middleware\AuthMiddleware($container));

$app->group('', function() {
    $this->get('/place/translate/create/{id}', 'PlaceTranslateController:create')->setName('place.translate.create');
    $this->post('/place/translate/create', 'PlaceTranslateController:store');
})->add(new \App\Middleware\AuthMiddleware($container));

$app->group('', function() {
    $this->get('/users', 'UserController:index')->setName('user.index');
    $this->get('/user/create', 'UserController:create')->setName('user.create');
    $this->post('/user/create', 'UserController:store');
    $this->get('/user/details/{id}', 'UserController:details')->setName('user.details');
    $this->get('/user/update[/{id}]', 'UserController:edit')->setName('user.update');
    $this->post('/user/update', 'UserController:update');
    $this->get('/user/update-password[/{id}]', 'UserController:editPassword')->setName('user.update.password');
    $this->post('/user/update-password', 'UserController:updatePassword');
    $this->post('/user/actuality', 'UserController:actuality')->setName('user.actuality');
})->add(new \App\Middleware\AuthMiddleware($container))
->add(new \App\Middleware\GlobalMiddleware($container));

$app->group('', function() {
    $this->get('/logout', 'UserController:logout')->setName('user.logout');
})->add(new \App\Middleware\AuthMiddleware($container));

$app->group('', function() {
    $this->get('/login', 'UserController:getLogin')->setName('user.login');
    $this->post('/login', 'UserController:postLogin');
})->add(new \App\Middleware\GuestMiddleware($container));
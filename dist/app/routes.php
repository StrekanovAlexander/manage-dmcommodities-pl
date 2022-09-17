<?php

$app->group('', function() {
    $this->get('/', 'HomeController:index')->setName('home.index');
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
    $this->get('/logout', 'UserController:logout')->setName('user.logout');
})->add(new \App\Middleware\AuthMiddleware($container));

$app->group('', function() {
    $this->get('/login', 'UserController:getLogin')->setName('user.login');
    $this->post('/login', 'UserController:postLogin');
})->add(new \App\Middleware\GuestMiddleware($container));
<?php

require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/UserController .php';


Router::get('/', 'Views/index.php');
Router::get('/about', 'Views/about.php');
Router::get('/contact', 'Views/contact.php');

Router::get('auth/check', fn() => AuthController::check());
Router::post('auth/login', fn() => AuthController::login());
Router::post('auth/logout', fn() => AuthController::logout());

Router::get('/api/users/get/$id', fn($id) => UserController::get($id));
Router::post('/api/users/create', fn() => UserController::create());
Router::post('/api/users/update', fn() => UserController::update());


Router::any('/404','Views/404.php');

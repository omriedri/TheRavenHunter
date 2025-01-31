<?php

require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/UserController .php';
require_once __DIR__ . '/../app/Controllers/ScoresController.php';

Router::get('/', 'Views/index.php');
Router::get('/about', 'Views/about.php');
Router::get('/contact', 'Views/contact.php');

Router::get('/auth/check', fn() => AuthController::check());
Router::post('/auth/login', fn() => AuthController::login());
Router::post('/auth/logout', fn() => AuthController::logout());

Router::get('/api/users/get/$id', fn($id) => UserController::get($id));
Router::post('/api/users/create', fn() => UserController::create());
Router::post('/api/users/update', fn() => UserController::update());
Router::get('/api/users/profile', fn() => UserController::profile());
Router::get('/api/users/profile/$id', fn($id) => UserController::profile($id));
Router::get('/api/users/settings', fn() => UserController::getSettings());
Router::post('/api/users/settings-update',fn() => UserController::updateSettings());

Router::get('/api/scores/$option', fn($option) => ScoresController::get($option));
Router::post('/api/game/start',  fn() => ScoresController::startGameSession());
Router::post('/api/game/end',    fn() => ScoresController::endGameSession());
Router::post('/api/game/forget', fn() => ScoresController::forgetGameSession());

http_response_code(404);
Router::any('/404','Views/404.php');

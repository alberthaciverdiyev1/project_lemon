<?php
/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\AuthController;

$router->post('/register-user', [AuthController::class, 'register']);
$router->post('/login-user', [AuthController::class, 'login']);
$router->post('/refresh-user', [AuthController::class, 'refreshToken']);

$router->group(['middleware' => 'jwt.auth'], function () use ($router) {
    $router->get('/user-me', [AuthController::class, 'me']);
});

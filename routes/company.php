<?php

use App\Http\Controllers\CompanyController;

/** @var \Laravel\Lumen\Routing\Router $router */


$router->group(['prefix' => 'company'], function () use ($router) {
    $router->post('/register', [CompanyController::class, 'register']);
    $router->post('/login', [CompanyController::class, 'login']);
    $router->post('/refresh', [CompanyController::class, 'refreshToken']);

    $router->group(['middleware' => 'jwt.auth'], function () use ($router) {
        $router->get('/me', [CompanyController::class, 'me']);

    });
});

<?php
/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\AuthController;

$router->group(['prefix' => 'user'], function () use ($router) {
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
    $router->post('/refresh', 'AuthController@refreshToken');

    $router->group(['middleware' => 'jwt.auth'], function () use ($router) {
        $router->get('/me', 'AuthController@me');
        $router->post('/logout', 'AuthController@logout');
    });
});

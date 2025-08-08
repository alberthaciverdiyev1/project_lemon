<?php

use App\Http\Controllers\CompanyController;

/** @var \Laravel\Lumen\Routing\Router $router */


$router->group(['prefix' => 'company'], function () use ($router) {
    $router->post('/register', 'CompanyController@register');
    $router->post('/login', 'CompanyController@login');
    $router->post('/refresh', 'CompanyController@refreshToken');

    $router->group(['middleware' => 'jwt.auth'], function () use ($router) {
        $router->get('/me', 'CompanyController@me');

    });
});

<?php

use App\Http\Controllers\VacancyController;

/** @var \Laravel\Lumen\Routing\Router $router */

$router->group(['prefix' => 'vacancy'], function () use ($router) {
    $router->get('/', 'VacancyController@list');
    $router->get('/{slug}', 'VacancyController@details');
});

$router->group(['middleware' => 'jwt.auth'], function () use ($router) {
    $router->post('/', 'VacancyController@store');
    $router->put('/{slug}', 'VacancyController@update');
    $router->delete('/{slug}', 'VacancyController@destroy');
});

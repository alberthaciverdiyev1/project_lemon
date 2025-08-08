<?php
/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\BlogController;

$router->group(['prefix' => 'blog'], function () use ($router) {
    $router->get('/', 'BlogController@list');
    $router->get('/{slug}', 'BlogController@details');
});

$router->group(['middleware' => 'jwt.auth'], function () use ($router) {
    $router->post('/', 'BlogController@store');
    $router->put('/{slug}', 'BlogController@update');
    $router->delete('/{slug}', 'BlogController@destroy');
});

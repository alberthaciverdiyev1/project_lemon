<?php
/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\StaticController;

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'contact'], function () use ($router) {
        $router->get('/', 'StaticController@contacts');
        $router->post('/', 'StaticController@addContact');
    });
});

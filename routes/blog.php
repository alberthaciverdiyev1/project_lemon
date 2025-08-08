<?php
/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\BlogController;

$router->group(['prefix' => 'blog'], function () use ($router) {
    $router->get('/', [BlogController::class, 'list']);
    $router->get('/{slug}', [BlogController::class, 'details']);
});

$router->group(['middleware' => 'jwt.auth'], function () use ($router) {
    $router->post('/', [BlogController::class, 'store']);
    $router->put('/{slug}', [BlogController::class, 'update']);
    $router->delete('/{slug}', [BlogController::class, 'destroy']);
});

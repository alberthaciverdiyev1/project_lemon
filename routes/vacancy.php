<?php

use App\Http\Controllers\VacancyController;

/** @var \Laravel\Lumen\Routing\Router $router */

$router->group(['prefix' => 'vacancy'], function () use ($router) {
    $router->get('/', [VacancyController::class, 'list']);
    $router->get('/{slug}', [VacancyController::class, 'details']);
});

$router->group(['middleware' => 'jwt.auth'], function () use ($router) {
    $router->post('/', [VacancyController::class, 'store']);
    $router->put('/{slug}', [VacancyController::class, 'update']);
    $router->delete('/{slug}', [VacancyController::class, 'destroy']);
});

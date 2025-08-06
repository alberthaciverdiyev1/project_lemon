<?php

require_once __DIR__.'/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));


$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

// Exception handler & kernel
$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

// Load config files
$app->configure('app');
$app->configure('auth');

// Register service providers
$app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);

// Register middleware
$app->routeMiddleware([
    'auth' => App\Http\Middleware\Authenticate::class,
]);

// Load routes
$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/api.php';
});

return $app;

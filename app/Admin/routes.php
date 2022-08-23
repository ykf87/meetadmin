<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('u-c-users', ClientUserController::class);
    $router->resource('orders', OrderController::class);
    $router->resource('consumes', ConsumeControllers::class);
    $router->resource('settlements', SettlementControllers::class);
});

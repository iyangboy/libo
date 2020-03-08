<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');

    // 用户
    // $router->resource('users', UsersController::class);
    $router->resource('users', 'UsersController');
    // 来源
    $router->resource('sources', 'SourcesController');
});

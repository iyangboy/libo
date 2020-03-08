<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => 'admin.'
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');

    // 用户
    // $router->resource('users', UsersController::class);
    $router->resource('users', 'UsersController');
    // 来源
    $router->resource('sources', 'SourcesController');
    // 销售Select
    $router->get('select_admin_user', 'UsersController@selectAdminUser')->name('select_admin_user');

    // 后台用户
    $router->resource('admin-users', 'AdminUsersController');
});

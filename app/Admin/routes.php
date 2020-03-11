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

    // 图片上传（simditor）
    $router->post('simditor_upload_image', 'SimditorController@uploadImage');

    // 用户
    // $router->resource('users', UsersController::class);
    $router->resource('users', 'UsersController');
    // 来源
    $router->resource('sources', 'SourcesController');
    // 销售Select
    $router->get('select_admin_user', 'UsersController@selectAdminUser')->name('select_admin_user');

    // 后台用户
    $router->resource('admin-users', 'AdminUsersController');

    // 设置会员
    $router->resource('vip-products', 'MemberProductsController');

    // 用户Vip订单
    $router->resource('user-vip-orders', 'UserVipOrdersController');

    // 协议
    $router->resource('agreements', 'AgreementsController');

    // 等级
    $router->resource('grades', 'GradesController');

    // 商品
    $router->resource('products', 'ProductsController');

    // 订单
    $router->resource('orders', 'OrdersController');
});

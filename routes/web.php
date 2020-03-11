<?php

use App\Models\AdminUser;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\User;

Route::get('/test', function () {
    $adminUser = User::with(['userInfo'])->find(1);
    dd($adminUser->toArray());
});

Route::get('/test-product-specification', function () {
    //
    $product_id = 1;
    $product = Product::find($product_id);
    $product->update([
        'specification' => [
            'loan_limit'  => [
                'name'    => '贷款限额',
                'options' => ['100000', '200000', '400000'],
                'default' => '100000'
            ],
            'by_stages' => [
                'name'    => '分期',
                'options' => ['三个月', '半年', '一年', '两年'],
                'default' => '半年'
            ],
        ]
    ]);
    // dd($product->toArray());

    // $product->saveMany([
    ProductSku::updateOrCreate([
        'specification' => [
            'loan_limit' => '100000',
            'by_stages' => '三个月'
        ],
        'product_id' => $product_id,
    ], [
        'stock' => 1000,
        'price' => 1000,
        'interest_rate' => 0.0003
    ]);
    ProductSku::updateOrCreate([
        'specification' => [
            'loan_limit' => '100000',
            'by_stages' => '半年'
        ],
        'product_id' => $product_id,
    ], [
        'stock' => 1000,
        'price' => 800,
        'interest_rate' => 0.0004
    ]);
    ProductSku::updateOrCreate([
        'specification' => [
            'loan_limit' => '100000',
            'by_stages' => '一年'
        ],
        'product_id' => $product_id,
    ], [
        'stock' => 1000,
        'price' => 500,
        'interest_rate' => 0.0005
    ]);
    ProductSku::updateOrCreate([
        'specification' => [
            'loan_limit' => '100000',
            'by_stages' => '两年'
        ],
        'product_id' => $product_id,
    ], [
        'stock' => 1000,
        'price' => 2000,
        'interest_rate' => 0.0004
    ]);
    ProductSku::updateOrCreate([
        'specification' => [
            'loan_limit' => '200000',
            'by_stages' => '三个月'
        ],
        'product_id' => $product_id,
    ], [
        'stock' => 1000,
        'price' => 2000,
        'interest_rate' => 0.0004
    ]);
    ProductSku::updateOrCreate([
        'specification' => [
            'loan_limit' => '200000',
            'by_stages' => '半年'
        ],
        'product_id' => $product_id,
    ], [
        'stock' => 1000,
        'price' => 1600,
        'interest_rate' => 0.0004
    ]);
    ProductSku::updateOrCreate([
        'specification' => [
            'loan_limit' => '200000',
            'by_stages' => '一年'
        ],
        'product_id' => $product_id,
    ], [
        'stock' => 1000,
        'price' => 1200,
        'interest_rate' => 0.0004
    ]);
    ProductSku::updateOrCreate([
        'specification' => [
            'loan_limit' => '200000',
            'by_stages' => '两年'
        ],
        'product_id' => $product_id,
    ], [
        'stock' => 1000,
        'price' => 4000,
        'interest_rate' => 0.0004
    ]);
    ProductSku::updateOrCreate([
        'specification' => [
            'loan_limit' => '400000',
            'by_stages' => '三个月'
        ],
        'product_id' => $product_id,
    ], [
        'stock' => 1000,
        'price' => 4000,
        'interest_rate' => 0.0004
    ]);
    ProductSku::updateOrCreate([
        'specification' => [
            'loan_limit' => '400000',
            'by_stages' => '半年'
        ],
        'product_id' => $product_id,
    ], [
        'stock' => 1000,
        'price' => 3200,
        'interest_rate' => 0.0004
    ]);
    ProductSku::updateOrCreate([
        'specification' => [
            'loan_limit' => '400000',
            'by_stages' => '一年'
        ],
        'product_id' => $product_id,
    ], [
        'stock' => 1000,
        'price' => 2400,
        'interest_rate' => 0.0004
    ]);
    ProductSku::updateOrCreate([
        'specification' => [
            'loan_limit' => '400000',
            'by_stages' => '两年'
        ],
        'product_id' => $product_id,
    ], [
        'stock' => 1000,
        'price' => 8000,
        'interest_rate' => 0.0004
    ]);
    dd($product);
});

Route::get('test-pgw-pay', 'PgwPayController@testPay');

Route::get('alipay', function () {
    return app('alipay')->web([
        'out_trade_no' => time(),
        'total_amount' => '1',
        'subject' => 'test subject - 测试',
    ]);
});

Route::get('phpinfo', function () {
    phpinfo();
});

Route::redirect('/', '/admin');
/*
Route::get('/', 'TopicsController@index')->name('root');

// 用户身份验证相关的路由
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// 用户注册相关路由
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// 密码重置相关路由
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Email 认证相关路由
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

Route::resource('users', 'UsersController', ['only' => ['show', 'update', 'edit']]);

Route::resource('topics', 'TopicsController', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);

Route::resource('categories', 'CategoriesController', ['only' => ['show']]);
Route::post('upload_image', 'TopicsController@uploadImage')->name('topics.upload_image');

Route::get('topics/{topic}/{slug?}', 'TopicsController@show')->name('topics.show');

Route::resource('replies', 'RepliesController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);

Route::resource('replies', 'RepliesController', ['only' => ['store', 'destroy']]);

Route::resource('notifications', 'NotificationsController', ['only' => ['index']]);

Route::get('permission-denied', 'PagesController@permissionDenied')->name('permission-denied');

*/

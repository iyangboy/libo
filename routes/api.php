<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// Route::group(['prefix' => 'auth', 'namespace' => 'Api'], function () {
//     Route::post('register', 'Auth\RegisterController@register');
//     Route::post('login', 'Auth\LoginController@login');
//     Route::get('me', 'Auth\MeController@me');
// });

Route::prefix('v1')
    ->namespace('Api')
    ->middleware('change-locale')
    ->name('api.v1.')
    ->group(function () {

        Route::middleware('throttle:' . config('api.rate_limits.sign'))
            ->group(function () {

                Route::group(['prefix' => 'auth'], function () {
                    Route::post('register-captcha', 'Auth\RegisterController@registerCaptcha');
                    Route::post('registerCaptcha', 'Auth\RegisterController@register');
                    Route::post('login', 'Auth\LoginController@login');
                    Route::get('me', 'Auth\MeController@me');
                });

                // 图片验证码
                Route::post('captchas', 'CaptchasController@store')
                    ->name('captchas.store');
                // 短信验证码
                Route::post('verificationCodes', 'VerificationCodesController@store')
                    ->name('verificationCodes.store');
                // 手机注册验证码
                Route::post('phoneRegisterCode', 'VerificationCodesController@phoneRegisterCode')
                    ->name('verificationCodes.phoneRegisterCode');

                // 用户注册
                Route::post('users', 'UsersController@store')
                    ->name('users.store');
                // 第三方登录
                Route::post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
                    ->where('social_type', 'weixin')
                    ->name('socials.authorizations.store');
                // 登录
                Route::post('authorizations', 'AuthorizationsController@store')
                    ->name('authorizations.store');
                // 小程序登录
                Route::post('weapp/authorizations', 'AuthorizationsController@weappStore')
                    ->name('api.weapp.authorizations.store');
                // 刷新token
                Route::put('authorizations/current', 'AuthorizationsController@update')
                    ->name('authorizations.update');
                // 删除token
                Route::delete('authorizations/current', 'AuthorizationsController@destroy')
                    ->name('authorizations.destroy');
            });

        Route::middleware('throttle:' . config('api.rate_limits.access'))
            ->group(function () {
                // 游客可以访问的接口

                // 某个用户的详情
                Route::get('users/{user}', 'UsersController@show')
                    ->name('users.show');
                // 分类类表
                Route::get('categories', 'CategoriesController@index')
                    ->name('categories.index');
                // 某个用户发布的话题
                Route::get('users/{user}/topics', 'TopicsController@userIndex')
                    ->name('users.topics.index');
                // 话题列表，详情
                Route::resource('topics', 'TopicsController')->only([
                    'index', 'show'
                ]);
                // 话题回复列表
                Route::get('topics/{topic}/replies', 'RepliesController@index')
                    ->name('topics.replies.index');
                // 某个用户的回复列表
                Route::get('users/{user}/replies', 'RepliesController@userIndex')
                    ->name('users.replies.index');
                // 资源推荐
                Route::get('links', 'LinksController@index')
                    ->name('links.index');
                // 活跃用户
                Route::get('actived/users', 'UsersController@activedIndex')
                    ->name('actived.users.index');

                // 省市区
                Route::get('china_areas','ChinaAreasController@index');
                // 银行卡信息
                Route::get('bank_codes','BankCodesController@index');

                // 登录后可以访问的接口
                Route::middleware('auth:api')->group(function () {
                    // 当前登录用户信息
                    Route::get('user', 'UsersController@me')
                        ->name('user.show');
                    // 编辑登录用户信息
                    Route::patch('user', 'UsersController@update')
                        ->name('user.update');
                    // 上传图片
                    Route::post('images', 'ImagesController@store')
                        ->name('images.store');
                    // 发布话题
                    Route::resource('topics', 'TopicsController')->only([
                        'store', 'update', 'destroy'
                    ]);
                    // 发布回复
                    Route::post('topics/{topic}/replies', 'RepliesController@store')
                        ->name('topics.replies.store');
                    // 删除回复
                    Route::delete('topics/{topic}/replies/{reply}', 'RepliesController@destroy')
                        ->name('topics.replies.destroy');
                    // 通知列表
                    Route::get('notifications', 'NotificationsController@index')
                        ->name('notifications.index');
                    // 通知统计
                    Route::get('notifications/stats', 'NotificationsController@stats')
                        ->name('notifications.stats');
                    // 标记消息通知为已读
                    Route::patch('user/read/notifications', 'NotificationsController@read')
                        ->name('user.notifications.read');
                    // 当前登录用户权限
                    Route::get('user/permissions', 'PermissionsController@index')
                        ->name('user.permissions.index');

                    // 支付宝支付
                    Route::get('payment/{order}/alipay', 'PaymentController@payByAlipay')
                        ->name('payment.alipay');

                    // 创建分期接口
                    Route::post('payment/{order}/installment', 'PaymentController@payByInstallment')
                        ->name('payment.installment');

                    // 退出登录
                    Route::delete('auth/logout', 'Auth\LoginController@logout');

                    // 购买授信额度-列表
                    Route::get('credit_line_products', 'CreditLinesController@index');
                    // 购买授信额度-订单
                    Route::resource('credit_line_orders', 'CreditLineOrdersController');

                    // 借款订单
                    Route::resource('loan_orders', 'LoanOrdersController');

                    // 接口 绑定身份证
                    Route::post('user_set_id_card','Auth\MeController@SetIdCard');
                    // 设置用户基本信息
                    Route::post('set_user_info','Auth\MeController@setUserInfo');
                    // 绑定银行卡
                    Route::post('set_user_bank','Auth\MeController@setBank');
                    // 购买vip
                    Route::post('purchase_vip/{vip_id}','VipOrdersController@purchaseVip');

                    // 支付-签约发短信
                    Route::post('pgw-signing-sms/{vip_order_id}', 'PgwPay\PgwPayController@signingSMS');
                    // 支付-签约
                    Route::post('pgw-signing/{log_id}', 'PgwPay\PgwPayController@signing');
                    // 协议支付
                    Route::post('pgw-agreement-payment/{vip_order_id}', 'PgwPay\PgwPayController@agreementPayment');
                    // 协议支付-短信验证
                    Route::post('pgw-agreement-payment-verification/{log_id}', 'PgwPay\PgwPayController@agreementPaymentVerification');
                });
            });

        // 支付宝前端支付回调
        Route::get('payment/alipay/return', 'PaymentController@alipayReturn')
            ->name('payment.alipay.return');
        // 支付宝后端支付回调
        Route::post('payment/alipay/notify', 'PaymentController@alipayNotify')->name('payment.alipay.notify');
    });

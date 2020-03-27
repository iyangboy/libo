<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PhoneCodeRequest;
use App\Http\Requests\Api\RegisterUserRequest;
use App\Http\Requests\Api\SourcesRegisterRequest;
use App\Http\Requests\Api\SourcesSendSmsRequest;
use App\Http\Resources\PrivateUserResource;
use App\Models\Source;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Overtrue\EasySms\EasySms;

class SourcesController extends Controller
{
    //
    public function sendsms(SourcesSendSmsRequest $request, EasySms $easySms)
    {
        $phone = $request->phone ?? '';
        $source = $request->source ?? '';

        $code = '1234';
        // if (!app()->environment('production')) {
        //     $code = '1234';
        // } else {
        //     // 生成4位随机数，左侧补0
        //     $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

        //     try {
        //         $result = $easySms->send($phone, [
        //             'template' => config('easysms.gateways.aliyun.templates.register'),
        //             'data' => [
        //                 'code' => $code
        //             ],
        //         ]);
        //     } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
        //         $message = $exception->getException('aliyun')->getMessage();
        //         abort(500, $message ?: '短信发送异常');
        //     }
        // }

        $key = 'sourcesCode_' . Str::random(15);
        $expiredAt = now()->addMinutes(5);
        // 缓存验证码 5分钟过期。
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        return response()->json([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ], 201)->setStatusCode(201);

    }

    // 用户注册
    public function register(SourcesRegisterRequest $request)
    {
        $verifyData = \Cache::get($request->verification_key);

        if (!$verifyData) {
            abort(403, '验证码已失效');
        }

        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            // 返回401
            throw new AuthenticationException('验证码错误');
        }

        // 来源
        $source_slug = $request->source ?? '';
        if ($source_slug) {
            $source = Source::where('slug', $source_slug)->first();
            if ($source) {
                $base = $source->user_count_decrement_base ?? 0;
                $real = $source->user_count_real ?? 0;

                $remainder = $real % ($base + 1);
                $source->increment('user_count_real');
                if ($remainder) {
                    $source->increment('user_count');
                    $source_id = $source->id;
                } else {
                    $source->increment('user_count_decrement');
                }
            }
        }

        $user = User::create([
            'phone'     => $verifyData['phone'],
            'password'  => bcrypt($request->password),
            'source_id' => $source_id ?? 1, // 来源()
        ]);

        // 清除验证码缓存
        \Cache::forget($request->verification_key);

        return response()->json([
            'success' => ['注册成功'],
            'data'    => new PrivateUserResource($user),
        ], 201);

    }

}

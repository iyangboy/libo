<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginPhoneCodeRequest;
use App\Http\Requests\Api\LoginUserRequest;
use App\Http\Resources\PrivateUserResource;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;

class LoginController extends Controller
{
    //
    public function login(LoginUserRequest $request)
    {
        $credentials['phone'] = $request->phone;
        $credentials['password'] = $request->password;

        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return response()->json([
                'error' => [
                    'phone' => '无法通过密码验证'
                ],
                'message' => '账号密码不正确'
            ], 422);
        }

        $user = auth('api')->user();

        return (new PrivateUserResource($user))->additional([
            'meta' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                // 'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            ]
        ]);
    }

    // 手机短信登录注册
    public function loginPhoneCode(LoginPhoneCodeRequest $request)
    {
        $phone = $request->phone ?? '';
        $verification_key = $request->verification_key ?? '';
        $verification_code = $request->verification_code ?? '';

        $verifyData = \Cache::get($request->verification_key);

        if (!$verifyData) {
            abort(403, '验证码已失效');
        }

        if (!hash_equals($verifyData['code'], $verification_code)) {
            // 返回401
            throw new AuthenticationException('验证码错误');
        }

        $user = User::where('phone', $verifyData['phone'])->first();
        if ($user) {
            // 存在直接登录
        } else {
            // 不存在，创建用户
            $user = User::create([
                'name' => $request->name,
                'phone' => $verifyData['phone'],
                // 'password' => bcrypt($request->password),
            ]);
        }

        // 清除验证码缓存
        \Cache::forget($request->verification_key);

        return response()->json([
            'success' => ['注册成功'],
            'data'    => new PrivateUserResource($user),
            'meta' => [
                'access_token' => \Auth::guard('api')->login($user),
                'token_type' => 'Bearer',
                // 'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            ]
        ], 200);

    }

    // 退出登录
    public function logout()
    {
        auth('api')->logout();
        return response(null, 204);
    }
}

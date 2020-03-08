<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUserRequest;
use App\Http\Resources\PrivateUserResource;
use App\Models\User;

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
                ]
            ], 422);
        }

        $user = auth('api')->user();

        return (new PrivateUserResource($user))->additional([
            'meta' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            ]
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterUserRequest;
use App\Http\Resources\PrivateUserResource;
use App\Models\User;

class RegisterController extends Controller
{
    // 用户注册
    public function register(RegisterUserRequest $request)
    {
        // return $request->all();
        $user = User::create([
            'phone'    => $request->phone,
            'name'     => $request->name,
            'password' => bcrypt($request->password),
        ]);

        return new PrivateUserResource($user);
    }
}

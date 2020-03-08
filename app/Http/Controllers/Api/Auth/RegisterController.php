<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterUserRequest;
use App\Http\Resources\PrivateUserResource;
use App\Models\Source;
use App\Models\User;

class RegisterController extends Controller
{
    // 用户注册
    public function register(RegisterUserRequest $request)
    {
        // 来源
        $source_slug = $request->source_code ?? '';
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

        // return $request->all();
        $user = User::create([
            'phone'     => $request->phone,
            'name'      => $request->name,
            'password'  => bcrypt($request->password),

            'source_id' => $source_id ?? 1, // 来源()
        ]);

        return new PrivateUserResource($user);
    }
}

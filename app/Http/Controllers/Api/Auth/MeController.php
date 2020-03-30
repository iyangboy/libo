<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\StatisticsController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserSetBankRequest;
use App\Http\Requests\Api\UserSetIdCardRequest;
use App\Http\Requests\Api\UserSetUserInfoRequest;
use App\Http\Resources\PrivateUserResource;
use App\Models\UserBankCard;
use App\Models\UserInfo;
use App\Models\UserRelation;

class MeController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    //
    public function me(Request $request)
    {
        $user = $request->user();
        $token = auth('api')->refresh();
        return (new PrivateUserResource($user))->additional([
            'meta' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                // 'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            ]
        ]);

        // 增加uv
        $statistic = new StatisticsController();
        $statistic->uvAdd();


        return new PrivateUserResource($request->user());
    }

    // 绑定身份证
    public function SetIdCard(UserSetIdCardRequest $request)
    {
        $id_card = $request->id_card ?? '';
        $real_name = $request->user_name ?? '';
        $card_front_path = $request->card_front_path ?? '';
        $card_back_path = $request->card_back_path ?? '';

        $relation_name = $request->relation_name ?? '';
        $relation_type = $request->relation_type ?? '';
        $relation_phone = $request->relation_phone ?? '';

        $user = $request->user();

        $user->id_card   = $id_card;
        $user->real_name = $real_name;
        // $user->card_front_path = $card_front_path;
        // $user->card_back_path = $card_back_path;
        $user->save();

        $userRelation = UserRelation::updateOrCreate(
            [
                'user_id' => $user->id,
                'phone' => $relation_phone,
            ],
            [
                'relation' => $relation_type,
                'name' => $relation_name,
            ]
        );


        $token = auth('api')->refresh();
        return response()->json([
            'meta' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                // 'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            ],
            'data'    => new PrivateUserResource($request->user())
        ], 200);
    }

    // 设置用户基本信息
    public function setUserInfo(UserSetUserInfoRequest $request)
    {
        $user_id = $request->user()->id;

        $china_areas = $request->china_areas ?? '';
        $china_areas = explode('-', $china_areas);

        $info = UserInfo::updateOrCreate(
            ['user_id' => $user_id],
            [
                'province'          => $request->province ?? $china_areas[0],
                'city'              => $request->city ?? $china_areas[1],
                'district'          => $request->district ?? $china_areas[2],
                'address'           => $request->address,
                'occupation'        => $request->occupation,
                'phone_long_time'   => $request->phone_long_time,
                'overdue_state'     => $request->overdue_state,
                'social_insurance'  => $request->social_insurance,
                'accumulation_fund' => $request->accumulation_fund,
                'monthly_pay'       => $request->monthly_pay,
            ]
        );

        $token = auth('api')->refresh();
        return response()->json([
            'meta' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                // 'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            ],
            'data'    => new PrivateUserResource($request->user())
        ], 200);
    }

    // 绑定银行卡
    public function setBank(UserSetBankRequest $request)
    {
        $user_id = $request->user()->id;

        $info = UserBankCard::updateOrCreate(
            [
                'user_id'     => $user_id,
                'card_number' => $request->card_number,
            ],
            [
                'user_name'      => $request->user_name,
                'bank_name'      => $request->bank_name ?? '',
                'phone'          => $request->phone,
                'bank_code_id'   => $request->bank_code_id,
                'address'        => $request->address ?? '',
            ]
        );

        $token = auth('api')->refresh();
        return response()->json([
            'meta' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                // 'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            ],
            'data'    => new PrivateUserResource($request->user())
        ], 200);
    }
}

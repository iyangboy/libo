<?php

namespace App\Http\Requests\Api;


class UserSetUserInfoRequest extends FormRequest
{
    public function rules()
    {
        return [
            'province'          => 'required|string',
            'city'              => 'required|string',
            'district'          => 'required|string',
            'address'           => 'required|string',
            'occupation'        => 'required|string',
            'phone_long_time'   => 'required|string',
            'overdue_state'     => 'required|string',
            'social_insurance'  => 'required',
            'accumulation_fund' => 'required',
            'monthly_pay'       => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'province'          => '省份',
            'city'              => '城市',
            'district'          => '地区',
            'address'           => '地址详细',
            'occupation'        => '职业',
            'phone_long_time'   => '手机入网时长',
            'overdue_state'     => '网贷逾期情况',
            'social_insurance'  => '社保',
            'accumulation_fund' => '公积金',
            'monthly_pay'       => '月薪',
        ];
    }
}

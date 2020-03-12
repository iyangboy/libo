<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PgwPayLog extends Model
{
    //支付记录
    protected $table = 'pgw_pay_log';

    protected $primaryKey = 'id';

    protected $guarded = [];

    // 所属订单
    public function vipOrder()
    {
        return $this->belongsTo(UserVip::class, 'vip_order_id');
    }

    // 所属用户
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

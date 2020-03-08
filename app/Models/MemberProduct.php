<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberProduct extends Model
{
    // 会员类型
    protected $table = 'member_products';

    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $casts = [
        'on_sale' => 'boolean', // on_sale 是一个布尔类型的字段
    ];
}

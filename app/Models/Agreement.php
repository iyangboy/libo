<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    // 协议管理
    protected $table = 'agreements';

    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $casts = [
        'on_sale' => 'boolean', // on_sale 是一个布尔类型的字段
    ];

    // 所属管理员
    public function adminUser()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }
}

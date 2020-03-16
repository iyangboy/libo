<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditLineProduct extends Model
{
    // 授信额度
    protected $table = 'credit_line_products';

    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $casts = [
        'on_sale' => 'boolean', // on_sale 是一个布尔类型的字段
        'specification' => 'array', // object
    ];

    // 管理员
    public function adminUser()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }
}

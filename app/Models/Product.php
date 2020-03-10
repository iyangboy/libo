<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // 商品列表
    protected $table = 'products';

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

    // SKU
    public function productSkus()
    {
        return $this->hasMany(ProductSku::class, 'product_id');
    }

    // 规格
    public function productSpecification()
    {
        return $this->hasMany(ProductSpecification::class, 'product_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductByStage extends Model
{
    // 商品列表选项-分期
    protected $table = 'product_by_stages';

    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $casts = [
        'on_sale' => 'boolean', // on_sale 是一个布尔类型的字段
    ];

    // 所属商品
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

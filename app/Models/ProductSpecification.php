<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    //
    // 商品列表选项
    protected $table = 'product_specifications';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

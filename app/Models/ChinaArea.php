<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ChinaArea extends Model
{
    // 省市区
    protected $table = 'china_area';

    protected $primaryKey = 'id';

    protected $guarded = [];

    // 顶级
    public function scopeRoots(Builder $builder)
    {
        $builder->where('parent_id', 1);
    }

    // 获取下级
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }
}

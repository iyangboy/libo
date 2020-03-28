<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Source extends Model
{
    protected $table = 'sources';

    protected $primaryKey = 'id';

    protected $guarded = [];

    // slug编码
    public static function findAvailableSlug($length = 16)
    {
        do {
            // 生成一个指定长度的随机字符串，并转成大写
            $slug = strtoupper(Str::random($length));
        // 如果生成的码已存在就继续循环
        } while (self::query()->where('slug', $slug)->exists());

        return $slug;
    }

    // 关联管理员
    public function adminUser()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }

    // 关联用户
    public function users()
    {
        return $this->hasMany(User::class, 'source_id');
    }
}

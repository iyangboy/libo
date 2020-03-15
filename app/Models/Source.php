<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $table = 'sources';

    protected $primaryKey = 'id';

    protected $guarded = [];

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

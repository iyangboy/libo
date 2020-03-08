<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $table = 'user_infos';

    protected $primaryKey = 'id';

    protected $guarded = [];

    // 地址信息
    public function getFullAddressAttribute()
    {
        return "{$this->province}{$this->city}{$this->district}{$this->address}";
    }

    // 所属用户
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

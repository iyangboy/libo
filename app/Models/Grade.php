<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    // 用户 等级
    protected $table = 'grades';

    protected $primaryKey = 'id';

    protected $guarded = [];


    // 用户绑定银行卡
    public function users()
    {
        return $this->hasMany(User::class, 'grade_id');
    }
}

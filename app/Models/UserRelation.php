<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRelation extends Model
{
    // 用户紧急联系人
    protected $table = 'user_relations';

    protected $primaryKey = 'id';

    protected $guarded = [];

    // 所属用户
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

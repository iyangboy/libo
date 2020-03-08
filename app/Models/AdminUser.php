<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    protected $table = 'admin_users';

    protected $primaryKey = 'id';

    protected $guarded = [];

    // public $appends = ['user_count'];

    // 来源数
    public function getSourceCountAttribute()
    {
        return $this->sources->sum('user_count');
    }

    public function setSourceCountAttribute()
    {
        $this->attributes['source_count'] = $this->sources->sum('user_count');
    }

    // 实际来源数
    public function getSourceRealCountAttribute()
    {
        return $this->sources->sum('user_count_real');
    }

    public function setSourceRealCountAttribute()
    {
        $this->attributes['source_real_count'] = $this->sources->sum('user_count_real');
    }

    public function sources()
    {
        return $this->hasMany(Source::class, 'admin_user_id');
    }
}

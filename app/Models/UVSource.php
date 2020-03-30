<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UVSource extends Model
{
    protected $table = 'uv_sources';

    protected $primaryKey = 'id';

    protected $guarded = [];

    // 关联用户
    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $table = 'sources';

    protected $primaryKey = 'id';

    protected $guarded = [];
}

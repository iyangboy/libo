<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankCode extends Model
{
    //
    protected $table = 'bank_codes';

    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $casts = [
        'on_sale' => 'boolean', // on_sale 是一个布尔类型的字段
    ];
}

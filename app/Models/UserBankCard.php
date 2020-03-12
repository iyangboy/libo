<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBankCard extends Model
{
    //
    protected $table = 'user_bank_cards';

    protected $primaryKey = 'id';

    protected $guarded = [];

    // 所属用户
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 所属银行
    public function bankCode()
    {
        return $this->belongsTo(BankCode::class, 'bank_code_id');
    }
}

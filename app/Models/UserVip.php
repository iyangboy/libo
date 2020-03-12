<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVip extends Model
{
    // 用户 vip 订单
    protected $table = 'user_vips';

    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $casts = [
        'closed'    => 'boolean',
    ];

    protected $dates = [
        'paid_at',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_FAILED = 'failed';
    const STATUS_SUCCESS = 'success';

    public static $statusMap = [
        self::STATUS_PENDING   => '待支付',
        self::STATUS_FAILED    => '支付失败',
        self::STATUS_SUCCESS   => '支付成功',
    ];

    protected static function boot()
    {
        parent::boot();
        // 监听模型创建事件，在写入数据库之前触发
        static::creating(function ($model) {
            // 如果模型的 no 字段为空
            if (!$model->no) {
                // 调用 findAvailableNo 生成订单流水号
                $model->no = static::findAvailableNo();
                // 如果生成失败，则终止创建订单
                if (!$model->no) {
                    return false;
                }
            }
        });
    }

    public static function findAvailableNo()
    {
        // 订单流水号前缀
        $prefix = date('YmdHis');
        for ($i = 0; $i < 10; $i++) {
            // 随机生成 6 位的数字
            $no = $prefix.str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            // 判断是否已经存在
            if (!static::query()->where('no', $no)->exists()) {
                return $no;
            }
        }
        \Log::warning('find order no failed');

        return false;
    }

    // 所属用户
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 所属VIP产品
    public function vipProduct()
    {
        return $this->belongsTo(MemberProduct::class, 'vip_id');
    }

    // 支付记录
    public function pgwPayLog()
    {
        return $this->belongsTo(PgwPayLog::class, 'vip_order_id');
    }

}

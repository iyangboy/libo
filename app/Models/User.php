<?php

namespace App\Models;

use Auth;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;

class User extends Authenticatable implements MustVerifyEmailContract, JWTSubject
{
    use HasRoles;
    use MustVerifyEmailTrait;
    use Traits\ActiveUserHelper;
    use Traits\LastActivedAtHelper;

    use Notifiable {
        notify as protected laravelNotify;
    }
    public function notify($instance)
    {
        // 如果要通知的人是当前用户，就不必通知了！
        if ($this->id == Auth::id()) {
            return;
        }

        // 只有数据库类型通知才需提醒，直接发送 Email 或者其他的都 Pass
        if (method_exists($instance, 'toDatabase')) {
            $this->increment('notification_count');
        }

        $this->laravelNotify($instance);
    }

    protected $fillable = [
        'name', 'phone', 'email', 'password', 'introduction', 'avatar',
        'weixin_openid', 'weixin_unionid', 'registration_id',
        'weixin_session_key', 'weapp_openid',
        'source_id', 'id_card', 'real_name', 'card_front_path', 'card_back_path'
    ];

    protected $hidden = [
        'password', 'remember_token', 'weixin_openid', 'weixin_unionid'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'card_verified_at' => 'datetime',
    ];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    public function setPasswordAttribute($value)
    {
        // 如果值的长度等于 60，即认为是已经做过加密的情况
        if (strlen($value) != 60) {

            // 不等于 60，做密码加密处理
            $value = bcrypt($value);
        }

        $this->attributes['password'] = $value;
    }

    public function setAvatarAttribute($path)
    {
        // 如果不是 `http` 子串开头，那就是从后台上传的，需要补全 URL
        if ( ! \Str::startsWith($path, 'http')) {

            // 拼接完整的 URL
            $path = config('app.url') . "/uploads/images/avatars/$path";
        }

        $this->attributes['avatar'] = $path;
    }

    public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	public function getJWTCustomClaims()
	{
		return [];
    }

    // 关联来源
    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id');
    }

    // 用户基本信息
    public function userInfo()
    {
        return $this->hasOne(UserInfo::class, 'user_id');
    }

    // 用户绑定银行卡
    public function userBankCards()
    {
        return $this->hasMany(UserBankCard::class, 'user_id');
    }

    // 用户绑定银行卡
    public function userBankCard()
    {
        return $this->hasOne(UserBankCard::class, 'user_id')->orderBy('id', 'desc');
    }
    // 用户已签约银行卡
    public function userBankCardProtocol()
    {
        return $this->hasOne(UserBankCard::class, 'user_id')->where('protocol_id', '>', 0)->orderBy('id', 'desc');
    }

    // 用户等级
    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
// use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $primaryKey = 'user_id';

    // protected $appends = [
    //    'userImages'
    // ];


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'full_name',
        'email_address',
        'mobile_number',
        'gender_id',
        'sexcual_orientation_id',
        'intrested_ids',
        'user_location',
        'user_bio',
        'user_pic_name',
        'gender_id',
        'password',
        'social_media_id',
        'user_location',
        'user_bio',
        'about_me',
        'device_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userImages(){
        return $this->hasMany(UsersImage::class,'user_id','user_id');
    }

    public function notificaion(){
        return $this->hasMany(Notification::class,'user_id','user_id');
    }

    public function userMatch()
    {
        return $this->hasMany(MatchUser::class,'to_user_id','user_id');
    }

    public function gender()
    {
        return $this->hasOne(Misc::class,'misc_id','gender_id');
    }

    public function sexcualOrientation()
    {
        return $this->hasOne(Misc::class,'misc_id','sexcual_orientation_id');
    }

    public function like()
    {
        return $this->hashOne(Like::class,'to_user_id','user_id');
    }
}

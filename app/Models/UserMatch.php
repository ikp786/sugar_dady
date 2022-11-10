<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_user_id',
        'to_user_id'
    ];

    public function user()
    {
        // echo auth()->user()->user_id;die;
        return $this->hasMany(User::class,'user_id','to_user_id');
    }
}
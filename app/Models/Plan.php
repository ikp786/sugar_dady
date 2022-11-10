<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $primaryKey = 'plan_id';
    protected $fillable = [
        'plan_title',
        'plan_price',
        'plan_duration'
    ];
}

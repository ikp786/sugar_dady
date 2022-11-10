<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Misc extends Model
{
    use HasFactory;

    protected $table = 'misc_mst';
    protected $primaryKey = 'misc_id';

    protected $fillable = [
        'misc_title',
        'misc_type'
    ];

}

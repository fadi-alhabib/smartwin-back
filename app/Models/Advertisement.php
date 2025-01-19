<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'path',
        'home_ad',
        'priority',
        'is_img',
        'from_date',
        'to_date',
        'is_active',
    ];
}

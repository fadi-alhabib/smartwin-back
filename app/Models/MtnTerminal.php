<?php

// app/Models/MtnTerminal.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MtnTerminal extends Model
{
    protected $fillable = ['terminal_id', 'settings', 'activated_at'];
    protected $casts = ['settings' => 'array', 'activated_at' => 'datetime'];
}

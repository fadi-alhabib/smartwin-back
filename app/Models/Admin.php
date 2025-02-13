<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'username',
        'password',
    ];

    // It’s a good idea to hide sensitive fields
    protected $hidden = [
        'password',
        'remember_token',
    ];
    // Relationships
    public function privileges()
    {
        return $this->belongsToMany(Privilege::class, 'admins_privileges');
    }
}

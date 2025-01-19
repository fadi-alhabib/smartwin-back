<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'username',
        'password',
    ];

    // Relationships
    public function privileges()
    {
        return $this->belongsToMany(Privilege::class, 'admins_privileges');
    }
}

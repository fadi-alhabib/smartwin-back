<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasFactory, HasApiTokens;

    protected $fillable = [
        'email',
        'full_name',
        'phone',
        'country',
        'points',
        'google_id',
        'invite_code',
        'is_active',
        'otp',
        'otp_expires_at',
    ];

    protected $hidden = [
        'remember_token',
        'otp',
        'otp_expires_at',
        'google_id',
        'is_active',
    ];


    // Relationships
    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }

    public function transferRequests()
    {
        return $this->hasMany(TransferRequest::class);
    }

    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'owner_id');
    }

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }

    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'image', 'online', 'available_time', 'host_id'];
    protected $casts = [
        'online' => "bool",
    ];
    // Relationships
    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function c4Games()
    {
        return $this->hasMany(C4Game::class);
    }

    public function timePurchases()
    {
        return $this->hasMany(TimePurchase::class);
    }
}

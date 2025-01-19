<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimePurchase extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'additional_minutes'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}

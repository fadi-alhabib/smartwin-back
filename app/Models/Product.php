<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'store_id',
    ];

    // Relationships
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }

    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }

    public function averageRating()
    {
        return (int)round($this->ratings()->avg('rating'));
    }

    public function userHasRated()
    {
        $user = auth('sanctum')->user();
        return $this->ratings()->where('user_id', $user->id)->exists();
    }

    public function userRating()
    {
        $user = auth('sanctum')->user();
        $rating = $this->ratings()->where('user_id', $user->id)->first();
        if ($rating) {
            return $rating['rating'];
        }
        return null;
    }
}

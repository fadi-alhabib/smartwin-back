<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->ratings()->avg('rating');
    }
}

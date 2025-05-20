<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'store_id',
        'quantity',
        'points_spent',
        'status',
    ];

    /**
     * Get the user who made the purchase.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the purchased product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the store from which the product is bought.
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}

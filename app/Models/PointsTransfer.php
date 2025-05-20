<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointsTransfer extends Model
{
    use HasFactory;

    protected $table = 'points_transfers';

    protected $fillable = [
        'user_id',
        'store_id',
        'admin_id',
        'points',
        'type',
        'accepted'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }


    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}

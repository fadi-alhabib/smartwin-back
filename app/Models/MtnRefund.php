<?php

// app/Models/MtnRefund.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MtnRefund extends Model
{
    protected $fillable = [
        'mtn_payment_id',
        'base_invoice',
        'refund_invoice',
        'refund_amount',
        'commission',
        'tax_sender',
        'status',
        'parameters',
    ];
    protected $casts = ['parameters' => 'array'];
}

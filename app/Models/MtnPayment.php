<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MtnPayment extends Model
{
    protected $table = 'mtn_payments';

    protected $fillable = [
        'invoice_id',
        'amount',
        'guid',
        'ttl',
        'status',
        'otp_sent',
        'confirmed',
        'customer_phone',
        'response_data',
        'meta',
        'invoice_number',
        'user_id',
    ];

    protected $casts = [
        'otp_sent'      => 'boolean',
        'confirmed'     => 'boolean',
        'response_data' => 'array',
        'meta'          => 'array',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'business_name', 'phone', 'email', 'address',
        'opening_time', 'closing_time', 'default_due_days',
        'low_stock_notification', 'transaction_notification',
    ];

    protected $casts = [
        'low_stock_notification' => 'boolean',
        'transaction_notification' => 'boolean',
    ];
}
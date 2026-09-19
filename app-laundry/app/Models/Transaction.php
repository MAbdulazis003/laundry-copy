<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'cashier_id', 'total_weight', 'total_price', 'status', 'received_at', 'due_at', 'ready_at', 'picked_up_at', 'completed_at', 'notes'
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'due_at' => 'datetime',
        'ready_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function operations()
    {
        return $this->hasMany(OperationsLog::class);
    }

    public function receipt()
    {
        return $this->hasOne(Receipt::class);
    }
}

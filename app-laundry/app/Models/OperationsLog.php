<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationsLog extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id', 'operator_id', 'stage', 'performed_at', 'notes'];

    protected $casts = [
        'performed_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
}

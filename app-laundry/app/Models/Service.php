<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'pricing_type', 'price', 'estimated_days'];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleLineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'name',
        'quantity',
        'price'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getTotalPrice()
    {
        return $this->item->price * $this->quantity;
    }
}

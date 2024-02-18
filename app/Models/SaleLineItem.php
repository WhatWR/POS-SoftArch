<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleLineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'quantity',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getTotalPrice()
    {
        if (!$this->relationLoaded('item')) {
            $this->load('item');
        }
        return $this->item->price * $this->quantity;
    }
}

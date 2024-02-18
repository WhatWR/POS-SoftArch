<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['totalPrice']; // Define any other fillable fields if necessary

    public function saleLineItems()
    {
        return $this->hasMany(SaleLineItem::class);
    }

    public function getTotalPrice()
    {
        return $this->saleLineItems->sum(function ($saleLineItem) {
            return $saleLineItem->getTotalPrice();
        });
    }
}

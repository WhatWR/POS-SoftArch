<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['totalPrice']; 

    public function saleLineItems()
    {
        return $this->hasMany(SaleLineItem::class);
    }

    public function member ()
    {
        return $this->belongsTo(Member::class);
    }

    public function getTotalPrice()
    {
        $totalPrice = $this->saleLineItems->sum(function ($saleLineItem) {
            return $saleLineItem->getTotalPrice();
        });

        if ($this->member) {
            $totalPrice *= 0.9;
        }

        return $totalPrice;
    }
}

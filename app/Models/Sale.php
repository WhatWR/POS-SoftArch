<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    public $sale_id;
    public $saleLineItems;
    public $totalPrice;

    public function __construct($sale_id = null, $saleLineItems = [], $totalPrice = 0)
    {
        $this->sale_id = $sale_id;
        $this->saleLineItems = $saleLineItems;
        $this->totalPrice = $totalPrice;
    }
}

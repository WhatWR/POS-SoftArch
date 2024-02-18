<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\SaleLineItem;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $items = Item::all();

        return view('sales.index');
    }
    public function start(Request $request)
    {
        $items = Item::all();
    
        $sale = $request->session()->get('sale', new Sale());

        return view('sales.start', compact('items', 'sale'));
    }

    public function addSaleLineItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|numeric|min:1'
        ]);

        $item = Item::findOrFail($request->item_id);

        $sale = $request->session()->get('sale', new Sale());

        if (!isset($sale->saleLineItems)) {
            $sale->saleLineItems = collect();
        }

        // Check if the sale already contains the item
        $existingSaleLineItem = $sale->saleLineItems->first(function ($saleLineItem) use ($item) {
            return $saleLineItem->item_id == $item->id;
        });

        $totalQuantity = $request->quantity + ($existingSaleLineItem ? $existingSaleLineItem->quantity : 0);

        if ($totalQuantity > $item->amount) {
            return redirect()->back()->withErrors(['quantity' => 'The requested quantity exceeds the available amount.']);
        }

        if ($existingSaleLineItem) {
            $existingSaleLineItem->quantity += $request->quantity;
        } else {
            $saleLineItem = new SaleLineItem([
                'item_id' => $item->id,
                'quantity' => $request->quantity,
            ]);

            $sale->saleLineItems->push($saleLineItem);
        }

        $sale->totalPrice = $sale->getTotalPrice();

        $request->session()->put('sale', $sale);
        return redirect()->back()->with('success', 'Sale line item added successfully.');
    }

    public function removeSaleLineItem(Request $request,$itemId)
    {
        $sale = $request->session()->get('sale');

        $sale->saleLineItems = $sale->saleLineItems->reject(function ($saleLineItem) use ($itemId) {
            return $saleLineItem->item_id == $itemId;
        });

        $sale->totalPrice = $sale->getTotalPrice();

        $request->session()->put('sale', $sale);

        return redirect()->back()->with('success', 'Sale line item removed successfully.');
    }

    public function updateSaleLineItem(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:1'
        ]);

        $item = Item::findOrFail($itemId);

        $sale = $request->session()->get('sale', new Sale());

        $saleLineItemsCollection = collect($sale->saleLineItems);

        $existingSaleLineItem = $saleLineItemsCollection->first(function ($saleLineItem) use ($item) {
            return $saleLineItem->item_id == $item->id;
        });

        $totalQuantity = 0;
        if($request->quantity - $existingSaleLineItem->quantity < 0) {
            $totalQuantity = $request->quantity;
        }
        elseif($request->quantity - $existingSaleLineItem->quantity > 0){
            $totalQuantity = $request->quantity;
        }
        else {
            $totalQuantity = $request->quantity;
        }

        if ($totalQuantity > $item->amount) {
            return redirect()->back()->withErrors(['quantity' => 'The requested quantity exceeds the available amount.']);
        }
        
        foreach ($sale->saleLineItems as $saleLineItem) {
            if ($saleLineItem->item_id == $itemId) {
                $saleLineItem->quantity = $request->quantity;
                break;
            }
        }

        $sale->totalPrice = $sale->getTotalPrice();

        $request->session()->put('sale', $sale);

        return redirect()->back()->with('success', 'Sale line item updated successfully.');
    }

    public function pay(Request $request)
    {
        $sale = $request->session()->get('sale');

        if (!$sale) {
            return redirect()->back()->with('error', 'No valid sale found.');
        }

        $newSale = new Sale();
        $newSale->totalPrice = $sale->totalPrice;
        $newSale->save();

        foreach ($sale->saleLineItems as $saleLineItem) {
            $saleLineItem->sale_id = $newSale->id;
            $saleLineItem->save();
        }

        $payment = new Payment([
            'sale_id' => $newSale->id,
            'total_price' => $newSale->totalPrice
        ]);

        $payment->save();

        foreach ($sale->saleLineItems as $saleLineItem) {
            $item = Item::findOrFail($saleLineItem->item_id);
            $item->amount -= $saleLineItem->quantity;
            $item->save();
        }

        $request->session()->forget('sale');
        return redirect()->route('sales.index')->with('success', 'Payment successful.');
    }
}
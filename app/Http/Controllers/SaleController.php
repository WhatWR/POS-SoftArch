<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\SaleLineItem;
use Illuminate\Http\Request;

class SaleController extends Controller
{
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

        $existingSaleLineItem = $sale->saleLineItems->first(function ($saleLineItem) use ($item) {
            return $saleLineItem->item_id == $item->id;
        });

        $totalQuantity = $request->quantity - $existingSaleLineItem->quantity;

        if ($totalQuantity > 0) {
            if ($request->quantity > $item->amount) {
                return redirect()->back()->withErrors(['quantity' => 'The requested quantity exceeds the available amount.']);
            }
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
        $newSale->member_id = $sale->member ? $sale->member->id : null;
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
        return redirect()->route('dashboard')->with('success', 'Payment successful.');
    }

    public function addMemberToSale(Request $request)
    {
        $request->validate([
            'tel' => 'required|numeric' // Adjust validation rules for telephone number
        ]);
    
        // Find member based on telephone number
        $member = Member::where('tel', $request->tel)->firstOrFail();

        $sale = $request->session()->get('sale', new Sale());
        $sale->member = $member; 
        $sale->totalPrice = $sale->getTotalPrice(); 
        $request->session()->put('sale', $sale);
        return redirect()->route('sales.start')->with('success', 'Member added to sale session successfully.');
    }

    public function removeMember(Request $request)
    {
        $sale = $request->session()->get('sale', new Sale());
        $sale->member = null;
        $sale->totalPrice = $sale->getTotalPrice(); 
        $request->session()->put('sale', $sale);
        return redirect()->route('sales.start')->with('success', 'Member removed from sale successfully.');
    }
}
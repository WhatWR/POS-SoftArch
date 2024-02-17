<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sale;
use App\Models\SaleLineItem;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $items = Item::all();

        return view('sales.index', compact('items'));
    }
    public function start(Request $request)
    {
        $items = Item::all();
        
        // Retrieve the Sale instance from session
        $sale = $request->session()->get('sale', new Sale());
        $saleLineItem = $sale->saleLineItems;
        $sale = [
            "saleLineItems" => $saleLineItem
        ];

        return view('sales.start', compact('items', 'sale'));
    }

    public function addSaleLineItem(Request $request)
    {
        // Validate request data
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|numeric|min:1'
        ]);

        // Retrieve the item based on the item_id
        $item = Item::findOrFail($request->item_id);

        // Create a new SaleLineItem instance
        $saleLineItem = new SaleLineItem([
            'item_id' => $item->id,
            'name' => $item->getName(),
            'quantity' => $request->quantity,
            'price' => $item->price
        ]);

        // Add the sale line item to the sale
        // retrieve the Sale instance from session
        $sale = $request->session()->get('sale');
        $sale->saleLineItems[] = $saleLineItem;

        // Calculate total price
        $totalPrice = $sale->totalPrice + $saleLineItem->getTotalPrice();
        $sale->totalPrice = $totalPrice;

        // Store the updated sale instance back into session
        $request->session()->put('sale', $sale);

        return redirect()->back()->with('success', 'Sale line item added successfully.');
    }

    public function removeSaleLineItem(Request $request,$itemId)
    {
        // Retrieve the Sale instance from session
        // You need to retrieve the Sale instance from session
        $sale = $request->session()->get('sale');

        // Find the sale line item by item id and remove it
        $sale->saleLineItems = array_filter($sale->saleLineItems, function ($saleLineItem) use ($itemId) {
            return $saleLineItem->item_id != $itemId;
        });

        // Calculate total price
        $totalPrice = 0;
        foreach ($sale->saleLineItems as $saleLineItem) {
            $totalPrice += $saleLineItem->getTotalPrice();
        }
        $sale->totalPrice = $totalPrice;

        // Store the updated sale instance back into session
        $request->session()->put('sale', $sale);

        return redirect()->back()->with('success', 'Sale line item removed successfully.');
    }

    public function updateSaleLineItem(Request $request, $itemId)
    {
        // Retrieve the Sale instance from session
        // You need to retrieve the Sale instance from session
        $sale = $request->session()->get('sale');

        // Validate request data
        $request->validate([
            'quantity' => 'required|numeric|min:1'
        ]);

        // Find the sale line item by item id and update its quantity
        foreach ($sale->saleLineItems as $saleLineItem) {
            if ($saleLineItem->item_id == $itemId) {
                $saleLineItem->quantity = $request->quantity;
                break;
            }
        }

        // Calculate total price
        $totalPrice = 0;
        foreach ($sale->saleLineItems as $saleLineItem) {
            $totalPrice += $saleLineItem->getTotalPrice();
        }
        $sale->totalPrice = $totalPrice;

        // Store the updated sale instance back into session
        $request->session()->put('sale', $sale);

        return redirect()->back()->with('success', 'Sale line item updated successfully.');
    }
}
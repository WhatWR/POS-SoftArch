<!-- resources/views/sales/start.blade.php -->

@extends('layouts.app')

@section('content') 
    <h1>Add Sale Line Item</h1>
<form class="mb-4" method="POST" action="{{ route('sales.addItem') }}">
    @csrf
    <div class="form-row align-items-center">
        <div class="col-auto">
            <label for="item_id" class="sr-only">Select Item:</label>
            <select name="item_id" id="item_id" class="form-control">
                @foreach($items as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
            @error('item_id')
            <div>{{ $message }}</div>
            @enderror
        </div>
        <div class="col-auto">
            <label for="quantity" class="sr-only">Quantity:</label>
            <input class="form-control" type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity') }}" required>
            @error('quantity')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div class="col-auto pt-2">
            <button type="submit" class="btn btn-primary">Add Line Item</button>
        </div>
    </div>
</form>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($sale['saleLineItems'] && count($sale['saleLineItems']) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale['saleLineItems'] as $saleLineItem)
                <tr>
                    <td>{{ $saleLineItem['item_id'] }}</td>
                    <td>{{ $saleLineItem['name'] }}</td>
                    <td>{{ $saleLineItem['quantity'] }}</td>
                    <td>${{ $saleLineItem['price'] }}</td>
                    <td>
                        <form action="{{ route('sales.updateItem', $saleLineItem['item_id']) }}" method="post">
                            @csrf
                            @method('PUT')
                            <input type="number" name="quantity" value="{{ $saleLineItem['quantity'] }}" class="form-control" min="1">
                            <button type="submit" class="btn btn-sm btn-primary mt-1">Update</button>
                        </form>
                        <form action="{{ route('sales.removeItem', $saleLineItem['item_id']) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger mt-1">Remove</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{-- <p>Total Price: ${{ $sale['totalPrice'] }}</p> --}}
    @else
        <p>No sale line items added yet.</p>
    @endif
@endsection
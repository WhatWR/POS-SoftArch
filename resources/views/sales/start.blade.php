<!-- resources/views/sales/start.blade.php -->

@extends('auth.layouts')

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
                    <td>{{ $saleLineItem->item->getName() }}</td>
                    <td>{{ $saleLineItem['quantity'] }} X ${{ $saleLineItem->item->getPrice() }}</td>
                    <td>${{ $saleLineItem->getTotalPrice() }}</td>
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

        

        @if ($sale->member)
        <div>
            <p>Current Member: {{ $sale->member->name }} (tel: {{ $sale->member->tel }})</p>
            <form action="{{ route('sales.removeMember') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Remove Member</button>
            </form>
        </div>
        @else
            <form action="{{ route('sales.store_member') }}" method="POST">
                @csrf
                <h3>Add Member to Sale</h3>
                <div class="form-group">
                    <label for="tel">Telephone Number:</label>
                    <input type="tel" id="tel" name="tel" class="form-control" placeholder="Enter Telephone Number">
                    @error('tel')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-sm btn-primary">Add Member to Sale</button>
            </form>
        @endif 
        

        <p>Total Price: ${{ $sale['totalPrice'] }}</p>
        <form action="{{ route('sales.pay') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">Pay Now</button>
        </form>
    @else
        <p>No sale line items added yet.</p>
    @endif
@endsection
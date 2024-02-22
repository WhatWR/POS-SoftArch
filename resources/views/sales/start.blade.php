@extends('auth.layouts')

@section('content') 
<div class="container mt-5">
    <h1 class="mb-4">Add Sale Line Item</h1>
    <form class="mb-4" method="POST" action="{{ route('sales.addItem') }}">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <label for="item_id" class="form-label">Select Item:</label>
                <select name="item_id" id="item_id" class="form-select" aria-label="Select Item">
                    @foreach($items as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
                @error('item_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="quantity" class="form-label">Quantity:</label>
                <input class="form-control" type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity') }}" required>
                @error('quantity')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4 align-self-end">
                <button type="submit" class="btn btn-primary">Add Line Item</button>
            </div>
        </div>
    </form>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($sale['saleLineItems'] && count($sale['saleLineItems']) > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
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
                                <form action="{{ route('sales.updateItem', $saleLineItem['item_id']) }}" method="post" class="mb-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $saleLineItem['quantity'] }}" class="form-control" min="1">
                                    <button type="submit" class="btn btn-sm btn-primary mt-1">Update</button>
                                </form>
                                <form action="{{ route('sales.removeItem', $saleLineItem['item_id']) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($sale->member)
            <div class="mt-4">
                <p>Current Member: {{ $sale->member->name }} (tel: {{ $sale->member->tel }})</p>
                <form action="{{ route('sales.removeMember') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Remove Member</button>
                </form>
            </div>
        @else
            <div class="mt-4">
                <form action="{{ route('sales.store_member') }}" method="POST">
                    @csrf
                    <h3>Add Member to Sale</h3>
                    <div class="mb-3">
                        <label for="tel" class="form-label">Telephone Number:</label>
                        <input type="tel" id="tel" name="tel" class="form-control" placeholder="Enter Telephone Number">
                        @error('tel')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Add Member to Sale</button>
                </form>
            </div>
        @endif 

        <div class="mt-4">
            <p>Total Price: ${{ $sale['totalPrice'] }}</p>
            <form action="{{ route('sales.pay') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Pay Now</button>
            </form>
        </div>
    @else
        <p>No sale line items added yet.</p>
    @endif
</div>
@endsection

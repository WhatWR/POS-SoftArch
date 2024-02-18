<!-- resources/views/items/show.blade.php -->

@extends('auth.layouts')

@section('content')
    <div class="container">
        <h2>Item Details</h2>
        <div>
            <p><strong>Name:</strong> {{ $item->name }}</p>
            <p><strong>Price:</strong> {{ $item->price }}</p>
            <p><strong>Amount:</strong> {{ $item->amount }}</p>
        </div>
        <a href="{{ route('items.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
@endsection

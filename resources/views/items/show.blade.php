@extends('auth.layouts')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2>Item Details</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p><strong>Name:</strong> {{ $item->name }}</p>
                        <p><strong>Price:</strong> {{ $item->price }}</p>
                        <p><strong>Amount:</strong> {{ $item->amount }}</p>
                    </div>
                    <a href="{{ route('items.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

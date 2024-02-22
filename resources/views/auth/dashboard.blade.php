@extends('auth.layouts')

@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h1 class="mb-0">Sales Management</h1>
                </div>
                <div class="card-body">
                    <h3>Membership Discount: 10%</h3>
                    <hr>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success" role="alert">
                            {{ $message }}
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            You are logged in!
                        </div>
                    @endif
                    <div class="mt-3">
                        <a href="{{ route('sales.start') }}" class="btn btn-success">Open Sale</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

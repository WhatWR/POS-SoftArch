@extends('auth.layouts')

@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <h1 class="mb-4">Sales Management</h1>
        <h3>Membership get 10% discount</h3>
        <div class="card">
            <div class="card-header">Dashboard</div>
            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        {{ $message }}
                    </div>
                    <div class="container">
                        <a href="{{ route('sales.start') }}" class="btn btn-primary">Open Sale</a>
                    </div>      
                @else
                    <div class="alert alert-success">
                        You are logged in!
                    </div> 
                    <div class="container">
                        <a href="{{ route('sales.start') }}" class="btn btn-primary">Open Sale</a>
                    </div>      
                @endif                
            </div>
        </div>
    </div>    
</div>
    
@endsection
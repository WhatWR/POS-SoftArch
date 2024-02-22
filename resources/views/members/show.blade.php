@extends('auth.layouts')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2>Member Details</h2>
                </div>
                <div class="card-body">
                    <div>
                        <p><strong>Name:</strong> {{ $member->name }}</p>
                        <p><strong>Telephone:</strong> {{ $member->tel }}</p>
                    </div>
                    <a href="{{ route('members.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

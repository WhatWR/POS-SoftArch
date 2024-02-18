<!-- resources/views/members/create.blade.php -->

@extends('auth.layouts')

@section('content')
    <div class="container">
        <h2>Add New Member</h2>
        <form action="{{ route('members.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>
            <div class="form-group">
                <label for="tel">Tel:</label>
                <input type="tel" class="form-control" id="tel" name="tel">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection

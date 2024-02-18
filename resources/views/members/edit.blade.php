<!-- resources/views/members/edit.blade.php -->

@extends('auth.layouts')

@section('content')
    <div class="container">
        <h2>Edit Member</h2>
        <form action="{{ route('members.update', $member->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $member->name }}">
            </div>
            <div class="form-group">
                <label for="tel">Tel:</label>
                <input type="tel" class="form-control" id="tel" name="tel" value="{{ $member->tel }}">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection

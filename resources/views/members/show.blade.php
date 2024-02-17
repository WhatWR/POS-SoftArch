<!-- resources/views/members/show.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Member Details</h2>
        <div>
            <p><strong>Name:</strong> {{ $member->name }}</p>
            <p><strong>Tel:</strong> {{ $member->tel }}</p>
        </div>
        <a href="{{ route('members.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
@endsection

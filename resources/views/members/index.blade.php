<!-- resources/views/members/index.blade.php -->

@extends('auth.layouts')

@section('content')
    <div class="container">
        <h2>List of Members</h2>
        <a href="{{ route('members.create') }}" class="btn btn-primary mb-3">Add New Member</a>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Tel</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                    <tr>
                        <td>{{ $member->id }}</td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->tel }}</td>
                        <td>
                            <a href="{{ route('members.show', $member->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('members.edit', $member->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('members.destroy', $member->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this member?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

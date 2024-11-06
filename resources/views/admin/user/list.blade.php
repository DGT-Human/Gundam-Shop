@extends('admin.main')
@section('content')
    <table class='table'>
        <thead>
        <tr>
            <th style="width: 50px">ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Date Created</th>
            <th style="width:100px">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $key => $user)
            <tr>
                <th>{{ $user->id }}</th>
                <th>{{ $user->name }}</th>
                <th>{{ $user->email }}</th>
                <th>{{ $user->phone }}</th>
                <th>{{ $user->address }}</th>
                <th>{{ $user->created_at }}</th>
                <th>
                    <a href="edit/{{ $user->id }}" class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                </th>
            </tr>
        @endforeach

        </tbody>
    </table>
    <div class="card-footer clearfix">
        {!! $users->links() !!}
    </div>
@endsection

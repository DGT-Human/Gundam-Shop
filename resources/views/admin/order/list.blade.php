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
            @foreach($orders as $key => $order)
                <tr>
                    <th>{{ $order->id }}</th>
                    <th>{{ $order->name }}</th>
                    <th>{{ $order->email }}</th>
                    <th>{{ $order->phone }}</th>
                    <th>{{ $order->address }}</th>
                    <th>{{ $order->created_at }}</th>
                    <th>
                        <a href="/admin/orders/edit/{{ $order->id }}" class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                    </th>
                </tr>
            @endforeach

        </tbody>
    </table>
    <div class="card-footer clearfix">
        {!! $orders->links() !!}
    </div>
@endsection

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
            <th>Total</th>
            <th>Date Created</th>
            <th>Status</th>
            <th style="width:100px">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
            @foreach($orders as $key => $order)
                <tr>
                    <th>{{ $order->customer_id * $order->customer_phone }}</th>
                    <th>{{ $order->customer_name }}</th>
                    <th>{{ $order->customer_email }}</th>
                    <th>{{ $order->customer_phone }}</th>
                    <th>{{ $order->customer_address }}</th>
                    <th>{{ number_format($order->total, 0, ',', '.') }} VNĐ</th>
                    <th>{{ $order->created_at }}</th>
                    <th>{{ $order->status }}</th>
                    <th>
                        <a href="/admin/orders/edit/{{ $order->customer_id }}" class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                    </th>
                </tr>
            @endforeach

        </tbody>
    </table>
    <div class="card-footer clearfix">
        {!! $orders->links() !!}
    </div>
@endsection

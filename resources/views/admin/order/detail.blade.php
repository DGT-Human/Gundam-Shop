@extends('admin.main')
@section('content')
<div class="invoice p-3 mb-3">
    <!-- title row -->
    <div class="row">
        <div class="col-12">
            <h4>
                <i class="fas fa-globe"></i> AdminLTE, Inc.
                <small class="float-right">Date: {{ date('d/m/Y', strtotime($order['created_at'])) }}</small>
            </h4>
        </div>
        <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            To
            <address>
                <strong>{{ $order['customer_name'] }}</strong><br>
                {{ $order['customer_address'] }}<br>
                Phone: {{ $order['customer_phone'] }}<br>
                Email: {{ $order['customer_email'] }}
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            <b>Invoice #007612</b><br>
            <br>
            <b>Order ID:</b>{{ $order['customer_name'] }}<br>
            <b>Payment Due:</b> {{ date('d/m/Y', strtotime($order['created_at'])) }}<br>
            <b>Account:</b> 968-34567
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
        <div class="col-12 table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Qty</th>
                    <th>Product</th>
                    <th>Price</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach($products as $item)
                @php
                    if ($item['price_sale'] > 0) {
                        $total += $item['quantity'] * $item['price_sale'];
                    }
                    else {
                        $total += $item['quantity'] * $item['price'];
                    }
                @endphp
                <tr>
                    <td>{{$item['quantity']}}</td>
                    <td>{{$item['name']}}</td>
                    @if ($item['price_sale'] > 0)
                        <td>{{number_format($item['price_sale'])}} VND</td>
                    @else
                        <td>{{number_format($item['price'])}} VND</td>
                    @endif
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <!-- /.col -->
        <div class="col-6">
            <p class="lead">Amount Due 2/22/2014</p>
            <p>
                @if($order['status'] == 'pending')
                    <span class="badge bg-warning">{{ $order['status'] }}</span>
                @elseif($order['status'] == 'shipping')
                    <span class="badge bg-info">{{ $order['status'] }}</span>
                @elseif($order['status'] == 'completed')
                    <span class="badge bg-success">{{ $order['status'] }}</span>
                @elseif($order['status'] == 'canceled')
                    <span class="badge bg-danger">{{ $order['status'] }}</span>
                @else
                    <span class="badge bg-secondary">{{ $order['status'] }}</span>
                @endif
            </p>
            <div class="table-responsive">
                <table class="table">
                    <tbody><tr>
                        <th style="width:50%">Subtotal:</th>
                        <td>{{ number_format($total) }} VND</td>
                    </tr>
                    <tr>
                        <th>Shipping:</th>
                        @if ($total > 1000000)
                            <td>Free</td>
                        @else
                            <td>29,000 VND</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Total:</th>
                        @if ($total > 1000000)
                            <td>{{ number_format($total) }} VND</td>
                        @else
                        <td>{{ number_format($total + 29000) }} VND</td>
                        @endif
                    </tr>
                    </tbody></table>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- this row will not appear when printing -->
    <div class="row no-print">
        <div class="col-12">
            @if ($order['status'] == 'pending')
                <form action="{{ route('order.shipping', ['order' => $order['customer_id'], 'date' => $order['created_at']]) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success float-right">
                        <i class="far fa-credit-card"></i> Ship
                    </button>
                </form>

                <form action="{{ route('order.cancel', ['order' => $order['customer_id'], 'date' => $order['created_at']]) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger float-right">
                        <i class="far fa-credit-card"></i> Cancel
                    </button>
                </form>
            @elseif ($order['status'] == 'shipping')
                <form action="{{ route('order.complete', ['order' => $order['customer_id'], 'date' => $order['created_at']]) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success float-right">
                        <i class="far fa-credit-card"></i> Complete
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
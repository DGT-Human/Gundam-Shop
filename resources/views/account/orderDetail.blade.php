@extends('main')
@extends('admin.head')

@section('content')
    <div class="wrapper wrapper--w790 mt-5">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="invoice p-3 mb-3">
                            <!-- info row -->
                            <div class="row invoice-info">

                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    To
                                    <address>
                                        @foreach($customer as $c)
                                            <strong>{{ $c->name }}</strong><br>
                                            Address: {{ $c->address }}<br>
                                            Phone: {{ $c->phone }}<br>
                                            Email: {{ $c->email }}
                                        @endforeach
                                    </address>
                                </div>
                                <!-- /.col -->
{{--                                <div class="col-sm-4 invoice-col">--}}
{{--                                    <b></b><br>--}}
{{--                                    <br>--}}
{{--                                    <b>Order ID:</b> 4F3S8J<br>--}}
{{--                                    <b>Payment Due:</b> 2/22/2014<br>--}}
{{--                                    <b>Account:</b> 968-34567--}}
{{--                                </div>--}}
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <!-- Table row -->
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Serial #</th>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $total = 0;
                                            $status = '';
                                        @endphp
                                        @if (count($orders) == 9)
                                            @php
                                                $products = App\Models\Product::all()->keyBy('id');
                                                $product = $products[$orders['product_id']] ?? null;
                                                $total = $total + $orders['total_price'];
                                                $date = date('d/m/Y', strtotime($orders['created_at']));
                                                $status = $orders['status'];
                                            @endphp
                                            <tr>
                                                <td>{{ $product->id ?? 'N/A' }}</td>
                                                <td>{{ $product->name ?? 'Sản phẩm không tồn tại' }}</td>
                                                <td>{{ $orders['quantity'] }}</td>
                                                @if ($product->price_sale > 0)
                                                    <td>{{ number_format($product->price_sale ?? 0) }} VND</td>
                                                @else
                                                    <td>{{ number_format($product->price ?? 0) }} VND</td>
                                                @endif
                                            </tr>
                                        @else
                                        @foreach($orders as $order)
                                            @php
                                                $products = App\Models\Product::all()->keyBy('id');
                                                $product = $products[$order['product_id']] ?? null;
                                                $total = $total + $order['total_price'];
                                                $date = date('d/m/Y', strtotime($order['created_at']));
                                                $status = $order['status'];
                                            @endphp

                                            <tr>
                                                <td>{{ $product->id ?? 'N/A' }}</td>
                                                <td>{{ $product->name ?? 'Sản phẩm không tồn tại' }}</td>
                                                <td>{{ $order['quantity'] }}</td>
                                                @if ($product->price_sale > 0)
                                                    <td>{{ number_format($product->price_sale ?? 0) }} VND</td>
                                                @else
                                                    <td>{{ number_format($product->price ?? 0) }} VND</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <div class="row">
                                <!-- accepted payments column -->
{{--                                <div class="col-6">--}}
{{--                                    <p class="lead">Payment Methods:</p>--}}
{{--                                    <img src="../../dist/img/credit/visa.png" alt="Visa">--}}
{{--                                    <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">--}}
{{--                                    <img src="../../dist/img/credit/american-express.png" alt="American Express">--}}
{{--                                    <img src="../../dist/img/credit/paypal2.png" alt="Paypal">--}}

{{--                                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">--}}
{{--                                        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem--}}
{{--                                        plugg--}}
{{--                                        dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.--}}
{{--                                    </p>--}}
{{--                                </div>--}}
                                <!-- /.col -->
                                <div class="col-6">
                                    <p class="lead">Amount Due {{ $date }}</p>
                                    <p>
                                        @if($status == 'pending')
                                            <span class="badge bg-warning">{{ $status }}</span>
                                        @elseif($status == 'shipping')
                                            <span class="badge bg-info">{{ $status }}</span>
                                        @elseif($status == 'completed')
                                            <span class="badge bg-success">{{ $status }}</span>
                                        @elseif($status == 'canceled')
                                            <span class="badge bg-danger">{{ $status }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $status }}</span>
                                        @endif
                                    </p>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                            <tr>
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

                        </div>
                    </div>
                </div>
            </div><!-- /.card-body -->
        </div>
        <!-- /.card -->
@endsection
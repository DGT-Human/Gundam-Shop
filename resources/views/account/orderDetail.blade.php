@extends('admin.head')
<div class="row">
    <div class="col-md-3">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" src="/template/images/image.jpg" alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- About Me Box -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">About Me</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i>Email</strong>

                <p class="text-muted">
                    {{ Auth::user()->email }}
                </p>

                <hr>

                <strong><i class="fas fa-map-marker-alt mr-1"></i> Phone</strong>

                <p class="text-muted">{{ Auth::user()->phone }}</p>

                <hr>

                <strong><i class="fas fa-pencil-alt mr-1"></i>Address</strong>

                <p class="text-muted">{{ Auth::user()->address }}</p>

                <hr>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link" href="/" data-toggle="tab">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url('users/account/'. Auth::user()->id) }}" data-toggle="tab">Information</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('users/account/settings/'. Auth::user()->id) }}" data-toggle="tab">Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" data-toggle="tab">Logout</a></li>
                </ul>
            </div><!-- /.card-header -->

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
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
                                        <strong>{{ Auth::user()->name }}</strong><br>
                                        Address: {{ Auth::user()->address }}<br>
                                        Phone: {{ Auth::user()->phone }}<br>
                                        Email: {{ Auth::user()->email }}
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
                                            <th>Qty</th>
                                            <th>Product</th>
                                            <th>Serial #</th>
                                            <th>Description</th>
                                            <th>Subtotal</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if (count($orders) == 8)
                                            @php
                                                $products = App\Models\Product::all()->keyBy('id');
                                                $product = $products[$orders['product_id']] ?? null;
                                            @endphp
                                            <tr>
                                                <td>{{ $orders['quantity'] }}</td>
                                                <td>{{ $product->name ?? 'Sản phẩm không tồn tại' }}</td>
                                                <td>{{ $product->id ?? 'N/A' }}</td>
                                                <td>{{ $product->description ?? 'Không có mô tả' }}</td>
                                                <td>{{ number_format($product->price ?? 0) }} VND</td>
                                            </tr>
                                        @else
                                        @foreach($orders as $order)
                                            @php
                                                $products = App\Models\Product::all()->keyBy('id');
                                                $product = $products[$order->product_id] ?? null;
                                            @endphp

                                            <tr>
                                                <td>{{ $order->quantity }}</td>
                                                <td>{{ $product->name ?? 'Sản phẩm không tồn tại' }}</td>
                                                <td>{{ $product->id ?? 'N/A' }}</td>
                                                <td>{{ $product->description ?? 'Không có mô tả' }}</td>
                                                <td>{{ number_format($product->price ?? 0) }} VND</td>
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
                                    <p class="lead">Amount Due 2/22/2014</p>

                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                            <tr>
                                                <th style="width:50%">Subtotal:</th>
                                                <td>{{ number_format($orders['total_price'] ?? 0)  }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tax (9.3%)</th>
                                                <td>$10.34</td>
                                            </tr>
                                            <tr>
                                                <th>Shipping:</th>
                                                <td>$5.80</td>
                                            </tr>
                                            <tr>
                                                <th>Total:</th>
                                                <td>$265.24</td>
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
                                    <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                                    <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                                        Payment
                                    </button>
                                    <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                                        <i class="fas fa-download"></i> Generate PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

    <!-- /.col -->
</div>
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
                        <br>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">My Orders</h3>

                                <div class="card-tools">
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($groups as $order)
                                        <tr>
                                            <td>{{$order['id']}}</td>
                                            <td>{{$order['date']}}</td>
                                            <td>{{$order['sum_quantity']}}</td>
                                            <td>{{ number_format($order['total_price'], 0, ',', '.') }} VND</td>
                                            <td>
                                                @switch($order['status'])
                                                    @case('pending')
                                                        <span class="badge badge-warning">Chờ xử lý</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge badge-success">Hoàn thành</span>
                                                        @break
                                                    @case('canceled')
                                                        <span class="badge badge-danger">Đã hủy</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-secondary">Không xác định</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <button class="btn btn-primary"><a style="color: white" href=" {{ url('users/account/'. $order['id']. '/order')}}">Xem</a></button>
                                                @if ($order['status'] == 'pending')
                                                    <button class="btn btn-danger">Hủy</button>
                                                @endif
                                            </td>
                                        </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

    <!-- /.col -->
</div>
@extends('main')
@extends('admin.head')
@section('content')
    <div class="mt-5">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <br>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">My Orders</h3>

{{--                                <div class="card-tools">--}}
{{--                                    <div class="input-group input-group-sm" style="width: 150px;">--}}
{{--                                        <input type="text" name="table_search" class="form-control float-right" placeholder="Search">--}}

{{--                                        <div class="input-group-append">--}}
{{--                                            <button type="submit" class="btn btn-default">--}}
{{--                                                <i class="fas fa-search"></i>--}}
{{--                                            </button>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th>ID</th>
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
                                                        <span class="badge badge-warning">pending</span>
                                                        @break
                                                    @case('shipping')
                                                        <span class="badge badge-info">Shipping</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge badge-success">Completed</span>
                                                        @break
                                                    @case('canceled')
                                                        <span class="badge badge-danger">Canceled</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-secondary">Không xác định</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <a href="{{ url('users/account/'. $order['id']. '/order/'. $order['date']) }}" class="btn btn-primary">
                                                    View Detail
                                                </a>
                                                @if ($order['status'] == 'pending')
                                                    <form action="{{ route('account.order.cancel', ['id' => $order['id'], 'date' => $order['date']]) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">
                                                            Cancel
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div>
        @if (empty($groups))
            <div class="text-center p-t-40 mt-5">
                <h2>You have no orders.</h2>
            </div>
        @else
            <div class="d-flex justify-content-center mt-5">
                {{ $groups->links('pagination') }} <!-- Pagination links -->
            </div>
        @endif
    </div>
    <div class="mt-5">

    </div>
@endsection
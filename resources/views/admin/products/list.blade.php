@extends('admin.main')
@section('content')
    <table class='table'>
        <thead>
        <tr>
            <th style="width: 50px">ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Price_sale</th>
            <th>Quantity</th>
            <th>Active</th>
            <th>Update</th>
            <th style="width:100px">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
            @foreach($products as $key => $product)
                <tr>
                    <th>{{ $product->id }}</th>
                    <th>{{ $product->name }}</th>
                    <th>{{ $product->menu->name ?? 'Unknown'}}</th>
                    <th>{{ $product->price }}</th>
                    <th>{{ $product->price_sale }}</th>
                    <th>{{ $product->quantity }}</th>
                    <th>{!!\App\Helpers\helper::active($product->active) !!}</th>
                    <th>{{ $product->updated_at }}</th>
                    <th>
                        <a href="/admin/products/edit/{{ $product->id }}" class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="#" onclick="removeRow({{ $product->id }}, '/admin/products/destroy')" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                    </th>
                </tr>
            @endforeach

        </tbody>
    </table>
    <div class="card-footer clearfix">
        {!! $products->links() !!}
    </div>
@endsection

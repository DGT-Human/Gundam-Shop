@extends('main')
@section('content')

@if($wishlists->isEmpty())
    <div class="text-center p-t-40 mt-5">
        <h2>You have no items in your wishlist.</h2>
    </div>
@else
<table class="table">
    <thead>
    <tr>
        <th>Product Name</th>
        <th>Price</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($wishlists as $wishlist)
    <tr>
        <td><a href="/san-pham/{{$wishlist->product->id}}-{{ Str::slug($wishlist->product->name, '-')}}.html">{{ $wishlist->product->name }}</a></td>
        <td>{{ number_format($wishlist->product->price_sale != 0 ? $wishlist->product->price_sale : $wishlist->product->price, 0, ',', '.') }} VND</td>
        <td>
            <form action="{{ route('wishlist.remove', $wishlist->product) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Remove</button>
            </form>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>

@endif
<div class = "m-t-300">

</div>
@endsection


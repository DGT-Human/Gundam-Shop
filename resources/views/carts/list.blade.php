@extends('main')

@section ('content')

    <form class="bg0 p-t-75 p-b-85" method="post">
        <div class="container">
            <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-40 p-lr-0-lg">
                <a href="/" class="stext-109 cl8 hov-cl1 trans-04">
                    Home
                    <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                </a>

                <span class="stext-109 cl4">
				Shoping Cart
			</span>
            </div>
            @if(count($products) != 0)
                @php $total = 0;
                    $carts = session()->get('carts');
                @endphp
            <div class="row">
                <div class="col-lg-10 col-xl-7 m-lr-auto m-b-50 p-t-40">
                    <div class="m-l-25 m-r--38 m-lr-0-xl">
                        <div class="wrap-table-shopping-cart">
                            <table class="table-shopping-cart">
                                <tbody><tr class="table_head">
                                    <th class="column-1">Product</th>
                                    <th class="column-2"></th>
                                    <th class="column-3">Price</th>
                                    <th class="column-4">Quantity</th>
                                    <th class="column-5">Total</th>
                                    <th class="p-lr-10">&nbsp;</th>
                                </tr>
                                @foreach($products as $key => $product)
                                    @php
                                            $price = $product->price_sale != 0 ? $product->price_sale : $product->price;
                                            $priceEnd = $price * $carts[$product->id];
                                            $total += $priceEnd;
                                    @endphp
                                <tr class="table_row">
                                    <td class="column-1">
                                        <a href="/san-pham/{{$product->id}}-{{Str::slug($product->name, '-')}}.html">
                                            <div class="how-itemcart1">
                                                <img src="{{ $product->thumb }}" alt="IMG">
                                            </div>
                                        </a>
                                    </td>
                                    <td class="column-2">{{ $product->name }}</td>
                                    <td class="column-3" >{{ number_format($price, '0', '', '.') }}đ</td>
                                    <td class="column-4" style="padding-left: 60px;">
                                        <div class="wrap-num-product flex-w m-l-auto m-r-0">
                                            <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m" onclick="changeQuantity({{ $product->id }}, -1)">
                                                <i class="fs-16 zmdi zmdi-minus"></i>
                                            </div>

                                            <input class="mtext-104 cl3 txt-center num-product" type="number" name="num-product[{{$product->id}}]" value="{{ $carts[$product->id] ?? 1 }}" min="1" max="{{ $product->quantity }}" id="num-product-{{$product->id}}">

                                            <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m" onclick="changeQuantity({{ $product->id }}, 1)">
                                                <i class="fs-16 zmdi zmdi-plus"></i>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="column-5" style="padding-left: 60px;">{{ number_format($priceEnd, '0', '', '.') }}đ</td>
                                    <td class="p-lr-10" style="padding-left: 4px;">
                                        <a href="/remove-cart/{{ $product->id }}" class="btn btn-danger">Xóa</a>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>

                        <div class="flex-w flex-sb-m bor15 p-t-18 p-b-15 p-lr-40 p-lr-15-sm">
                            <div class="flex-w flex-m m-r-20 m-tb-5">
                                <input class="stext-104 cl2 plh4 size-117 bor13 p-lr-20 m-r-10 m-tb-5" type="text" name="coupon" placeholder="Coupon Code">

                                <div class="flex-c-m stext-101 cl2 size-118 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-5">
                                    Apply coupon
                                </div>
                            </div>

                            <input type="submit" value="Update Cart" formaction="/update-cart" class="flex-c-m stext-101 cl2 size-119 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-10">
                                @csrf
                        </div>
                    </div>
                </div>

                <div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
                    <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
                        <h4 class="mtext-109 cl2 p-b-30">
                            Cart Totals
                        </h4>
                        <div class="flex-w flex-t p-t-27 p-b-33">
                            <div class="size-208">
								<span class="mtext-101 cl2">
									Total:
								</span>
                            </div>

                            <div class="size-209 p-t-1">
								<span class="mtext-110 cl2">
									{{ number_format($total, '0', '', '.') }}đ
								</span>
                            </div>
                        </div>
                        <div class="flex-w flex-t bor12 p-t-15 p-b-30">
                            <div class="size-208 w-full-ssm">
								<span class="stext-110 cl2">
									Shipping:
								</span>
                            </div>
                                <p class="stext-111 cl6 p-t-2">
                                    Hãy nhập thông tin giao hàng để xem phí vận chuyển. <br>
                                    Lưu ý: Chỉ thanh toán khi nhận hàng (Phí ship là 29.000đ và Free shipping với đơn trên 1.000.000đ).
                                </p>

                                <div class="p-t-15">
									<span class="stext-112 cl8">
										Thông tin giao hàng
									</span>
                                    @if (Auth::check())
                                        <div class="bor8 bg0 m-b-22">
                                            <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="name" placeholder="Tên của bạn" value = "{{ Auth::user()->name }}" readonly>
                                        </div>

                                        <div class="bor8 bg0 m-b-22">
                                            <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="phone" placeholder="Số điện thoại" value = "{{ Auth::user()->phone }}" readonly>
                                        </div>

                                        <div class="bor8 bg0 m-b-22">
                                            <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="address" placeholder="Địa chỉ giao hàng" value = "{{ Auth::user()->address }}, {{ Auth::user()->city }}" readonly>
                                        </div>

                                        <div class="bor8 bg0 m-b-22">
                                            <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="email" placeholder="Email" value = "{{ Auth::user()->email }}" readonly>
                                        </div>

                                        <div class="bor8 bg0 m-b-22">
                                            <textarea class="stext-111 cl8 plh3 size-130 p-lr-15" name="content" id="" cols="30" rows="10"></textarea>
                                        </div>
                                    @else
                                    <div class="bor8 bg0 m-b-22">
                                        <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="name" placeholder="Tên của bạn" >
                                    </div>

                                    <div class="bor8 bg0 m-b-22">
                                        <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="phone" placeholder="Số điện thoại" >
                                    </div>

                                    <div class="bor8 bg0 m-b-22">
                                        <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="address" placeholder="Địa chỉ giao hàng" >
                                    </div>

                                    <div class="bor8 bg0 m-b-22">
                                        <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="email" placeholder="Email" >
                                    </div>

                                    <div class="bor8 bg0 m-b-22">
                                        <textarea class="stext-111 cl8 plh3 size-130 p-lr-15" name="content" id="" cols="30" rows="10"></textarea>
                                    </div>
                                    @endif
                                </div>
                        </div>
                        <button class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer" type="submit">
                            Đặt hàng
                        </button>
                        @csrf
                    </div>
                </div>
            </div>
            @else
                <div class="text-center p-t-40 mt-5">
                    <h2>Không có sản phẩm nào trong giỏ hàng</h2>
                </div>
            @endif
        </div>
    </form>
    <div class = "m-t-300">

    </div>
@endsection
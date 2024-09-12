<div class="wrap-header-cart js-panel-cart">
    <div class="s-full js-hide-cart"></div>

    <div class="header-cart flex-col-l p-l-65 p-r-25">
        <div class="header-cart-title flex-w flex-sb-m p-b-8">
				<span class="mtext-103 cl2">
					Your Cart
				</span>

            <div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
                <i class="zmdi zmdi-close"></i>
            </div>
        </div>
        <div class="header-cart-content flex-w js-pscroll">
            <ul class="header-cart-wrapitem w-full">
                @php
                     $total = 0;
                @endphp
                @foreach($productCarts as $productCart)
                    @php
                        $carts = \Illuminate\Support\Facades\Session::get('carts', []);
                        $quantity = isset($carts[$productCart->id]) ? $carts[$productCart->id] : 0;
                    @endphp
                    @if($quantity > 0)
                        <li class="header-cart-item flex-w flex-t m-b-12">
                            <a href="/remove-cart/{{ $productCart->id }}">
                            <div class="header-cart-item-img">
                                <img src="{{ $productCart->thumb }}" alt="IMG">
                            </div>
                            </a>
                            <div class="header-cart-item-txt p-t-8">
                                <a href="/san-pham/{{$productCart->id}}-{{ Str::slug($productCart->name, '-')}}.html" class="header-cart-item-name m-b-18 hov-cl1 trans-04">
                                    {{ $productCart->name }}
                                </a>

                                <span class="header-cart-item-info">
                                    {{ $quantity }} x {{ number_format($productCart->price_sale != 0 ? $productCart->price_sale : $productCart->price, 0, '', '.') }}đ
                                    @php
                                    $total = $total + ($quantity * ($productCart->price_sale != 0 ? $productCart->price_sale : $productCart->price));
                                    @endphp
                                </span>
                            </div>
                        </li>
                    @endif
                @endforeach
            </ul>
            <div class="w-full">
                <div class="header-cart-total w-full p-tb-40">
                    Total: {{ number_format($total, 0, '', '.') }}đ
                </div>

                <div class="header-cart-buttons flex-w w-full">
                    <a href="/carts"
                       class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">
                        View Cart
                    </a>

                    <a href="/carts"
                       class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
                        Check Out
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

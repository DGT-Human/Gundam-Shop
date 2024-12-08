@extends('main')


@section('content')

    <div class="bg0 m-t-23 p-b-140">
        <div class="container">
            <div class="flex-w flex-sb-m p-b-52">
                <div class="flex-w flex-l-m filter-tope-group m-tb-10">

                    <h1><b>{{ $title }}</b></h1>

                </div>
                <div class="flex-w flex-c-m m-tb-10">
                    <div class="flex-c-m stext-106 cl6 size-104 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 js-show-filter">
                        <i class="icon-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-filter-list"></i>
                        <i class="icon-close-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
                        Filter
                    </div>

                    <div class="flex-c-m stext-106 cl6 size-105 bor4 pointer hov-btn3 trans-04 m-tb-4 js-show-search">
                        <i class="icon-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-search"></i>
                        <i class="icon-close-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
                        Search
                    </div>
                </div>

                <!-- Search product -->
                <div class="dis-none panel-search w-full p-t-10 p-b-15">
                    <form action="/search" method="get">
                    <div class="bor8 dis-flex p-l-15">
                        <button class="size-113 flex-c-m fs-16 cl2 hov-cl1 trans-04">
                            <i class="zmdi zmdi-search"></i>
                        </button>

                        <input class="mtext-107 cl2 size-114 plh2 p-r-15" type="text" name="search" placeholder="Search" value="{{ request('search') }}">
                    </div>
                    </form>
                </div>
                <!-- Filter -->
                <div class="dis-none panel-filter w-full p-t-10">
                    <div class="wrap-filter flex-w bg6 w-full p-lr-40 p-t-27 p-lr-15-sm">
                        <div class="filter-col1 p-r-15 p-b-27">
                            <div class="mtext-102 cl2 p-b-15">
                                Sort By
                            </div>

                            <ul>
                                <li class="p-b-6">
                                    <a href="{{ request()->url() }}" class="filter-link stext-106 trans-04">
                                        Default
                                    </a>
                                </li>

                                <li class="p-b-6">
                                    <a href="{{ request()->fullUrlWithQuery(['price' => 'asc']) }}" class="filter-link stext-106 trans-04">
                                        Price: Low to High
                                    </a>
                                </li>

                                <li class="p-b-6">
                                    <a href="{{ request()->fullUrlWithQuery(['price' => 'desc']) }}" class="filter-link stext-106 trans-04">
                                        Price: High to Low
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <form action="/search-money" method="GET">
                            <input type="hidden" name="category_id" value="{{ url()->current() }}">
                        <div class="filter-col2 p-r-15 p-b-27">
                        <div class="mtext-102 cl2 p-b-15">
                            <label for="priceRange">Price range:</label>
                            <input type="range" id="priceRange" name="min_price" min="0" max="1000000" step="10000" value="500000"
                                   oninput="document.getElementById('minPriceOutput').innerHTML = this.value.toLocaleString('vi-VN')">
                            <span id="minPriceOutput">500,000</span> VND

                            <input type="range" id="priceRangeMax" name="max_price" min="1000000" max="10000000" step="10000" value="10000000"
                                   oninput="document.getElementById('maxPriceOutput').innerHTML = this.value.toLocaleString('vi-VN')">
                            <span id="maxPriceOutput">10,000,000</span> VND
                        </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                        </form>
{{--                        <div class="filter-col2 p-r-15 p-b-27">--}}
{{--                            <div class="mtext-102 cl2 p-b-15">--}}
{{--                                Price--}}
{{--                            </div>--}}
{{--                            <input type="range" id="priceRange" name="priceRange" min="0" max="10000000" step="100000" value="5000000" oninput="document.getElementById('priceOutput').innerHTML = this.value.toLocaleString('vi-VN')">--}}
{{--                            <span id="priceOutput">5,000,000</span> VND--}}
{{--                        </div>--}}

                    </div>
                    </div>
                </div>

                @include('product.list')
            <!-- Load more -->
            @if (!request('search') && !request('min_price') && !request('max_price'))
                <div class="flex-c-m flex-w w-full p-t-45">
                    {{ $products->appends(request()->query())->links('pagination') }}
                </div>
            @endif
        </div>
    </div>

@endsection
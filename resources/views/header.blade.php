<!-- Header -->
<header class="header-v2">
    <!-- Header desktop -->
    <div class="container-menu-desktop trans-03">

        <div class="wrap-menu-desktop">
            <nav class="limiter-menu-desktop p-l-45">

                <!-- Logo desktop -->
                <a href="/" class="logo">
                    <img src="/template/images/icons/logo-02.png" alt="IMG-LOGO">
                </a>

                <!-- Menu desktop -->
                <div class="menu-desktop">
                    <ul class="main-menu">
                        <li><a href="/">Home</a>  </li>
                        {!! \App\Helpers\Helper::menu($menus) !!}
                    </ul>
                </div>

                <!-- Icon header -->
                <div class="wrap-icon-header flex-w flex-r-m h-full">
                    <div class="flex-c-m h-full p-r-24">
                        <div class="icon-header-item cl2 hov-cl1 trans-04 p-lr-11 js-show-modal-search">
                            <i class="zmdi zmdi-search"></i>
                        </div>
                    </div>

                    <div class="flex-c-m h-full p-l-18 p-r-25 bor5">
                        <div class="icon-header-item cl2 hov-cl1 trans-04 p-lr-11 icon-header-noti js-show-cart" data-notify="{{ count(\Illuminate\Support\Facades\Session::get('carts') ?? []) }}">
                            <i class="zmdi zmdi-shopping-cart"></i>
                        </div>
                    </div>

                    <div class="flex-c-m h-full p-lr-19">
                        <div class="icon-header-item cl2 hov-cl1 trans-04 p-lr-11 js-show-sidebar">
                            <i class="zmdi zmdi-menu"></i>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <!-- Header Mobile -->
    <div class="wrap-header-mobile">
        <!-- Logo moblie -->
        <div class="logo-mobile">
            <a href="/"><img src="/template/images/icons/logo-02.png" alt="IMG-LOGO"></a>
        </div>

        <!-- Icon header -->
        <div class="wrap-icon-header flex-w flex-r-m h-full m-r-15">
            <div class="flex-c-m h-full p-r-5">
                <div class="icon-header-item cl2 hov-cl1 trans-04 p-lr-11 icon-header-noti js-show-cart"
                     data-notify="{{ count(\Illuminate\Support\Facades\Session::get('carts') ?? []) }}">
                    <i class="zmdi zmdi-shopping-cart"></i>
                </div>
            </div>
        </div>

        <!-- Button show menu -->
        <div class="btn-show-menu-mobile hamburger hamburger--squeeze">
				<span class="hamburger-box">
					<span class="hamburger-inner"></span>
				</span>
        </div>
    </div>


    <!-- Menu Mobile -->
    <div class="menu-mobile">
        <ul class="main-menu-m">
            <li>
                <a href="/">Home</a>
            </li>

                {!! \App\Helpers\Helper::menuMobile($menus) !!}

            <li>
                <a href="{{ url('users/wishlist') }}">My Wishlist</a>
            </li>
            @if(!Auth::check())
            <li>
                <a href="{{ url('users/login') }}">Login</a>
            </li>
            @else
                <li>
                    <a href="{{ url('users/account/settings/'. Auth::user()->id) }}">My Account: <b>{{ Auth::user()->name }}</b></a>
                    <span> | </span>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
                <li>
                    @if(Auth::check())
                        <a href="{{ url('users/account/' . Auth::user()->id)}}">
                            Track Order
                        </a>
                    @else
                        <p>Login to track your order</p>
                    @endif
                </li>
            @endif
        </ul>
    </div>

    <!-- Modal Search -->
    <div class="modal-search-header flex-c-m trans-04 js-hide-modal-search">
        <button class="flex-c-m btn-hide-modal-search trans-04">
            <i class="zmdi zmdi-close"></i>
        </button>

        <form class="container-search-header" action="/search" method="get">
            <div class="wrap-search-header">
                <input class="plh0" type="text" name="search" placeholder="Search..." value="{{ request('search') }}">

                <button class="flex-c-m trans-04">
                    <i class="zmdi zmdi-search"></i>
                </button>
            </div>
        </form>
    </div>
</header>

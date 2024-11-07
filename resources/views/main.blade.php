<!DOCTYPE html>
<html lang="en">
@include('head')
<body>   <!--class="animsition">-->
@include('header')
<!-- Sidebar -->
<aside class="wrap-sidebar js-sidebar">
    <div class="s-full js-hide-sidebar"></div>

    <div class="sidebar flex-col-l p-t-22 p-b-25">
        <div class="flex-r w-full p-b-30 p-r-27">
            <div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-sidebar">
                <i class="zmdi zmdi-close"></i>
            </div>
        </div>

        <div class="sidebar-content flex-w w-full p-lr-65 js-pscroll">
            <ul class="sidebar-link w-full">
                <li class="p-b-13">
                    <a href="/" class="stext-102 cl2 hov-cl1 trans-04">
                        Home
                    </a>
                </li>

                <li class="p-b-13">
                    <a href="{{ url('users/wishlist') }}" class="stext-102 cl2 hov-cl1 trans-04">
                        My Wishlist
                    </a>
                </li>

                <li class="p-b-13">
                    @if(!Auth::check())
                        <a href="{{ url('users/login') }}" class="stext-102 cl2 hov-cl1 trans-04">
                            Login / Account
                        </a>
                    @else
                        <a href="{{ url('users/account/settings/'. Auth::user()->id) }}" class="stext-102 cl2 hov-cl1 trans-04">
                            My Account: <b>{{ Auth::user()->name }}</b>
                        </a>
                        <span> | </span>
                        <a href="{{ route('logout') }}" class="stext-102 cl2 hov-cl1 trans-04"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endif
                </li>


                <li class="p-b-13">
                    @if(Auth::check())
                        <a href="{{ url('users/account/' . Auth::user()->id)}}" class="stext-102 cl2 hov-cl1 trans-04">
                            Track Order
                        </a>
                    @else
                        <p class="stext-102 cl2 hov-cl1 trans-04">Login to track your order</p>
                    @endif
                </li>
            </ul>

            <div class="sidebar-gallery w-full p-tb-30">
					<span class="mtext-101 cl5">
						@ DGT Gundam Store
					</span>

            </div>

            <div class="sidebar-gallery w-full">
					<span class="mtext-101 cl5">
						About Us
					</span>

                <p class="stext-108 cl6 p-t-27">
                    Gundam for Everyone
                </p>
            </div>
        </div>
    </div>
</aside>


<!-- Cart -->
@include('carts.carts')
@include('admin.alert')
@yield('content')


<!-- Slider -->



<!-- Footer -->
@include('footer')
</body>
</html>

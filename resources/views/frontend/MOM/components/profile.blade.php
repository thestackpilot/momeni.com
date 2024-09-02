@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp 

    @guest()
    <a href="{{route('auth.login')}}">
        <div class="headericons quickProfile-opener">
            <img src="/MOM/images/myaccount-icon-mom.svg">
        </div>
    </a>
    @endguest()
    @auth()
    <div class="headericons quickProfile-opener">
        <img src="/MOM/images/myaccount-icon-mom.svg">
    </div>
    @endauth
    @auth()
    <div class="quick-profile col-sm-12 position-fixed checkout-balance col-12 d-none">
        <i class="icon-cross position-absolute closeProfile"> </i>
        <div class="d-flex flex-column">
            <div class="flex-row justify-content-center upperArea text-center">
                <a href="{{route('dashboard.myaccount')}}" class="profile-img">
                    <h1 class="naming-initials"> {{Auth::user()->firstname ? strtoupper(Auth::user()->firstname)[0] : ''}}{{Auth::user()->lastname ? strtoupper(Auth::user()->lastname)[0] : ''}} </h1>
                </a>
            </div>
            <div class="text-center inner-user-settings">
                <div class="user-information p-0 border-0">
                    <h6 class="user-name">{{Auth::user()->firstname.' '.Auth::user()->lastname}}</h6>
                    <h6 class="user-email">{{Auth::user()->email}}</h6>
                </div>
                <div class="user-settings-block1 p-0">
                    <a href="{{route('dashboard')}}" class="user-settings">
                        <div>Dashboard</div>
                        <i class="icon-user"></i>
                    </a>
                </div>
                @if ( strcmp( ConstantsController::USER_ROLES['admin'], Auth::user()->role ) !== 0 )
                {{-- <div class="user-settings-block1 p-0">
                    <a href="{{route('dashboard.myaccount')}}#custom-cost" class="user-settings">
                        <div>Custom Cost</div>
                        <i class="icon-cog"></i>
                    </a>
                </div> --}}
                <div class="user-settings-block1 p-0">
                    <a href="{{route('dashboard.placeorder')}}" class="user-settings">
                        <div>Place Order</div>
                        <i class="icon-cart-plus"></i>
                    </a>
                </div>
                <div class="user-settings-block1 p-0">
                    <a href="{{route('dashboard.vieworder')}}" class="user-settings">
                        <div>View Orders</div>
                        <i class="icon-file-check"></i>
                    </a>
                </div>
                <div class="user-settings-block1 p-0">
                    <a href="{{route('dashboard.invoice')}}" class="user-settings">
                        <div>View Invoices</div>
                        <i class="icon-credit-card"></i>
                    </a>
                </div>
                {{-- <div class="user-settings-block1 p-0">
                    <a href="{{route('dashboard.viewreturn')}}" class="user-settings">
                        <div>View Returns</div>
                        <i class="icon-reply"></i>
                    </a>
                </div> --}}
                @endif
                <div class="user-settings-block1 p-0">
                    <a class="user-settings" href="{{route('auth.logout')}}">
                        <div>Logout</div>
                        <i class="fa fa-sign-out"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endauth

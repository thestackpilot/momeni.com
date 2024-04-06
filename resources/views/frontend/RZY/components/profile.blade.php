@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp 

    <ul class="navbar-nav right-nav d-flex flex-row justify-content-end align-items-center">
        @php
            if ( isset($menus -> main_header_right) )
            {
                echo CommonController::print_menu
                (
                    $menus -> main_header_right -> metas,                                     //meta
                    '',                                                                 //prefix_li
                    'class="nav-item position-static catalog-nav-menu"',                                                 //li
                    '',                                                                 //postfix_li
                    'dropdown-toggle',                                                  //anchor_parent
                    '',                                                                 //prefix_ul
                    'class="dropdown-submenuu-home nav-item" style="display: none"',    //ul
                    '',                                                                 //postfix_ul
                    2                                                                   // 0 - Normal, 1 - smallcase, 2 - capitalcase, 3 - ucfirst, 3 - ucword                                                                
                ); 
            }
        @endphp
        <li class="nav-item">
            <div  class="popup-search-header" id="search_text_container">
                <input type="text" name="searchText" class="form-control type-here-field" id="formGroupExampleInput" placeholder="Type here" value="">
                <a href="javascript:void(0);" class="search_text_button inner-search-btn" role="button" aria-pressed="true">
                    <img src="{{asset('/RZY/images/icon-search.svg')}}" >
                </a>
            </div>
            <a href="javascript:void(0);" id="search-icon-mobile-version">
                <img src="{{asset('/RZY/images/icon-search.svg')}}" class="icon-img icon-search icon-white"/>
                <img src="{{asset('/RZY/images/icon-search-black.svg')}}" class="icon-img icon-search icon-black"/>
            </a>
        </li>
        @guest()
        <li class="nav-item position-relative for-cartt">
            <a href="javascript:void(0);" id="cart-icon">
                <img src="{{url('/').'/RZY/images/cart-icon.svg'}}" class="icon-img icon-bag icon-white"/>
                <img src="{{url('/').'/RZY/images/cart-icon-black.svg'}}" class="icon-img icon-search icon-black"/>
            </a>
        </li>
        @endguest
        @auth()
        @if ( strcmp( ConstantsController::USER_ROLES['admin'], Auth::user()->role ) !== 0 )
        <li class="nav-item position-relative for-cartt">
            <a href="javascript:void(0);" id="cart-icon">
                <div class="badge-on-cart">{{$cart -> cart_count}}</div>
                <img src="{{url('/').'/RZY/images/cart-icon.svg'}}" class="icon-img icon-bag icon-white"/>
                <img src="{{url('/').'/RZY/images/cart-icon-black.svg'}}" class="icon-img icon-search icon-black"/>
            </a>
        </li>
        @endauth
        @endif
        <li class="nav-item right-side-menu">
            @guest()
            <a href="{{route('auth.login')}}" class="position-relative" data-toggle="tooltip" data-placement="left" title="Login to your account">
                <img src="{{url('/').'/RZY/images/user-icon.svg'}}" class="icon-img icon-menu icon-white" /> 
                <img src="{{url('/').'/RZY/images/user-icon-black.svg'}}" class="icon-img icon-search icon-black"  />
            </a>
            @endguest
            @auth()
            <a href="javascript:void(0);" class="position-relative loggedInUser" data-toggle="tooltip" data-html="true" data-placement="left" title="logged-in as: {{Auth::user()->email}}">
                <img src="{{url('/').'/RZY/images/user-icon.svg'}}" class="icon-img icon-menu icon-white"  />
                <img src="{{url('/').'/RZY/images/user-icon-black.svg'}}" class="icon-img icon-search icon-black" />
                {{-- <div class="icon-white">
                    <div class="avatar-headers" width="25" height="25" data-name="{{Auth::user()->name}}"></div>
                </div>
                <div class="icon-black">
                    <div class="avatar-headers" width="25" height="25" data-name="{{Auth::user()->name}}"></div>
                </div> --}}
            </a>
            <!-- Add class "mobile-mega-menu" to "mega-menu" -->
            <div class="avatar-pop-up" id="avatar-pop-up" style="display: none;">
                <div class="arrow-upp-user-portal"></div>
                <div class="card-avatar-popup">
                    <div class="upper"> </div>
                        <div class="user text-center">
                            <div class="profile">
                                <div class="avatar-initials" width="70" height="70" data-name="{{Auth::user()->firstname}}.' '.{{Auth::user()->lastname}}">
                                {{Auth::user()->firstname ? strtoupper(Auth::user()->firstname)[0] : ''}}{{Auth::user()->lastname ? strtoupper(Auth::user()->lastname)[0] : ''}}
                                </div>
                            </div>
                        </div>
                    <div class="text-center inner-user-settings">
                        <div class="user-information">
                            <h4 class="user-name">{{Auth::user()->firstname.' '.Auth::user()->lastname}}</h4>
                            <h4 class="user-email">{{Auth::user()->email}}</h4>
                        </div>
                        <div class="user-settings-block1">
                            <a href="{{route('dashboard')}}" class="user-settings">
                                <div class="user-settings">Dashboard</div>
                                <i class="bi bi-speedometer"> </i>
                            </a>
                        </div>
                        @if ( strcmp( ConstantsController::USER_ROLES['admin'], Auth::user()->role ) !== 0 )
                        <div class="user-settings-block1">
                            <a href="{{route('dashboard.vieworder')}}" class="user-settings">
                                <div class="user-settings">View Orders</div>
                                <i class="bi bi-clipboard"> </i>
                            </a>
                        </div>
                        <div class="user-settings-block1">
                            <a href="{{route('dashboard.invoice')}}" class="user-settings">
                                <div class="user-settings">View Invoices</div>
                                <i class="bi bi-receipt"> </i>
                            </a>
                        </div>
                        <div class="user-settings-block1">
                            <a href="{{route('dashboard.viewreturn')}}" class="user-settings">
                                <div class="user-settings">View Returns</div>
                                <i class="bi bi-archive-fill"> </i>
                            </a>
                        </div>
                        @endif
                        <div class="user-settings-block1">
                            <a class="user-settings" style="" href="{{route('auth.logout')}}">
                            <div class="user-settings">Logout</div>
                            <i class="bi bi-power" style="color: orangered;"> </i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endauth
        </li>
    </ul>


@php
    // $active_theme object is available containing the theme developer json loaded.
    // This is for the theme developers who want to load further view assets

    use App\Http\Controllers\ConstantsController;
    use App\Http\Controllers\CommonController;

@endphp

<div class="col-lg-10 col-md-5 header-menu text-left">
    <ul class="menu">
        <li class="parant">
            @if(isset($menus -> rug_header))
                <a href="{{$pages -> all_pages -> sections -> main_top_menu -> menu_1_url}}"
                   class="main-item">{{$pages -> all_pages -> sections -> main_top_menu -> menu_1_caption}} <i
                        class="icon-chevron-down"></i></a>
                <div class="drop-down-item">
                    <div class="container">
                        <div class="sub-menu-item">

                            @foreach($menus -> rug_header -> metas as $meta)
                                <a href="{{ $meta -> meta_url }}">{{ $meta -> meta_title }}</a>
                            @endforeach

                        </div>
                    </div>
                </div>
            @else
                <a href="{{$pages -> all_pages -> sections -> main_top_menu -> menu_1_url}}"
                   class="main-item">{{$pages -> all_pages -> sections -> main_top_menu -> menu_1_caption}}</a>
            @endif
        </li>
            @if($pages -> all_pages -> sections -> main_top_menu -> menu_2_caption !== null)
                <li class="parant" style="display: {{ !(Auth::check() && (Auth::user()->broadloom_user || Auth::user()->is_sale_rep)) ? 'inline-block' : 'none' }}" id="broadloomLi">
                    <a href="{{$pages -> all_pages -> sections -> main_top_menu -> menu_2_url}}" class="main-item">
                        {{$pages -> all_pages -> sections -> main_top_menu -> menu_2_caption}}
                    </a>
                </li>
            @endif

        @if($pages -> all_pages -> sections -> main_top_menu -> menu_3_caption !== null)
            <li class="parant"><a href="{{$pages -> all_pages -> sections -> main_top_menu -> menu_3_url}}"
                                  class="main-item">{{$pages -> all_pages -> sections -> main_top_menu -> menu_3_caption}}</a>

            </li>
        @endif

        @if($pages -> all_pages -> sections -> main_top_menu -> menu_4_caption !== null)
            <li class="parant"><a href="{{$pages -> all_pages -> sections -> main_top_menu -> menu_4_url}}"
                                  class="main-item">{{$pages -> all_pages -> sections -> main_top_menu -> menu_4_caption}}</a>

            </li>
        @endif
        @if($pages -> all_pages -> sections -> main_top_menu -> menu_5_caption !== null)
            <li class="parant"><a href="{{$pages -> all_pages -> sections -> main_top_menu -> menu_5_url}}"
                                  class="main-item">{{$pages -> all_pages -> sections -> main_top_menu -> menu_5_caption}}</a>

            </li>
        @endif
        <li class="parant d-none">
            @if(!Auth::user())
                <span> <a href="{{route('auth.register')}}" class="main-item"> Become a Partner</a></span>
            @endif
        </li>
        <li class="parant" id="loginLi" style="display: {{ !Auth::user() ? 'inline-block' : 'none' }}">
            <span> <a href="{{route('auth.login')}}" class="main-item"> Login</a></span>
            {{-- @else
                <span> <a href="javascript:void(0)" class="main-item quickProfile-opener"> <img src="/MOM/images/myaccount-icon-mom.svg"></a></span> --}}
        </li>
        <li class="parant">
            <div class="" id="search_text_container">
                <input type="text" name="searchText" id="search_field" style="display: none;"
                       class="border-0 main-item search-field search-input" placeholder="Search Here"/>
                <span>
                    <a href="javascript:void(0)" class="search-button exclude-link submit-btn search-icon"
                       id="click-search">
                        <img src="/MOM/images/search-icon-mom.svg" id="serach-popup-btn-box">
                    </a>
                </span>
            </div>
        </li>
        <li class="parant">
            <span id="cart-parent">

                <a href="javascript:void(0)" class="main-item quickCart-opener position-relative">
                    @auth()
                        <span class="badge badge-pill badge-primary position-absolute cartCount"
                              style="top: auto">{{$cart -> cart_count}}</span>
                    @endauth
                    <img src="/MOM/images/cart-icon-mom.svg">
                </a>
            </span>
        </li>
        <li class="parant" style="display: {{ !Auth::user() ? 'inline-block' : 'none' }}" id="profileLi">
            <span> <a href="javascript:void(0)" class="main-item quickProfile-opener"> <img
                        src="/MOM/images/myaccount-icon-mom.svg"></a></span>
        </li>
    </ul>
</div>
@auth()
    <div class="quick-profile col-sm-12 position-fixed checkout-balance col-12 container-checker d-none">
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


    @if ( ! Str::contains(request()->url(), 'checkout'))
        <div class="quickCart position-fixed d-none container-checker" id="quickCart">
            @if(isset($cart -> items) && count((array)$cart -> items))
                @php
                    $broadloom_item = false;
                @endphp
                <div class="col-sm-12 m-md-2 checkout-balance col-12 position-absolute">
                    <i class="close-icon icon-cross position-absolute quickcart-closer"> </i>
                    <div class="checkout_items_wrap mt-4">
                        @foreach($cart -> items as $item)
                            @php
                                if(isset($item -> item_data) && $item -> item_data) {
                                //dd(unserialize($item->item_data));
                                    $item_data = json_decode(unserialize($item->item_data));
                                    //$item_data = json_decode($item -> item_data);
                                }

                                if ($item->broadloom_item) {
                                    $broadloom_item = true;
                                }
                            @endphp
                            <div
                                class="d-flex flex-row justify-content-between align-items-center p-3 pt-3 border-bottom-thick"
                                id="{{$item -> item_id}}__{{$item -> item_customer_id}}">
                                <div class="col-md-3 products-thumbnails position-relative align-self-baseline p-0">
                                    <a href="javascript:void(0)" class="d-block newStyle">
                                        <i class="position-absolute icon-cross removeProd"
                                           onclick="removeItemFromCart('{{$item -> item_id}}','{{csrf_token()}}','{{$item -> item_customer_id}}')"> </i>
                                        <img src="{{$item -> item_image}}"
                                             onerror="this.onerror=null; this.src='{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'"
                                             alt="{{$item -> item_name}}">
                                    </a>
                                </div>
                                <div class="col-md-9">
                                    <h3 class="font-ropa m-0">{{$item -> item_name}}</h3>
                                    {{--
                                    <p class="specs m-0"> <strong> Customer ID: </strong> <span> {{$item -> item_customer_id}} </span> </p>
                                    <p class="specs m-0"> <strong> Item ID: </strong> <span> {{$item -> item_id}} </span> </p>
                                    --}}
                                    <p class="specs m-0"><strong> Color: </strong>
                                        <span> {{$item -> item_color}} </span></p>
                                    <p class="specs m-0"><strong> Size: </strong> <span> {{$item -> item_size}} </span>
                                    </p>@if($item->oak_item)
                                <p class="specs m-0"> <strong> SKU: </strong> <span> {{$item -> oak_sku}} </span> </p>
                                @endif
                                    <p class="price justify-content-end m-0">{{$item -> item_currency}}{{$item -> item_total}} </p>
                                    <hr>
                                    <div
                                        class="action-item-sm p-2 px-0 d-flex flex-row align-items-center justify-content-between col-sm-12 overflow-hidden">
                                        <input type="number"
                                               oninput="showUpdateCartButton('{{$item -> item_id}}__{{$item -> item_customer_id}}')"
                                               onkeydown="if(this.key==='.'){this.preventDefault();}"
                                               class="form-control" min="1" value="{{$item -> item_quantity}}"
                                               {{isset($item_data -> oak) && $item_data -> oak ? 'readonly' : ''}} style="margin-right: 10px; max-width: 80px;">
                                        <a href="javascript:void(0);" style="display: none;"
                                           onclick="updateCart('{{$item -> item_id}}','{{csrf_token()}}','{{$item -> item_customer_id}}')"
                                           class="update-cart-button font-ropa ms-1"> Update </a>
                                        <div id="updating-cart" class="d-none flex-column text-center">
                                            <div class="spinner-border" role="status">
                                                <span class="sr-only" style="opacity:0;">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <!-- <h2 class="text-muted text-center mt-5 mb-3 emptyCart"> Cart is empty! </h2> -->
                    </div>
                    <div class="col-md-12 px-5 py-1">
                        <hr>
                        <p class="specs m-0 d-flex justify-content-between mb-2">
                            <strong class="font-crimson"> Sub Total </strong>
                            <span
                                class="font-ropa cart_sub_total"> {{$cart -> cart_currency}}{{$cart -> cart_total}} </span>
                        </p>
                        <input type="hidden" value="940">
                        <p class="specs m-0 d-flex justify-content-between mb-2">
                            <strong class="font-crimson"> Shipping</strong>
                            <span class="font-ropa shipping_price_value"> (will be calculated on checkout) </span>
                        </p>
                        <hr>
                        <p class="specs m-0 d-flex justify-content-between total-amount">
                            <strong class="font-crimson"> Total </strong>
                            <span
                                class="font-ropa cart_total_price"> {{$cart -> cart_currency}}{{$cart -> cart_total}} </span>
                        </p>
                        <a href="{{ $broadloom_item ? route('broadloom.shopping_cart') : route('frontend.checkout')}}"
                           class="btn btn--md btn--border_1 mt-3 quick-cart-btn d-block">View Cart & Checkout<i
                                class="icon-arrow-right"></i></a>
                    </div>
                </div>
            @else
                <div class="d-flex align-items-center col-md-12 d-flex justify-content-center" style="height: 100%;">
                    <i class="close-icon icon-cross position-absolute quickcart-closer"> </i>
                    <div class="col-md-12">
                        <h3 class="d-flex footer-widget__title justify-content-center m-0 mb-2 specs">
                            <strong class="font-crimson cart-is-empty"> Cart is Empty </strong>
                        </h3>
                    </div>
                </div>
            @endif
        </div>

    @endif
@endauth
<div id="show-on-search" style="display: none;">
    <div id="search-custom-overlay"></div>
    <div id="custom-cross-btn">X</div>
    <div class="full-screen-serach-box_inner_wrapper d-flex align-items-center">
        <div class="header-left-search" id="search_text_container">
            <div class="header-search-box">
                <input type="text" name="searchText" id="popup-search" class="search-field search-input"
                       id="formGroupExampleInput" placeholder="Search" value="">
                <button id="serach-popup-btn-box" class="search-button submit-btn search_text_button search-icon">
                    <img src="/MOM/images/white-search-icon-mom.svg">
                </button>
            </div>
        </div>
    </div>
</div>
<div class="cartoverlay"></div>

@php
    // $active_theme object is available containing the theme developer json loaded.
    // This is for the theme developers who want to load further view assets

    use App\Http\Controllers\ConstantsController;
    use App\Http\Controllers\CommonController;

@endphp
<style>
    .nav-link:hover{
        text-decoration: underline 2px #660000;
        color: #660000 !important;
    }
     /* Reduce space around nav items */
     .navbar .nav-item,
    .navbar .navbar-item {
        margin: 0;
        padding: 0;
    }

    .navbar .nav-link {
        padding: 0.25rem 0.75rem; /* Reduce padding: top/bottom left/right */
        font-size: 0.9rem; /* Optional: smaller text */
    }

    .dropdown-menu {
        margin-top: 0; /* Prevent gap between toggle and menu */
    }

    /* Adjust image and badge positioning inside nav */
    .nav-link img {
        max-height: 20px;
    }

    .cartCount {
        top: -5px;
        right: -10px;
    }
  
    .text-light:hover{
        color: black;
    }
    a:hover{
color: black !important;

    }
    .abc a{
    color: white;
    }
    .abc,.abc>a:hover{
        color: black;
    }
    .dropdown-item {
        padding-left: 0px;
        padding-right: 8px;
    }
    .dropdown-item:active {
        background-color: white !important;
    }
.dropdown-item:hover a {
    color: black !important;
}

</style>
@php
    $allIds=[];
    $ids = [];
@endphp
<div class=" header-menu  text-left">
<!-- Start Bootstrap 5 Navbar -->
<ul class="navbar">

    {{-- <li class="nav-item dropdown"> --}}
    <li class="navbar-item dropdown">
        @if(isset($menus -> rug_header))
       
        <a href="{{ $pages->all_pages->sections->main_top_menu->menu_1_url }}" 
           class="nav-link dropdown-toggle" 
           id="dropdownMenu1" 
           role="button" 
           data-bs-toggle="dropdown" 
           aria-expanded="false">
            {{ $pages->all_pages->sections->main_top_menu->menu_1_caption }}
        </a>
        <ul class="dropdown-menu " aria-labelledby="dropdownMenu1" style="background-color:#660000;">
            {{-- <li class="dropdown-item "><a href="#" class="abc" style="color:white">new collection</a></li>
            <li class="dropdown-item "><a href="#" class="abc" style="color:white">abc </a></li>
            <li class="dropdown-item "><a href="#" class="abc" style="color:white">ab</a></li> --}}
            @foreach($menus->rug_header->metas as $meta)
             <li class="dropdown-item "><a href="{{ $meta->meta_url }}" class="abc" style="color:white">{{ $meta->meta_title }}</a></li>

                {{-- <li><a href="{{ $meta->meta_url }}" class="dropdown-item">{{ $meta->meta_title }}</a></li> --}}
            @endforeach
        </ul>
         @else 
          <a href="{{$pages -> all_pages -> sections -> main_top_menu -> menu_1_url}}"
               class="main-item">{{$pages -> all_pages -> sections -> main_top_menu -> menu_1_caption}}</a>
        @endif 
    </li>
    
    @if($pages->all_pages->sections->main_top_menu->menu_2_caption)
        <li class="navbar-item">
            <a href="{{ $pages->all_pages->sections->main_top_menu->menu_2_url }}" class="nav-link">
                {{ $pages->all_pages->sections->main_top_menu->menu_2_caption }}
            </a>
        </li>
    @endif

    @if($pages->all_pages->sections->main_top_menu->menu_3_caption)
        <li class="navbar-item">
            <a href="{{ $pages->all_pages->sections->main_top_menu->menu_3_url }}" class="nav-link">
                @if($pages->all_pages->sections->main_top_menu->menu_3_caption === "OAK")
                    ONE OF A KIND
                @else
                    {{ $pages->all_pages->sections->main_top_menu->menu_3_caption }}
                @endif
            </a>
        </li>
    @endif
   @if($pages -> all_pages -> sections -> main_top_menu -> menu_6_caption !== null && Auth::user() && (Auth::user()->is_sale_rep == 1 || Auth::user()->broadloom_user == 1))
    <li class="navbar-item">
        <a href="{{ $pages->all_pages->sections->main_top_menu->menu_6_url }}" class="nav-link">
            {{ $pages->all_pages->sections->main_top_menu->menu_6_caption }}
        </a>
    </li>
    @endif
    @if($pages->all_pages->sections->main_top_menu->menu_4_caption)
        <li class="navbar-item">
            <a href="{{ $pages->all_pages->sections->main_top_menu->menu_4_url }}" class="nav-link">
                {{ $pages->all_pages->sections->main_top_menu->menu_4_caption }}
            </a>
        </li>
    @endif

    @if($pages->all_pages->sections->main_top_menu->menu_5_caption)
        <li class="navbar-item">
            <a href="{{ $pages->all_pages->sections->main_top_menu->menu_5_url }}" class="nav-link">
                {{ $pages->all_pages->sections->main_top_menu->menu_5_caption }}
            </a>
        </li>
    @endif

    @if(!Auth::user())
        <li class="navbar-item d-none">
            <a href="{{ route('auth.register') }}" class="nav-link">Become a Partner</a>
        </li>
    @endif

    <li class="navbar-item" id="loginLi" style="display: {{ !Auth::user() ? 'inline-block' : 'none' }}">
        <a href="{{ route('auth.login') }}" class="nav-link">Login</a>
    </li>
    <li class="navbar-item">
        <div id="search_text_container" class="d-flex align-items-center">
            <input type="text" name="searchText" id="search_field" class="form-control me-2 d-none" placeholder="Search Here"/>
            <a href="javascript:void(0)" class="nav-link p-0" id="click-search">
                <img src="/MOM/images/search-icon-mom.svg" alt="Search">
            </a>
        </div>
    </li>
    
 
    <li class="navbar-item">
        <a href="javascript:void(0)" class="nav-link position-relative quickCart-opener">
            @auth
                <span class="badge badge-pill badge-primary position-absolute cartCount" style="top:0; right:0">{{ $cart->cart_count }}</span>
            @endauth
            <img src="/MOM/images/cart-icon-mom.svg" alt="Cart">
        </a>
    </li>

    @if(Auth::user())
        <li class="navbar-item" id="profileLi">
            <a href="javascript:void(0)" class="nav-link quickProfile-opener">
                <img src="/MOM/images/myaccount-icon-mom.svg" alt="My Account">
            </a>
        </li>
    @endif
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
                            <div>Place Rug Order</div>
                            <i class="icon-cart-plus"></i>
                        </a>
                    </div>
                    @if(Auth::user()->is_sale_rep == 1 || Auth::user()->broadloom_user == 1)
                    <div class="user-settings-block1 p-0">
                        <a href="{{route('dashboard.place_bl_order')}}" class="user-settings">
                            <div>Place Broadloom Order</div>
                            <i class="icon-cart-plus"></i>
                        </a>
                    </div>
                    @endif
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
                    <div class="checkout_items_wrap mt-4" style="overflow-x: hidden">
                        @foreach($cart -> items as $item)
                            @php
                                if(isset($item -> item_data) && $item -> item_data) {
                                    $item_data = json_decode(unserialize($item->item_data));
                                    //$item_data = json_decode($item -> item_data);
                                }

                                if ($item->broadloom_item) {
                                    $broadloom_item = true;
                                }
                            @endphp
                            @if (!$item->broadloom_item)
                                <div
                                    class="d-flex flex-row justify-content-between align-items-center p-3 pt-3 border-bottom-thick"
                                    id="{{$item -> item_id}}__{{$item -> item_customer_id}}">
                                    <div class="col-md-3 products-thumbnails position-relative align-self-baseline p-0">
                                        <a href="javascript:void(0)" class="d-block newStyle">
                                            <i class="position-absolute icon-cross removeProd"
                                               onclick="removeItemFromCart(this,'{{$item -> id}}','{{$item -> item_id}}','{{csrf_token()}}','{{$item -> item_customer_id}}', '{{$item->broadloom_item}}', '{{$item->bd_roll_id}}', '')"> </i>
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
                                        <p class="specs m-0"><strong> Size: </strong>
                                            <span> {{$item -> item_size}} </span>
                                        </p>
                                        {{-- @if($item->oak_item)
                                            <p class="specs m-0"> <strong> SKU: </strong> <span> {{$item -> oak_sku}} </span> </p>
                                        @endif --}}
                                        @if($item->oak_item)
                                            <p class="specs m-0"><strong> SKU: </strong>
                                                <span> {{$item -> oak_sku}} </span></p>
                                        @endif<p
                                            class="price justify-content-end m-0">{{$item -> item_currency}}{{number_format($item->item_total, 2)}} </p>
                                        <hr>
                                        @if(!$item->broadloom_item)
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
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        @foreach ($cart->items as $item)
                        @php
                            array_push($allIds,$item -> id);
                        @endphp
                            @if($item->broadloom_item)
                           
                                @if ($item->is_bd_child != 1)
                                @php
                                
                                
                                  $prevId=$item -> id;
                                @endphp
                            
                                <div
                               
                                    class="d-flex flex-row justify-content-between align-items-center p-3 pt-3 border-bottom-thick "
                                    id="{{$item -> item_id}}__{{$item -> item_customer_id}}">
                                    <div class="col-md-3 products-thumbnails position-relative align-self-baseline p-0">
                                        <a href="javascript:void(0)" class="d-block newStyle">
                                            <i class="position-absolute icon-cross removeProd"
                                               onclick="removeItemFromCart(this,'{{$item -> id}}','{{$item -> item_id}}','{{csrf_token()}}','{{$item -> item_customer_id}}', '{{$item->broadloom_item}}', '{{$item->bd_roll_id}}', '{{$item->rand_str}}')"> </i>
                                            <img src="{{$item -> item_image}}"
                                                 onerror="this.onerror=null; this.src='{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'"
                                                 alt="{{$item -> item_name}}">
                                        </a>
                                    </div>
                                    <div class="col-md-9">
                                        <h3 class="font-ropa m-0">{{$item -> item_name}}</h3>
                                        <p class="specs m-0"><strong> Color: </strong>
                                            <span> {{$item -> item_color}} </span></p>
                                        <div class="row">
                                            <div class="col-2">
                                                <strong>Sizes: </strong>
                                            </div>
                                            <div class="col-10">
                                                <div class="specs">
                                                    @php
                                                        $sizes = json_decode( unserialize($item->item_data ), true );
                                                        //$sizes = json_decode($item->item_data, true);
                                                    @endphp
                                                    @if(isset($sizes['CutPieces']))
                                                        @foreach($sizes['CutPieces'] as $item_sizes)
                                                            @php
                                                                $lenght_feet =  (int)floor($item_sizes['ATSLength'] / 12);
                                                                $width_feet =  (int)floor($item_sizes['ATSWidth'] / 12);
                                                                $lenght_inch =  $item_sizes['ATSLength'] % 12;
                                                                $width_inch =   $item_sizes['ATSWidth'] % 12;
                                                            @endphp
                                                            <div
                                                                class="badge badge-default broadloom-badge side-bar-broadloom-badge"
                                                                style="background: @if($item_sizes['LengthStatus'] == 'F') blue @else #660000 @endif">
                                                        <span>
                                                            {{ $width_feet . "'" . $width_inch . "\"" . " x " . $lenght_feet  . "'" . $lenght_inch . "\"" }}
                                                        </span>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <p class="price justify-content-end m-0 menu-price">Price:
                                            <span>{{$item -> item_currency}}{{ number_format($item->item_total, 2) }}</span></p>
                                        <hr>
                                        <div id="id-{{$item -> id}}" style="width: 320px; margin:0; padding:0;"></div>
                                    </div>
                                </div>
                            
                             @elseif($item->is_bd_child == 1)
                              @php
                                // $cart -> cart_total+=number_format($cart -> cart_total, 2)+number_format($item->item_total, 2);
                               // Assuming both are strings like "100.50" and "50.25"
                               $cart_total = (float)str_replace(',', '', $cart->cart_total);
                               $item_price = (float)str_replace(',', '', $item->item_total);
                               $mini_total=$cart_total+$item_price;
                                $cart->cart_total = $mini_total;
                                array_push($ids, $prevId);
                                @endphp

<div
                                    class="d-flex flex-row justify-content-between align-items-center p-3 pt-3 border-bottom-thick id-{{$prevId}}"
                                    id="{{$item -> item_id}}__{{$item -> item_customer_id}}">
                                    <div class="col-md-1 products-thumbnails position-relative align-self-baseline p-0">
                                        <a href="javascript:void(0)" class="d-block newStyle">
                                            <i class="position-absolute icon-cross removeProd " data-items='@json($allIds)'
                                            
                                               onclick="removeItemFromCart(this,'{{$item -> id}}','{{$item -> item_id}}','{{csrf_token()}}','{{$item -> item_customer_id}}', '{{$item->broadloom_item}}', '{{$item->bd_roll_id}}', '{{$item->rand_str}}')"> </i>
                                            
                                        </a>
                                    </div>
                                    <div class="col-md-11">
                                        <h6 class="font-ropa m-0">RUG PAD</h6>
                                       
                                        <div class="row">
                                            <div class="col-2">
                                                <strong>Sizes: </strong>
                                            </div>
                                            <div class="col-10">
                                                <div class="specs">
                                                    @php
                                                        $sizes = json_decode( unserialize($item->item_data ), true );
                                                        //$sizes = json_decode($item->item_data, true);
                                                    @endphp
                                                    @if(isset($sizes['CutPieces']))
                                                        @foreach($sizes['CutPieces'] as $item_sizes)
                                                            @php
                                                                $lenght_feet =  (int)floor($item_sizes['ATSLength'] / 12);
                                                                $width_feet =  (int)floor($item_sizes['ATSWidth'] / 12);
                                                                $lenght_inch =  $item_sizes['ATSLength'] % 12;
                                                                $width_inch =   $item_sizes['ATSWidth'] % 12;
                                                            @endphp
                                                            <div
                                                                class="badge badge-default broadloom-badge side-bar-broadloom-badge"
                                                                style="background: @if($item_sizes['LengthStatus'] == 'F') blue @else #660000 @endif">
                                                        <span>
                                                            {{ $width_feet . "'" . $width_inch . "\"" . " x " . $lenght_feet  . "'" . $lenght_inch . "\"" }}
                                                        </span>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <p class="price justify-content-end m-0 menu-price">Price:
                                            <span>{{$item -> item_currency}}{{ number_format($item->item_total, 2) }}</span></p>
                                        <hr>
                                        
                                    </div>
                                </div>
                            @endif
                            @endif
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
                            <strong class="font-crimson"> Shipping  </strong>
                            <span class="font-ropa shipping_price_value ml-2"> (Will be calculated at the time of shipping) </span>
                        </p>
                        <hr>
                        <p class="specs m-0 d-flex justify-content-between total-amount">
                            <strong class="font-crimson"> Total </strong>
                            <span
                                class="font-ropa cart_total_price"> {{$cart->cart_currency}}{{$cart->cart_total}} </span>
                        </p>

                        <a href="{{ $broadloom_item ? route('broadloom.shopping_cart') : route('frontend.checkout')}}"
                           class="btn btn--md btn--border_1 mt-3 quick-cart-btn quick_btn d-block">View Cart & Checkout<i
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
<script>
    $(document).ready(function () {
        $('.quick_btn-now').on('click', function(event){
            event.preventDefault();
            $(this).attr('href', '');
            $.ajax({
                url: "{{ route('verify-cart-items') }}",
                type: 'GET',
                success: function(response) {
                    console.log('verify-cart-items response', response);

                    if(response.broadloom){
                        window.location.href = "{{ route('broadloom.shopping_cart') }}";
                    }else{
                        window.location.href = "{{ route('frontend.checkout') }}";
                    }
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });
    });
</script>
<script>
    var dropdowns = document.querySelectorAll('.nav-item.dropdown');
    dropdowns.forEach(function(dropdown) {
        var menu = dropdown.querySelector('.dropdown-menu');
        dropdown.addEventListener('mouseenter', function() {
            menu.classList.add('show'); 
        });
        dropdown.addEventListener('mouseleave', function() {
            menu.classList.remove('show');
        });
    });

    document.getElementById('click-search').addEventListener('click', function () {
        var searchField = document.getElementById('search_field');
        searchField.classList.toggle('d-none');
        searchField.focus(); // Optional: focus on the input after showing
    });
   
    
   
        // Example array of strings
        let myArray = {!! json_encode($ids) !!};
        
        myArray.forEach(function (key) {

            
            let sourceElement = document.querySelector('.id-' + key);
            let targetElement =  document.querySelector('#id-' + key);

            if (sourceElement && targetElement) {
                // Get the content
                let content = sourceElement.innerHTML;

                // Clear the source content
                sourceElement.innerHTML = '';
                sourceElement.classList.add('d-none');

                // Add the content to the matching ID
                targetElement.innerHTML = content;
            }
        });




</script>

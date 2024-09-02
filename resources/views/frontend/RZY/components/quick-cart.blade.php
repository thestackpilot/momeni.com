@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp 

<aside id="quick_cart" class="quick-cart position-fixed d-none">
    <div class="overlay position-fixed"></div>
    <div class="bg-white position-absolute quick-cart-inner">
        <a href="javascript:void(0);" id = "close-cart" class="close-icon position-absolute"> <i class="bi bi-x-lg"></i> </a>
        <div class="cart-items">
            <div class="minicart-header d-flex flex-row align-items-center justify-content-between">
                <h2>Your Order</h2>
                <span class="badge rounded-pill bg-secondary">{{$cart -> cart_count}}</span>
            </div>
            <div class="minicart-content mt-4">
                @if(!empty($cart -> items))
                    @foreach($cart -> items as $item)
                        <div class="d-flex flex-row justify-content-lg-between flex-dir-col" id="{{$item -> item_id}}__{{$item -> item_customer_id}}">
                            <div class="col-md-4 products-thumbnails position-relative">
                                <a href="javascript:void(0);" >
                                    <i class="bi bi-x-circle-fill remove-product-cart" onclick="removeItemFromCart('{{$item -> item_id}}','{{csrf_token()}}','{{$item -> item_customer_id}}')"></i>
                                    <img src="{{$item -> item_image}}" onerror="this.onerror=null; this.src='{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'" alt="{{$item -> item_name}}">
                                </a>
                            </div>
                            <div class="col-md-7">
                                <h2 class="font-ropa product-heading m-0">{{$item -> item_name}}</h2>
                                {{-- 
                                <p class="specs m-0"> <strong class="font-crimson"> Customer ID: </strong> <span class="font-ropa"> {{$item -> item_customer_id}} </span> </p>
                                <p class="specs m-0"> <strong class="font-crimson"> Item ID: </strong> <span class="font-ropa"> {{$item -> item_id}} </span> </p>
                                --}}
                                <p class="specs m-0"> <strong class="font-crimson"> Color: </strong> <span class="font-ropa"> {{$item -> item_color}} </span> </p>
                                <p class="specs m-0"> <strong class="font-crimson"> Size: </strong> <span class="font-ropa"> {{$item -> item_size}} </span> </p>
                                <p class="price justify-content-end m-0"> {{$item -> item_currency}}{{$item -> item_total}} </p>
                                <hr class="minicart-seprator" />
                                <div class="action-item-sm p-2 px-0 d-flex flex-row align-items-center justify-content-between">
                                    <input type="number" oninput="showUpdateCartButton('{{$item -> item_id}}__{{$item -> item_customer_id}}')" onkeydown="if(this.key==='.'){this.preventDefault();}" class="form-control" max="{{ $item->item_only_max_quantity ? ($item->item_atsq > $item -> item_quantity ? $item->item_atsq : $item -> item_quantity) : '9999'}}" maxlength="4" min="1" value="{{$item -> item_quantity}}" />
                                    <a href="javascript:void(0);" style="display: none;" class="update-cart-button font-ropa" onclick="updateCart('{{$item -> item_id}}','{{csrf_token()}}','{{$item -> item_customer_id}}')"> Update </a>
                                    <div id="updating-cart" class="d-none d-flex flex-column text-center">
                                        <div class="spinner-border" role="status">
                                            <span class="sr-only" style="opacity:0;">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="minicart-seprator mt-4" />
                    @endforeach
                @else
                    <div class="d-flex flex-row cart-is-empty justify-content-lg-between flex-dir-col">
                        <div class="col-md-12">
                            <span>Cart is Empty</span>
                        </div>
                    </div>
                @endif
            </div>
            @if(!empty($cart -> items))
                <div class="minicart-footer position-absolute p-5 py-2 text-center" onclick="location.href='{{route('frontend.checkout')}} ';">
                    <h1 class="checkout-label font-ropa color-white">Checkout — <span> {{$cart -> cart_currency}}{{$cart -> cart_total}} </span></h1>
                    <a href="javascript:void(0)" class="btn-checkout font-crimson mt-4"> CHECKOUT </a>
                </div>
            @endif
        </div>
    </div>
</aside>

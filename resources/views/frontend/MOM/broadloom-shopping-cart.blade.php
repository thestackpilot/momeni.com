@php
    // $active_theme object is available containing the theme developer json loaded.
    // This is for the theme developers who want to load further view assets

    use App\Http\Controllers\ConstantsController;
    use App\Http\Controllers\CommonController;

@endphp

@section('title', 'Item Detail Page')
@extends('frontend.' . $active_theme->theme_abrv . '.layouts.app')

@section('content')
    <div class="wrapper">
        @include('frontend.' . $active_theme->theme_abrv . '.components.header')
        <main class="main-content">
            @php
                $cust = '';
            @endphp
            {{-- @include('frontend.'.$active_theme -> theme_abrv.'.components.breadcrumbs') --}}
            {{-- <div class="d-none" id="item_id" value="{{$roll_pieces['OutPut']["RollsAndCutPieces"][0]['ItemID']}}"></div> --}}
            <input type="hidden" name="" id="item_id" value="">
            <div class="container broadloom-wrapper" style="background-color: whitesmoke;">
                <div class="fa-3x font-weight--bold text-center stepper-heading">Shopping cart</div>
                <div class="steppers">
                    <ol>
                        <li class="checkout-step section-1 active">
                            <span>1</span>
                            Shopping Cart
                        </li>
                        <li class="checkout-step section-2">
                            <span>2</span>
                            Checkout
                        </li>
                        <li class="section-3">
                            <span>3</span>
                            Order Complete
                        </li>
                    </ol>
                </div>
            </div>
            {{-- @include('frontend.' . $active_theme->theme_abrv . '.components.shopping-cart-header') --}}
            <div class="section" id="section1">
                <div class="site-wrapper-reveal">
                    <div class="container mt-2">
                        <div class="row mt-4 mb-5">
                            <div class="col-md-9 col-sm-12" style="background-color: grey:">
                                <div class="table-responsive">
                                    <table id="" class="table for-data-table">
                                        <input type="hidden" name="item" id="item_ids" value="[]">
                                        <input type="hidden" name="quantity" id="quantities" value="[]">
                                        {{-- <input type="hidden" name="customer" id="customer_id" value=""> --}}
                                        {{-- @dd($cart) --}}
                                        @foreach ($cart->items as $row)
                                            @php
                                                $cust = $row->item_customer_id;
                                            @endphp
                                        @endforeach
                                        <input type="hidden" name="customer" id="customer_id" value="{{ $cust }}">
                                        <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>SubTotal</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if (count((array) $cart->items))
                                            @foreach ($cart->items as $item)
                                                {{-- @dd($item) --}}
                                                @php
                                                    if (isset($item->item_data) && $item->item_data) {
                                                        $item_data = json_decode(unserialize($item -> item_data));
                                                        // dump($item_data);
                                                    }
                                                @endphp
                                                <tr>
                                                    <th class="" scope="row">
                                                        <div class="row">
                                                            <div
                                                                class="col-1 justify-content-center align-content-center delete-row"
                                                                style="color: red;cursor: pointer;">x
                                                            </div>
                                                            <div class="col-3"><img
                                                                    src={{ CommonController::getApiFullImage($item_data->ImageName) }}
                                                                        alt="{{ $item_data->ItemID }}" height="80px" width="80px"
                                                                        onerror="this.onerror=null; this.src='{{url('/').ConstantsController::SPARS_LOGO}}'"
                                                                    >
                                                            </div>
                                                            <div class="col-8" style="font-size: 12px">
                                                                <div class=" mt-2 font-weight--bold row">Design: <p
                                                                        class="font-weight--normal mx-2">
                                                                        {{ $item_data->ItemName }}</p>
                                                                </div>
                                                                <div class=" mt-2 row">SKU: <p
                                                                        class="font-weight--normal mx-2">N/A</p>
                                                                </div>
                                                                <div class=" mt-2 row">Size: <p
                                                                        class="font-weight--normal mx-2">
                                                                        {{ $item->item_size }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <td class="align-content-center">
                                                        <div class="d-flex flex-row qty-styles mb-2">
                                                            <a href="javascript:void(0);" class="qty-minus qty-action">
                                                                -
                                                            </a>
                                                            <input type="number" id="item_qty" name="quantity"
                                                                   autocomplete="off"
                                                                   onkeydown="if(this.key==='.'){this.preventDefault();}"
                                                                   class="form-control" min="1" max="9999"
                                                                   maxlength="4" step="1" required
                                                                   value="{{ $item->item_quantity }}"/>
                                                            <a href="javascript:void(0);" class="qty-add qty-action"> +
                                                            </a>
                                                            <input type="hidden" class="item_id" name="item_id"
                                                                   value="{{ $item_data->ItemID }}">
                                                        </div>
                                                    </td>
                                                    <td class="align-content-center">
                                                        {{ $item->item_currency }}{{ $item->item_price }}</td>
                                                    <td class="align-content-center">{{ $item->item_currency }}<span
                                                            id="item_total_price">{{ $item->item_total }}</span></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                No Item in Cart
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    <div class="mt-4 d-flex justify-content-end mx-5">
                                        <button href="#" class=" btn btn-dark align-content-center" id="update_cart"
                                                disabled="disabled">
                                            Update Cart
                                        </button>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12 border">
                                <div class="d-flex justify-content-around align-items-left flex-column">
                                    <p class="mt-2 mb-2 text-center fa-2x">Cart Totals</p>
                                    <div class="row mt-3">
                                        <div class="col-md-6">SubTotal:</div>
                                        <div class="col-md-6 text-right">{{ $cart->cart_currency }}<span
                                                id="item_subtotal_price">{{ $cart->cart_total }}</span></div>
                                    </div>
                                    <hr style="border-top-color: whitesmoke;">
                                    <div class="row">
                                        <div class="col-md-9">Shipping Charges:</div>
                                        <div class="col-md-3 text-right shipping_charges">$0</div>
                                    </div>
                                    <hr style="border-top-color: whitesmoke;">
                                    <div class="row mt-3">
                                        <div class="col-md-6 font-weight-bold">Total:</div>
                                        <div class="col-md-6 font-weight-bold text-right cart_total"></div>
                                    </div>
                                    <btn class="add-to-cart-button text-left btn btn-dark col-md-12 mt-3 mb-3"
                                         id="proceed_to_checkout">
                                        Proceed to Checkout <i class="px-4 fa fa-long-arrow-right"></i>
                                        </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section" id="section2" style="display: none;">
                <div class="site-wrapper-reveal">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 my-5">
                                <div class="mb-5">
                                    <form class="needs-validation" id="customer_info" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-7 mb-2">
                                                <div class="d-flex">
                                                <input type="radio" name="shipping-address" class="existing-address customer-addr-select" id="existing-address" value="existing-address" />
                                                <select class="p-0 m-0" class="select-address" style="height:40px !important; line-height: 20px !important; padding: 0.375rem 1rem !important;">
                                                    @foreach($shipping_addresses['ShipToAddresses'] as $address)
                                                        <option value="{{$address['AddressID']}}">
                                                            {{$address['AddressID']}} : {!!$address['FirstName'] ? $address['FirstName'] . ( $address['LastName'] ? " {$address['LastName']}" : '' ) : ''!!}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @foreach($shipping_addresses['ShipToAddresses'] as $address)
                                                    <p style="display: none !important;" class="card-text address-card d-none {{$address['AddressID']}}" id="{{$address['AddressID']}}">
                                                        <input type="hidden" class="hidden-inp" name="shipping-address-data" value="{{json_encode($address)}}" />
                                                    </p>
                                                @endforeach
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-2">
                                               <div class="d-flex">
                                                <input type="radio" name="shipping-address" class="existing-address" id="other-address" value="other"/>
                                                <label for="other" class="mt-2">Dropship</label>
                                               </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5 mb-2">
                                                <label for="" class="form-label mb-0" style="font-size: 14px">First Name
                                                    <span class="text-danger" style="font-size: 18px">*</span></label>
                                                <input class="form-control disable-toggle" type="text" id="" name="FirstName"
                                                       placeholder=""
                                                       value="{{$shipping_addresses['ShipToAddresses'][0]['FirstName']}}"
                                                       required>
                                            </div>
                                            <div class="col-md-5 mb-2">
                                                <label for="" class="form-label mb-0" style="font-size: 14px">Last
                                                    Name<span class="text-danger" style="font-size: 18px">*</span></label>
                                                <input class="form-control disable-toggle" type="text" id="" name="LastName"
                                                       placeholder=""
                                                       value="{{$shipping_addresses['ShipToAddresses'][0]['LastName']}}"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5 mb-2">
                                                <label for="" class="form-label mb-0"
                                                       style="font-size: 14px">Email<span class="text-danger" style="font-size: 18px">*</span></label>
                                                <input class="form-control disable-toggle" type="email" id="" name="Email"
                                                       placeholder=""
                                                       value="{{$shipping_addresses['ShipToAddresses'][0]['Email']}}"
                                                       required>
                                            </div>
                                            <div class="col-md-5 mb-2">
                                                <label for="" class="form-label mb-0"
                                                       style="font-size: 14px">Phone<span class="text-danger" style="font-size: 18px">*</span></label>
                                                <input class="form-control disable-toggle" type="number" id="" name="Phone"
                                                       placeholder=""
                                                       value="{{$shipping_addresses['ShipToAddresses'][0]['Phone1']}}"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-10 mb-2">
                                                <label for="" class="form-label mb-0" style="font-size: 14px">Street
                                                    Address<span class="text-danger" style="font-size: 18px">*</span></label>
                                                <input class="form-control disable-toggle new-address" type="text" id="" name="Address1"
                                                       placeholder=""
                                                       value="{{$shipping_addresses['ShipToAddresses'][0]['Address1']}}"
                                                       required>
                                            </div>
                                            <div class="col-md-10 mb-2">
                                                <input class="form-control disable-toggle" type="text" id="bd-address2" name="Address2"
                                                       placeholder=""
                                                       value="{{$shipping_addresses['ShipToAddresses'][0]['Address2']}}"
                                                       >
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5 mb-2">
                                                <label for="" class="form-label mb-0" style="font-size: 14px">Town/
                                                    City<span class="text-danger" style="font-size: 18px">*</span></label>
                                                <input class="form-control disable-toggle" type="text" id="" name="City"
                                                       placeholder=""
                                                       value="{{$shipping_addresses['ShipToAddresses'][0]['City']}}"
                                                       required>
                                            </div>
                                            <div class="col-md-5 mb-2">
                                                <label for="" class="form-label mb-0"
                                                       style="font-size: 14px">State<span class="text-danger" style="font-size: 18px">*</span></label>
                                                <input class="form-control disable-toggle" type="text" id="" name="State"
                                                       placeholder=""
                                                       value="{{$shipping_addresses['ShipToAddresses'][0]['State']}}"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5 mb-2">
                                                <label for="" class="form-label mb-0" style="font-size: 14px">Zip
                                                    Code<span class="text-danger" style="font-size: 18px">*</span></label>
                                                <input class="form-control disable-toggle" type="text" id="" name="Zip"
                                                       placeholder=""
                                                       value="{{$shipping_addresses['ShipToAddresses'][0]['Zip']}}"
                                                       required>
                                            </div>
                                        </div>
                                        <p class="font-weight--bold " style="font-size: 18px">Additional Information</p>
                                        <div class="row">
                                            <div class="col-md-5 mb-2 align-content-center">
                                                <input class="form-check-input" type="checkbox" id=""
                                                       name="ship_complete" >
                                                <label class="form-check-label" for="" style="font-size: 14px">Ship
                                                    Complete</label>
                                            </div>
                                            <div class="col-md-5 mb-2">
                                                <label for="" class="form-label mb-0" style="font-size: 14px">P.O or
                                                    Reference Number<span class="text-danger" style="font-size: 18px">*</span></label>
                                                <input class="form-control" type="text" id="" name="reference_number"
                                                       placeholder="" value="" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5 mb-2">
                                                <label for="" class="form-label mb-0" style="font-size: 14px">Shipping
                                                    Date<span class="text-danger" style="font-size: 18px">*</span></label>
                                                <input class="form-controlmb-2 datepicker" type="text" id="datepicker" data-date-format="dd-mm-yyyy" name="ship_date"
                                                       placeholder="" value="" required>
                                            <label for="" class="form-label mb-1" style="font-size: 14px">Shipping Method</label>
                                                    <select name="shipping_method"class="form-control">
                                                        @if($shipping_options)
                                                            @foreach($shipping_options as $shipping_option)
                                                                <option
                                                                    {{ $default_ship_via_id == $shipping_option['ShipViaID'] ? 'selected' : '' }} value="{{$shipping_option['ShipViaID']}}">{{$shipping_option['Description']}}</option>
                                                            @endforeach
                                                        @else
                                                            <option value="3RDP">Standard ShipVia</option>
                                                        @endif
                                                    </select>
                                                </div><div class="col-md-5 mb-2">
                                                        <label for="" class="mb-0" style="font-size: 14px">Order Notes (optional)</label>
                                                        <textarea class="form-control" id="" name="shipping_instructions" style="height: 8rem;" placeholder=""></textarea>
                                                        <input type="hidden" name="item_broadloom" id="item_broadloom" value="{{$cart->item_broadloom}}">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{-- --}}
                            <div class="col-md-6 col-sm-12 my-5">
                                <div style="background-color: whitesmoke;">
                                    <div class="d-flex justify-content-around align-items-left flex-column">
                                        <p class="mt-2 mb-2 text-center fa-2x">Your Order</p>
                                        <div class="row mt-3 px-5">
                                            <div class="col-md-6">Product</div>
                                            <div class="col-md-6 text-right">SubTotal</div>
                                        </div>
                                        <hr class="mx-4" style="border-top-color: rgb(161, 161, 161);">
                                        @foreach ( $cart->items as $item)
                                            <div class="row px-5">
                                                <div class="col-md-9">
                                                    <div class="row">
                                                        <div class="col-3"><img
                                                                src="{{ CommonController::getApiFullImage($item_data->ImageName) }}"
                                                                alt="{{$item_data->ItemID}}" height="50px" width="80px"
                                                                onerror="this.onerror=null; this.src='{{url('/').ConstantsController::SPARS_LOGO}}'"></div>
                                                        <div class="col-9" style="font-size: 12px">
                                                            <div class="mx-3 mt-2 font-weight--bold row">Design: <p
                                                                    class="font-weight--normal mx-2">{{$item->item_name}}</p>
                                                            </div>
                                                            <div class="mx-3 mt-2 row">SKU: <p
                                                                    class="font-weight--normal mx-2">N/A</p></div>
                                                            <div class="mx-3 mt-2 row">Size: <p
                                                                    class="font-weight--normal mx-2">{{$item->item_size}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-md-3 text-right align-content-center">{{$item->item_currency}}{{$item->item_total}}</div>
                                            </div>
                                            <hr class="mx-4" style="border-top-color: rgb(161, 161, 161);">
                                        @endforeach
                                        <div class="row px-5">
                                            <div class="col-md-6 font-weight-bold">SubTotal</div>
                                            <div
                                                class="col-md-6 font-weight-bold text-right section_2_subtotal">{{$item->item_currency}}{{$cart->cart_total}}</div>
                                        </div>
                                        <hr class="mx-4" style="border-top-color: rgb(161, 161, 161);">
                                        <div class="row px-5">
                                            <div class="col-md-9">Shipping Charges</div>
                                            <div class="col-md-3 text-right section_2_shipping_charges">$0</div>
                                        </div>
                                        <hr class="mx-4" style="border-top-color: rgb(161, 161, 161);">
                                        <div class="row my-4 px-5">
                                            <div class="col-md-9 font-weight--bold">Total</div>
                                            <div
                                                class="col-md-3 font-weight--bold text-right section_2_cart_total"></div>
                                        </div>
                                        <hr class="mx-4" style="border-top-color: rgb(161, 161, 161);">
                                        <div class="row my-4 px-5 justify-content-center">
                                            <div class="col-md-12">
                                                <p class="text-center">Your personal data will be used to process your
                                                    order, support your experience throughout this website, and for
                                                    other purposes described in our privacy policy.</p>
                                                <div class="text-center">
                                                    <button
                                                        class="add-to-cart-button btn btn-dark align-content-center text-left"
                                                        id="place_order">
                                                        Place Order &nbsp; &nbsp; &nbsp;<i
                                                            class="fa fa-long-arrow-right pl-5"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section" id="section3" style="display: none;">
                <div class="container">
                    <div class="mt-5 mb-4 text-center" style="color: green; font-size:22px;">
                        Your order is processed and you will get the confirmation soon. Your Order Detail is:
                    </div>
                    <div class="container my-5">
                        <div class="row justify-content-center">
                            <div class="col-sm-3 text-center p-3 order-complete-border"
                                 style="border-left: 2px dashed green;">
                                <div class="row">
                                    <div class="col">
                                        <div>Order Number</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <strong id="orderno">4818</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 text-center p-3 order-complete-border"
                                 style="border-left: 2px dashed green;">
                                <div class="row">
                                    <div class="col">
                                        <div>Date</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <strong>{{ date('j F, Y') }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 text-center p-3 order-complete-border"
                                 style="border-right:  2px dashed green; border-left: 2px dashed green;">
                                <div class="row">
                                    <div class="col">
                                        <div>Total</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <strong class="cart_total">${{$cart->cart_total}}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="text-center fa-3x font-weight--bold">Order Details</div>
                    </div>
                    <div class="container my-5">
                        <div class="row justify-content-center mb-5">
                            <div class="col-sm-4 text-left fa">
                                PRODUCT
                            </div>
                            <div class="col-sm-4 text-right fa">
                                SUBTOTAL
                            </div>
                            <div class="col-md-9">
                                <hr class="mx-4" style="border-top-color: whitesmoke;">
                            </div>
                            <div class="col-md-5">
                                @foreach ( $cart->items as $item)
                                    <div class="row">
                                        <div class="col-3"><img
                                                src="{{ CommonController::getApiFullImage($item_data->ImageName) }}"
                                                alt="{{$item_data->ItemID}}" height="80px" width="80px" onerror="this.onerror=null; this.src='{{url('/').ConstantsController::SPARS_LOGO}}'">
                                        </div>
                                        <div class="col-9" style="font-size: 12px">
                                            <div class="mx-3 mt-2 font-weight--bold row">Design:
                                                <p class="font-weight--normal mx-2">{{$item->item_name}}</p>
                                            </div>
                                            <div class="mx-3 mt-2 row">SKU: <p class="font-weight--normal mx-2">N/A</p>
                                            </div>
                                            <div class="mx-3 mt-2 row">Size: <p class="font-weight--normal mx-2">
                                                    ${{$item->item_size}}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-md-3 text-right align-content-center">${{$cart->cart_total}}</div>
                            <div class="col-md-9">
                                <hr class="mx-4" style="border-top-color: whitesmoke;">
                            </div>
                            <div class="col-md-4 align-content-center font-weight--bold">SubTotal</div>
                            <div class="col-md-4 align-content-center text-right font-weight--bold">
                                ${{$cart->cart_total}}</div>
                            <div class="col-md-9">
                                <hr class="mx-4" style="border-top-color: whitesmoke;">
                            </div>
                            <div class="col-md-4 align-content-center">Shipping Charges</div>
                            <div class="col-md-4 align-content-center text-right">$0</div>
                            <div class="col-md-9 mb-3">
                                <hr class="mx-4" style="border-top-color: whitesmoke;">
                            </div>
                            <div class="col-md-4 align-content-center font-weight--bold" style="font-size: 20px">Total
                            </div>
                            <div class="col-md-4 align-content-center text-right font-weight--bold mb-5 cart_total"
                                 style="font-size: 20px"></div>
                            <div class="col-sm-8 my-5 row justify-content-center">
                                <a href="/" class="add-to-cart-button btn btn-dark align-content-center text-left mt-5"
                                   id="add_cart">
                                    Go to dashboard &nbsp; &nbsp; &nbsp;<i class="fa fa-long-arrow-right pl-5"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        @include('frontend.' . $active_theme->theme_abrv . '.components.footer')
    </div>
    @include('frontend.' . $active_theme->theme_abrv . '.components.login-modal')
@endsection

@section('styles')
<style>
    .muted-bd-fields{
        opacity: 0.4;
        pointer-events: none;
        cursor: not-allowed;
    }
</style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {

            var subtotal = parseFloat($(".section_2_subtotal").text().replace('$', " "));
            var shippingCharges = parseFloat($(".section_2_shipping_charges").text().replace('$', ''));
            var total = subtotal + shippingCharges;
            $(".section_2_cart_total").text("$" + total.toFixed(2));

            $('.delete-row').click(function () {
                $(this).closest('tr').remove();
            });

            function updateTotalPrice() {
                var quantity = parseInt($('#item_qty').val());
                var itemPrice = parseFloat("{{ $item->item_price }}"); // Assuming item_price is a numeric value
                var total = quantity * itemPrice;
                $('#item_total_price').text(total); // Adjust decimal places as needed
                $('#item_subtotal_price').text(total); // Adjust decimal places as needed
            }

            // Event listener for quantity change
            $('#item_qty').on('change', function () {
                updateTotalPrice();
                var $hiddenInput = $(this).closest('.qty-styles').find('.item_id');
                $('#item_ids').val($hiddenInput);
            });

            // Event listener for minus button
            $('.qty-minus').on('click', function () {
                if ($('#update_cart').is(':disabled')) {
                    $('#update_cart').removeAttr('disabled');
                }
                var currentValue = parseInt($('#item_qty').val());
                if (currentValue > 1) {
                    $('#item_qty').val(currentValue - 1);
                    updateTotalPrice();
                }
                var $row = $(this).closest('tr');
                var $itemIdInput = $row.find('.item_id');
                var itemId = $itemIdInput.val();
                console.log(itemId);
                appendItemId(itemId, currentValue - 1);
            });

            // Event listener for plus button
            $('.qty-add').on('click', function () {
                if ($('#update_cart').is(':disabled')) {
                    $('#update_cart').removeAttr('disabled');
                }
                var currentValue = parseInt($('#item_qty').val());
                $('#item_qty').val(currentValue + 1);
                updateTotalPrice();
                var $row = $(this).closest('tr');
                var $itemIdInput = $row.find('.item_id');
                var itemId = $itemIdInput.val();
                console.log(itemId);
                appendItemId(itemId, currentValue + 1);
            });

            function appendItemId(itemId, currentValue) {
                var itemIdsArray = JSON.parse($('#item_ids').val() || '[]');
                var quantityArray = JSON.parse($('#quantities').val() || '[]');
                var index = itemIdsArray.indexOf(itemId);
                if (index === -1) {
                    itemIdsArray.push(itemId);
                    quantityArray.push(currentValue);
                } else {
                    quantityArray[index] = currentValue;
                    console.log('Item ID ' + itemId + ' already exists in the array. Updated quantity.');
                }
                $('#item_ids').val(JSON.stringify(itemIdsArray));
                $('#quantities').val(JSON.stringify(quantityArray));

                console.log(itemIdsArray);
                console.log(quantityArray);
            }

            function updateTotal() {
                var subtotal = parseFloat($("#item_subtotal_price").text());
                var shippingCharges = parseFloat($(".shipping_charges").text().replace('$', ''));
                var total = subtotal + shippingCharges;
                $(".cart_total").text("$" + total.toFixed(2));
            }

            updateTotal();
            $("#item_subtotal_price").on("DOMSubtreeModified", function () {
                updateTotal();
            });

            $('#update_cart').on('click', function () {
                if ($(this).is(':disabled')) {
                    return;
                }

                $.ajax({
                    url: '{{ route('frontend.cart.blupdate') }}',
                    method: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        "itemID": $('#item_ids').val(),
                        "quantity": $('#quantities').val(),
                        "CustomerId": $('#customer_id').val()
                    },
                    success: function (response) {
                        location.reload();
                        console.log(response);
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    }
                });
            });

            $('#proceed_to_checkout').click(function () {
                $(".customer-addr-select").prop("checked", true);
                $(".disable-toggle").addClass("muted-bd-fields");
                $(".disable-toggle").removeAttr("required");
                $('.stepper-heading').text('Checkout');
                $('.section-2').addClass('active');
                $('#section1').attr('style', 'display:none;');
                $('#section3').attr('style', 'display:none;');
                $('#section2').attr('style', 'display:block;');
            });

            $('#place_order').click(function () {
                var form = $('#customer_info')[0];
                if((form.checkValidity())){

                    var formData = $('#customer_info').serialize();
                 //   console.log('form data', formData);
                    $.ajax({
                       url: '{{route("frontend.checkout.place_order")}}',
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            $('#orderno').text('');
                            var spanText = response.msg.match(/\[\s*(\d+)\s*\]/)[1];
                            $('#orderno').text(spanText);

                            $('.stepper-heading').text('Order Complete');
                            $('.section-3').addClass('active');
                            $('#section1').attr('style', 'display:none;');
                            $('#section2').attr('style', 'display:none;');
                            $('#section3').attr('style', 'display:block;');
                            $('.badge.badge-pill.badge-primary.position-absolute.cartCount').text('0');

                            $.ajax({
                                url: "{{ route('delete-cart-items') }}",
                                type: "GET",
                                success: function (response) {
                                    if (response) {
                                        console.log('cart empty is del', response);
                                    } else {
                                        toastr.error('Someting went wrong while empty the cart after place order.', {
                                            hideDuration: 10000,
                                            closeButton: true,
                                        });
                                    }
                                }
                            })
                        } else {
                            toastr.error(response.msg, {
                                hideDuration: 10000,
                                closeButton: true,
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Form submission error:', error);
                    }
                });
            }else{
                alert("Please fill all the required fields");
            }
            });
            $('.datepicker').datepicker({
                format: "yyyy-mm-dd",
                startDate: "-10Y",
                maxViewMode: 3,
                todayBtn: "linked",
                clearBtn: false,
                keyboardNavigation: false,
                autoclose: true,
                todayHighlight: true,
                toggleActive: true
            });

            $(document).on('click', 'input[name="shipping-address"]', function() {
                var addressValue = $(this).val();
                if (addressValue == 'existing-address') {
                    $(".disable-toggle").addClass("muted-bd-fields");
                    $(".disable-toggle").removeAttr("required");
                } else {
                    $(".disable-toggle").removeClass("muted-bd-fields");
                    $(".hidden-inp").val("");
                    $(".disable-toggle").attr("required", true);
                    $("#bd-address2").removeAttr("required")
                }
            });


            $('.select-address').on('change', function() {
                var address = JSON.parse($('.hidden-inp').val());
                if(address != undefined){
                    $('.new-address').val(address);
                }
            });

        });
    </script>
@endsection

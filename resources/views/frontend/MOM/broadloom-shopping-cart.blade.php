
@php
    // $active_theme object is available containing the theme developer json loaded.
    // This is for the theme developers who want to load further view assets

    use App\Http\Controllers\ConstantsController;
    use App\Http\Controllers\CommonController;
    $quoteCartData = $quote_cart_data ?? [];

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
            <input type="hidden" name="customer-country" id="customer_country" value="{{ $cust_country }}">
            <input type="hidden" name="customer-state" id="customer_state" value="{{ $cust_state }}">
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
                                    @if((count((array) $cart->items)) || !empty($quoteCartData))
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
                                                <th>Product X</th>
                                                @php
                                                $show_quantity = false;
                                                if (!empty($quoteCartData)) {
                                                    foreach ($quoteCartData as $item) {
                                                        if(!$item->broadloom_item) {
                                                            $show_quantity = true;
                                                        }
                                                    }
                                                } else {
                                                    foreach ($cart->items as $item) {
                                                        if(!$item->broadloom_item) {
                                                            $show_quantity = true;
                                                        }
                                                    }
                                                }
                                                
                                                @endphp
                                                @if($show_quantity)
                                                <th>Quantity {{ $show_quantity }}</th>
                                                @endif
                                                <th>Cut Cost</th>
                                                <th>Serging Cost</th>
                                                <th>Rug Pad</th>
                                                <th>Sub Total</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if (count((array) $cart->items) || !empty($quoteCartData))
                                                @php  $subPriceTotal = 0;  $priceTotal = 0; $sergingTotal = 0;  $cuttingTotal = 0; $rugPadTotal = 0;  @endphp
                                                @if(empty($quoteCartData))
                                                    @foreach ($cart->items as $item)
                                                        @php
                                                            if (isset($item->item_data) && $item->item_data) {
                                                                $item_data = json_decode(unserialize($item -> item_data));
                                                            }
                                                            $sum_surging_charges = 0;
                                                            $serging_charges = 0;
                                                        @endphp
                                                        @if($item->is_bd_child != 1)
                                                        <tr>
                                                            <th class="" scope="row">
                                                                <div class="row">
                                                                    <div
                                                                        class="col-1 justify-content-center align-content-center delete-row"
                                                                        style="color: red;cursor: pointer;"
                                                                        onclick="removeItemFromCart('{{$item -> item_id}}','{{csrf_token()}}','{{$item -> item_customer_id}}', '{{$item->broadloom_item}}', '{{$item->bd_roll_id}}', '{{$item->rand_str}}')">
                                                                        x
                                                                    </div>
                                                                    <div class="col-3"><img
                                                                            src={{ CommonController::getApiFullImage($item_data->ImageName) }}
                                                                    alt="{{ $item_data->ItemID }}" height="80px"
                                                                            width="80px"
                                                                            onerror="this.onerror=null; this.src='{{url('/').ConstantsController::SPARS_LOGO}}'"
                                                                        >
                                                                    </div>
                                                                    <div class="col-lg-8 col-md-12 col-sm-12 ps-5 mobile-mode-bd-cart" style="font-size: 12px">
                                                                        <div class=" mt-2 font-weight--bold row">Design:<p
                                                                                class="font-weight--normal d-flex flex-wrap mx-1">
                                                                                {{ Str::after($item_data->ItemName, 'DESIGN: ') }}  {{substr($item_data->ColorID, 0, 3)}}
                                                                                <span class="cfa-rem {{$item->cfa != 1 ? 'd-none' : ''}}">CFA Required</span>
                                                                                <span class="cfa-rem {{$item->remnant_shipable != 1 ? 'd-none' : ''}}">Remnant Required</span> </p>
                                                                        </div>
                                                                        <div class=" mt-2 row">Roll Id: <p
                                                                                class="font-weight--normal mx-2">{{ $item_data->RollID }}</p>
                                                                        </div>
                                                                        <div class=" mt-2 row" style="display:flex; align-items: center;">
                                                                                <div class="col-md-2 col-lg-2 p-0"> Sizes:</div>
                                                                                <div class="col-md-10 col-lg-10 p-0" style="align-items: center;">
                                                                                    @php
                                                                                    $sizes = json_decode( unserialize($item->item_data ), true );
                                                                                    $sum_surging_charges = 0;
                                                                                    //$sizes = json_decode($item->item_data, true );
                                                                                    @endphp
                                                                                    @foreach($sizes['CutPieces'] as $key=>$item_sizes)
                                                                                        @php
                                                                                            $lenght_feet =  (int)floor($item_sizes['ATSLength'] / 12);
                                                                                            $width_feet =  (int)floor($item_sizes['ATSWidth'] / 12);
                                                                                            $lenght_inch =  $item_sizes['ATSLength'] % 12;
                                                                                            $width_inch =   $item_sizes['ATSWidth'] % 12;
                                                                                            if (!empty($item_sizes['SergingType'])) {
                                                                                                $serging_charges = 0;
                                                                                            // $cut_piece_serging_charges = (($lenght_feet + $width_feet) * 2) * $item_sizes['SergingCharges'];
                                                                                                $cut_piece_serging_charges = ((($lenght_feet * 12 + $lenght_inch) + ($width_feet * 12 + $width_inch)) * 2 / 12) * $item_sizes['SergingCharges'];
                                                                                                $serging_charges += $cut_piece_serging_charges;
                                                                                                $sum_surging_charges += $serging_charges;
                                                                                            }
                                                                                        @endphp
                                                                                        <div
                                                                                            class="mytooltip badge badge-default broadloom-badge side-bar-broadloom-badge"
                                                                                            style="margin:2px 2px !important;background: @if($item_sizes['LengthStatus'] == 'F') blue @else #660000 @endif">
                                                                                            {{ $width_feet . "'" . $width_inch . "\"" . " x " . $lenght_feet  . "'" . $lenght_inch . "\"" }}
                                                                                            @if(!empty($item_sizes['SergingType']))
                                                                                                <span
                                                                                                    class="tooltiptext">
                                                                                                    {{-- <strong>Serging Rate: ${{ number_format($item_sizes['SergingCharges'], ConstantsController::ALLOWED_DECIMALS) }}</strong> --}}
                                                                                                    <strong>Serging Charges: {{ $item->item_currency }}{{ number_format($serging_charges, 2) }}</strong>
                                                                                                </span>
                                                                                            @endif
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            @if(!$item->broadloom_item)
                                                                <td class="align-content-center">
                                                                    <div class="d-flex flex-row qty-styles mb-2">
                                                                        <a href="javascript:void(0);"
                                                                        class="qty-minus qty-action">
                                                                            -
                                                                        </a>
                                                                        <input type="number" id="item_qty" name="quantity"
                                                                            autocomplete="off"
                                                                            onkeydown="if(this.key==='.'){this.preventDefault();}"
                                                                            class="form-control" min="1" max="9999"
                                                                            maxlength="4" step="1" required
                                                                            value="{{ $item->item_quantity }}"/>
                                                                        <a href="javascript:void(0);"
                                                                        class="qty-add qty-action"> +
                                                                        </a>
                                                                        <input type="hidden" class="item_id" name="item_id"
                                                                            value="{{ $item_data->ItemID }}">
                                                                    </div>
                                                                </td>
                                                            @endif
                                                            <td class="align-content-center">
                                                                @php
                                                                    $priceTotal += $item->item_price;
                                                                    number_format($priceTotal, 2);
                                                                @endphp
                                                                {{ $item->item_currency }}{{ number_format($item->item_price, 2) }}</td>
                                                            <td class="align-content-center">
                                                                @php
                                                                    $sergingTotal += $sum_surging_charges;
                                                                    $cuttingTotal += $item->unit_price;
                                                                @endphp
                                                                {{ $item->item_currency }}{{ number_format($sum_surging_charges + $item->unit_price, 2) }}
                                                            </td>
                                                            <td class="align-content-center">
                                                                @php
                                                                    $rugPadTotal += $item->rugpad_price;
                                                                @endphp
                                                                {{ $item->item_currency }}{{ number_format($item->rugpad_price, 2) }}
                                                            </td>
                                                            <td class="align-content-center">{{ $item->item_currency }}<span
                                                                    id="item_total_price">{{ number_format($sum_surging_charges + $item->rugpad_price + $item->unit_price + $item->item_total, 2)  }}
                                                                @php
                                                                    $subPriceTotal += $item->item_price + $item->rugpad_price; // $sum_surging_charges + $item->unit_price + $item->item_total
                                                                @endphp
                                                            </span>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    @foreach ($quoteCartData as $item)
                                                        @php
                                                            if (isset($item->item_data) && $item->item_data) {
                                                                $item_data = json_decode(unserialize($item -> item_data));
                                                            }
                                                            $sum_surging_charges = 0;
                                                            $serging_charges = 0;
                                                        @endphp
                                                        @if($item->is_bd_child != 1)
                                                        <tr>
                                                            <th class="" scope="row">
                                                                <div class="row">
                                                                    <div
                                                                        class="col-1 justify-content-center align-content-center delete-row"
                                                                        style="color: red;cursor: pointer; visibility: hidden;"
                                                                        onclick="removeItemFromCart('{{$item -> item_id}}','{{csrf_token()}}','{{$item -> item_customer_id}}', '{{$item->broadloom_item}}', '{{$item->bd_roll_id}}', '{{$item->rand_str}}')">
                                                                        x
                                                                    </div>
                                                                    <div class="col-3"><img
                                                                            src={{ CommonController::getApiFullImage($item_data->ImageName) }}
                                                                    alt="{{ $item_data->ItemID }}" height="80px"
                                                                            width="80px"
                                                                            onerror="this.onerror=null; this.src='{{url('/').ConstantsController::SPARS_LOGO}}'"
                                                                        >
                                                                    </div>
                                                                    <div class="col-lg-8 col-md-12 col-sm-12 ps-5 mobile-mode-bd-cart" style="font-size: 12px">
                                                                        <div class=" mt-2 font-weight--bold row">Design: <p
                                                                                class="font-weight--normal d-flex flex-wrap mx-1">
                                                                                {{ Str::after($item_data->ItemName, 'DESIGN: ') }} {{substr($item_data->ColorID, 0, 3)}}
                                                                                <span class="cfa-rem {{$item->cfa != 1 ? 'd-none' : ''}}">CFA Required</span>
                                                                                <span class="cfa-rem {{$item->remnant_shipable != 1 ? 'd-none' : ''}}">Remnant Required</span> </p>
                                                                        </div>
                                                                        <div class=" mt-2 row">Roll Id: <p
                                                                                class="font-weight--normal mx-2">{{ $item_data->RollID }}</p>
                                                                        </div>
                                                                        <div class=" mt-2 row" style="display:flex; align-items: center;">
                                                                                <div class="col-md-2 col-lg-2 p-0"> Sizes:</div>
                                                                                <div class="col-md-10 col-lg-10 p-0" style="align-items: center;">
                                                                                    @php
                                                                                    $sizes = json_decode( unserialize($item->item_data ), true );
                                                                                    $sum_surging_charges = 0;
                                                                                    //$sizes = json_decode($item->item_data, true );
                                                                                    @endphp
                                                                                    @foreach($sizes['CutPieces'] as $key=>$item_sizes)
                                                                                        @php
                                                                                            $lenght_feet =  (int)floor($item_sizes['ATSLength'] / 12);
                                                                                            $width_feet =  (int)floor($item_sizes['ATSWidth'] / 12);
                                                                                            $lenght_inch =  $item_sizes['ATSLength'] % 12;
                                                                                            $width_inch =   $item_sizes['ATSWidth'] % 12;
                                                                                            if (!empty($item_sizes['SergingType'])) {
                                                                                                $serging_charges = 0;
                                                                                            // $cut_piece_serging_charges = (($lenght_feet + $width_feet) * 2) * $item_sizes['SergingCharges'];
                                                                                                $cut_piece_serging_charges = ((($lenght_feet * 12 + $lenght_inch) + ($width_feet * 12 + $width_inch)) * 2 / 12) * $item_sizes['SergingCharges'];
                                                                                                $serging_charges += $cut_piece_serging_charges;
                                                                                                $sum_surging_charges += $serging_charges;
                                                                                            }
                                                                                        @endphp
                                                                                        <div
                                                                                            class="mytooltip badge badge-default broadloom-badge side-bar-broadloom-badge"
                                                                                            style="margin:2px 2px !important;background: @if($item_sizes['LengthStatus'] == 'F') blue @else #660000 @endif">
                                                                                            {{ $width_feet . "'" . $width_inch . "\"" . " x " . $lenght_feet  . "'" . $lenght_inch . "\"" }}
                                                                                            @if(!empty($item_sizes['SergingType']))
                                                                                                <span
                                                                                                    class="tooltiptext">
                                                                                                    {{-- <strong>Serging Rate: ${{ number_format($item_sizes['SergingCharges'], ConstantsController::ALLOWED_DECIMALS) }}</strong> --}}
                                                                                                    <strong>Serging Charges: {{ $item->item_currency }}{{ number_format($serging_charges, 2) }}</strong>
                                                                                                </span>
                                                                                            @endif
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            @if(!$item->broadloom_item)
                                                                <td class="align-content-center">
                                                                    <div class="d-flex flex-row qty-styles mb-2">
                                                                        <a href="javascript:void(0);"
                                                                        class="qty-minus qty-action">
                                                                            -
                                                                        </a>
                                                                        <input type="number" id="item_qty" name="quantity"
                                                                            autocomplete="off"
                                                                            onkeydown="if(this.key==='.'){this.preventDefault();}"
                                                                            class="form-control" min="1" max="9999"
                                                                            maxlength="4" step="1" required
                                                                            value="{{ $item->item_quantity }}"/>
                                                                        <a href="javascript:void(0);"
                                                                        class="qty-add qty-action"> +
                                                                        </a>
                                                                        <input type="hidden" class="item_id" name="item_id"
                                                                            value="{{ $item_data->ItemID }}">
                                                                    </div>
                                                                </td>
                                                            @endif
                                                            <td class="align-content-center">
                                                                @php
                                                                    $priceTotal += (float)$item->item_price;
                                                                    number_format($priceTotal, 2);
                                                                @endphp
                                                                {{ $item->item_currency }}{{ number_format((float)$item->item_price, 2) }}</td>
                                                            <td class="align-content-center">
                                                                @php
                                                                    $sergingTotal += $sum_surging_charges;
                                                                    $cuttingTotal += (float)$item->unit_price;
                                                                @endphp
                                                                {{ $item->item_currency }}{{ number_format((float)$sum_surging_charges + (float)$item->unit_price, 2) }}
                                                            </td>
                                                            <td class="align-content-center">
                                                                @php
                                                                    $rugPadTotal += $item->rugpad_price;
                                                                @endphp
                                                                {{ $item->item_currency }}{{ number_format($item->rugpad_price, 2) }}
                                                            </td>
                                                            <td class="align-content-center">{{ $item->item_currency }}<span
                                                                    id="item_total_price">{{ number_format($sum_surging_charges + $item->rugpad_price + $item->unit_price + $item->item_total, 2)  }}
                                                                @php
                                                                    $subPriceTotal += $item->item_price + $item->rugpad_price; // $sum_surging_charges + $item->unit_price + $item->item_total
                                                                @endphp
                                                            </span>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                @endif
                                                <input type="hidden" id="sergingTotal" value="{{ number_format($sergingTotal, 2) }}">
                                                <input type="hidden" id="cuttingTotal" value="{{ number_format($cuttingTotal, 2) }}">
                                                <input type="hidden" name="inside-hidden-subtotal" id="inside-hidden-subtotal" value="{{ number_format( $subPriceTotal, 2)}}">
                                                <input type="hidden" name="quote-cart-data" id="quote-cart-data" value="{{ json_encode($quoteCartData) }}">
                                                <input type="hidden" name="quote-no" id="quote-no" value="{{ !empty($quoteCartData) ? $quoteCartData[0]->quotation_no : "" }}">
                                            @else
                                                <tr>
                                                    No Item in Cart
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="d-flex flex-row p-5 align-items-center">
                                            <div class="col-md-12">
                                                <h2 class="text-muted text-center mt-5 mb-3 emptyCart"> Cart is
                                                    empty! </h2>
                                            </div>
                                        </div>
                                        <div class="bottom-0 col p-4 row mt-5">
                                            <a href="{{ url('/') }}" class="btn btn--border_1 col mt-3">BACK TO SHOP</a>
                                        </div>
                                    @endif
                                    <div class="mt-4 d-flex justify-content-end mx-5">
                                        <button href="#" class=" btn btn-dark align-content-center d-none"
                                                id="update_cart"
                                                disabled="disabled">
                                            Update Cart
                                        </button>
                                    </div>

                                </div>
                            </div>
                            @if((count((array) $cart->items)) || !empty($quoteCartData))

                                <div class="col-md-3 col-sm-12 border">
                                    <div class="d-flex justify-content-around align-items-left flex-column">
                                        <p class="mt-2 mb-2 text-center fa-2x">Cart Totals</p>
                                        <div class="row mt-3">
                                            <div class="col-md-7">Cut Cost:</div>
                                            <div class="col-md-5 text-right"><span id="item_subtotal_price" class="cart_total">{{ $cart->cart_currency }}{{ number_format($priceTotal, 2) }}</span></div>
                                        </div>
                                        <hr style="border-top-color: whitesmoke;">
                                        <div class="row">
                                            <div class="col-md-7">Serging Cost:</div>
                                            <div class="col-md-5 text-right serging_charges">{{ $cart->cart_currency }}{{ number_format($sergingTotal + $cuttingTotal, 2) }}</div>
                                        </div>
                                        <hr style="border-top-color: whitesmoke;">
                                        <div class="row">
                                            <div class="col-md-7">Rug Pad:</div>
                                            <div class="col-md-5 text-right rugpad_charges">{{ $cart->cart_currency }}{{ number_format($rugPadTotal, 2) }}</div>
                                        </div>
                                        <hr style="border-top-color: whitesmoke;">
                                        <div class="row">
                                            <div class="col-md-3">Shipping Charges:</div>
                                            <div class="col-md-9 text-right shipping_charges">will be calculated at time of Shipping
                                            </div>
                                            {{-- <div class="col-md-5 text-right shipping_charges">$0.00</div> --}}
                                        </div>
                                        <hr style="border-top-color: whitesmoke;">
                                        <div class="row mt-3">
                                            <div class="col-md-6 font-weight-bold">Total:</div>
                                            <div class="col-md-6 font-weight-bold text-right cart_total_final"></div>
                                        </div>
                                        <btn class="add-to-cart-button text-left btn btn-dark col-md-12 mt-3 mb-3"
                                             id="proceed_to_checkout">
                                            Proceed to Checkout <i class="px-4 fa fa-long-arrow-right"></i>
                                            </button>
                                    </div>
                                </div>
                            @endif
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
                                    @if(isset($shipping_addresses['ShipToAddresses']))
                                        <form class="needs-validation" id="customer_info" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-10 mb-2">
                                                    <div class="d-flex">
                                                        <input type="radio" name="shipping-address"
                                                               class="existing-address customer-addr-select"
                                                               id="existing-address" value="existing-address"/>
                                                        <select class="p-0 m-0" class="select-address"
                                                                id="select-address"
                                                                style="height:40px !important; line-height: 20px !important; padding: 0.375rem 1rem !important;">

                                                            @foreach($shipping_addresses['ShipToAddresses'] as $address)
                                                                <option
                                                                    value="{{$address['AddressID']}}~~~{{json_encode($address)}}">
                                                                    {{$address['AddressID']}}
                                                                    : {!!$address['FirstName'] ? $address['FirstName'] . ( $address['LastName'] ? " {$address['LastName']}" : '' ) : ''!!}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <p style="display: none !important;"
                                                           class="card-text address-card d-none {{$address['AddressID']}}"
                                                           id="{{$address['AddressID']}}">
                                                            <input type="hidden" class="hidden-inp"
                                                                   name="shipping-address-data"
                                                                   value="{{json_encode($address)}}"/>
                                                        </p>
                                                        <p style="display: none !important">
                                                            <input type="hidden" id="hidden-address_id" name="AddressID"
                                                                   value="{{$shipping_addresses['ShipToAddresses'][0]['AddressID']}}"/>
                                                        </p>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-md-3 mb-2">
                                                   <div class="d-flex">
                                                    <input type="radio" name="shipping-address" class="existing-address" id="other-address" value="other"/>
                                                    <label for="other" class="mt-2">Dropship</label>
                                                   </div>
                                                </div> --}}
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3 mb-2">
                                                    <div class="d-flex">
                                                        <input type="radio" name="shipping-address"
                                                               class="existing-address" id="other-address"
                                                               value="other"/>
                                                        <label for="other" class="mt-2">Dropship</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5 mb-2">
                                                    <label for="" class="form-label mb-0" style="font-size: 14px">First
                                                        Name
                                                        <span class="text-danger"
                                                              style="font-size: 18px">*</span></label>
                                                    <input class="form-control disable-toggle" type="text" id=""
                                                           name="FirstName"
                                                           placeholder=""
                                                           value="{{$shipping_addresses['ShipToAddresses'][0]['FirstName']}}"
                                                           required>
                                                </div>
                                                <div class="col-md-5 mb-2">
                                                    <label for="" class="form-label mb-0" style="font-size: 14px">Last
                                                        Name</label>
                                                    <input class="form-control disable-toggle" type="text" id=""
                                                           name="LastName"
                                                           placeholder=""
                                                           value="{{$shipping_addresses['ShipToAddresses'][0]['LastName']}}"
                                                           required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5 mb-2">
                                                    <label for="" class="form-label mb-0"
                                                           style="font-size: 14px">Email</label>
                                                    <input class="form-control disable-toggle" type="email" id=""
                                                           name="Email"
                                                           placeholder=""
                                                           value="{{$shipping_addresses['ShipToAddresses'][0]['Email']}}"
                                                           required>
                                                </div>
                                                <div class="col-md-5 mb-2">
                                                    <label for="" class="form-label mb-0"
                                                           style="font-size: 14px">Phone</label>
                                                    <input class="form-control disable-toggle" type="number" id=""
                                                           name="Phone"
                                                           placeholder=""
                                                           value="{{$shipping_addresses['ShipToAddresses'][0]['Phone1']}}"
                                                           required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-10 mb-2">
                                                    <label for="" class="form-label mb-0" style="font-size: 14px">Street
                                                        Address<span class="text-danger"
                                                                     style="font-size: 18px">*</span></label>
                                                    <input class="form-control disable-toggle new-address" type="text"
                                                           id="" name="Address1"
                                                           placeholder=""
                                                           value="{{$shipping_addresses['ShipToAddresses'][0]['Address1']}}"
                                                           required>
                                                </div>
                                                <div class="col-md-10 mb-2">
                                                    <input class="form-control disable-toggle" type="text"
                                                           id="bd-address2" name="Address2"
                                                           placeholder=""
                                                           value="{{$shipping_addresses['ShipToAddresses'][0]['Address2']}}"
                                                    >
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5 mb-2">
                                                    <label for="" class="form-label mb-0" style="font-size: 14px">Country
                                                        <span class="text-danger" style="font-size: 18px">*</span>
                                                    </label>
                                                    @if (isset($countries))
                                                        <select name="country" id="countries"
                                                                class="form-control bg-white">
                                                            <option value="0">Select a Country</option>
                                                            @foreach ($countries['Countries'] as $row)
                                                                @php
                                                                    $selected = '';
                                                                    if (isset($cust_country) && $cust_country == $row['Description']) {
                                                                        $selected = 'selected';
                                                                    }
                                                                @endphp
                                                                <option value="{{ $row['OriginCode'] }}"
                                                                    origincode="{{ $row['CountryNo'] }}" {{$selected}}>
                                                                {{ $row['Description'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    @elseif (!isset($countries) && isset($cust_country))
                                                        <input type="text" data-required="true" name="country"
                                                               maxlength="30" class="form-control bg-white"
                                                               aria-describedby="Country" value="{{$cust_country}}">
                                                    @else
                                                        <input type="text" data-required="true" name="country"
                                                               maxlength="30" class="form-control bg-white"
                                                               aria-describedby="Country" placeholder="Country*">
                                                    @endif
                                                </div>
                                               
                                                <div class="col-md-5 mb-2">
                                                    <label for="" class="form-label mb-0"
                                                           style="font-size: 14px">State<span class="text-danger"
                                                                                              style="font-size: 18px">*</span></label>
                                                    {{-- <input class="form-control disable-toggle" type="text" id="state" name="State"
                                                           placeholder=""
                                                           value="{{$shipping_addresses['ShipToAddresses'][0]['State']}}"
                                                           required> --}}
                                                    @if(isset($cust_state))
                                                        <select name="State" id="state_dropdown"
                                                                class="form-control bg-white reter checkout-dropdown">
                                                        </select>
                                                    @elseif(!isset($cust_state) && isset($cust_state))
                                                        <input type="text" data-required="true"
                                                               class="form-control bg-white" name="State" maxlength="50"
                                                               aria-describedby="State" value="{{$cust_state}}">
                                                    @else
                                                        <input type="text" data-required="true"
                                                               class="form-control bg-white" name="State" maxlength="50"
                                                               aria-describedby="State" placeholder="State*">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5 mb-2">
                                                    <label for="" class="form-label mb-0" style="font-size: 14px">Town/
                                                        City<span class="text-danger"
                                                                  style="font-size: 18px">*</span></label>
                                                    <input class="form-control disable-toggle" type="text" id=""
                                                           name="City"
                                                           placeholder=""
                                                           value="{{$shipping_addresses['ShipToAddresses'][0]['City']}}"
                                                           required>
                                                </div>
                                                <div class="col-md-5 mb-2">
                                                    <label for="" class="form-label mb-0" style="font-size: 14px">Zip
                                                        Code<span class="text-danger"
                                                                  style="font-size: 18px">*</span></label>
                                                    <input class="form-control disable-toggle" type="text" id=""
                                                           name="Zip"
                                                           placeholder=""
                                                           value="{{$shipping_addresses['ShipToAddresses'][0]['Zip']}}"
                                                           required>
                                                </div>
                                               
                                            </div>
                                            <p class="font-weight--bold " style="font-size: 18px">Additional
                                                Information</p>
                                            <div class="row">
                                                <div class="col-md-5 mb-2 align-content-center">
                                                    <input class="form-check-input" type="checkbox" id=""
                                                           name="ship_complete">
                                                    <label class="form-check-label" for="" style="font-size: 14px">Ship
                                                        Complete</label>
                                                </div>
                                                <div class="col-md-5 mb-2">
                                                    <label for="" class="form-label mb-0" style="font-size: 14px">P.O or
                                                        Reference Number<span class="text-danger"
                                                                              style="font-size: 18px">*</span></label>
                                                    <input class="form-control" type="text" id=""
                                                           name="reference_number"
                                                           placeholder="" value="" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-10 mb-2">
                                                    <label for="" class="form-label mb-1" style="font-size: 14px">Shipping
                                                        Date<span class="text-danger"
                                                        style="font-size: 18px">*</span></label>
                                                    <input class="form-control mb-2 datepicker order-ship-date" type="text"
                                                           id="datepicker" data-date-format="dd-mm-yyyy"
                                                           name="ship_date"
                                                           placeholder="" value="" required>
                                                </div>
                                                <div class="col-md-10 mb-2">
                                                    <label for="" class="form-label mb-1" style="font-size: 14px">Shipping
                                                        Method</label>
                                                    <select name="shipping_method" class="form-control ship-method-select">
                                                        @if($shipping_options)
                                                            @foreach($shipping_options as $shipping_option)
                                                                <option
                                                                    {{ $default_ship_via_id == $shipping_option['ShipViaID'] ? 'selected' : '' }} value="{{$shipping_option['ShipViaID']}}">{{$shipping_option['Description']}}</option>
                                                            @endforeach
                                                        @else
                                                            <option value="3RDP">Standard ShipVia</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-10 mb-2">
                                                    <label for="" class="mb-0" style="font-size: 14px">Order Notes
                                                        (optional)</label>
                                                    <textarea class="form-control" id="ship_instructions" name="shipping_instructions"
                                                              style="height: 7rem;" placeholder="" class="ship_instructions"></textarea>
                                                    
                                                </div>
                                                <input type="hidden" name="item_broadloom" id="item_broadloom"
                                                           value="{{1}}">
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            {{-- --}}
                            @if(isset($cart->items))
                                <div class="col-md-6 col-sm-12 my-5">
                                    <div style="background-color: whitesmoke;">
                                        <div class="d-flex justify-content-around align-items-left flex-column">
                                            <p class="mt-2 mb-2 text-center fa-2x">Your Order</p>
                                            <div class="row mt-3 px-5">
                                                <div class="col-md-6">Product</div>
                                                <div class="col-md-6 text-right">SubTotal</div>
                                            </div>
                                            <hr class="mx-4" style="border-top-color: rgb(161, 161, 161);">

                                            @php $total_price=0; @endphp
                                            @if(empty($quoteCartData))
                                                @foreach ($cart->items as $item)
                                                    @if($item->is_bd_child != 1)
                                                    @php
                                                        if (isset($item->item_data) && $item->item_data) {
                                                            $item_data = json_decode(unserialize($item -> item_data));
                                                            //$item_data = json_decode($item -> item_data);
                                                        }
                                                        $total_price += $item->item_price;
                                                        $sum_surging_charges=0; $sergingTotal=0;
                                                    @endphp
                                                        <div class="row px-5">
                                                            <div class="col-md-10">
                                                                <div class="row">
                                                                    <div class="col-3"><img
                                                                            src="{{ CommonController::getApiFullImage($item_data->ImageName) }}"
                                                                            alt="{{$item_data->ItemID}}" height="50px"
                                                                            width="80px"
                                                                            onerror="this.onerror=null; this.src='{{url('/').ConstantsController::SPARS_LOGO}}'">
                                                                    </div>
                                                                    @php
                                                                        $decodedData = json_decode(unserialize($item->item_data), true);
                                                                        $colorID = substr($decodedData['ColorID'], 0, 3);
                                                                    @endphp
                                                                    <div class="col-9" style="font-size: 12px;">
                                                                        <div class="mx-3 mt-2 font-weight--bold row">Design: <p
                                                                                class="font-weight--normal d-flex flex-wrap mx-1">{{ Str::after($item->item_name, 'DESIGN: ') }} {{$colorID}}
                                                                                <span class="cfa-rem {{$item->cfa != 1 ? 'd-none' : ''}}">CFA Required</span>
                                                                                <span class="cfa-rem {{$item->remnant_shipable != 1 ? 'd-none' : ''}}">Remnant Required</span>
                                                                            </p>
                                                                        </div>
                                                                        <div class="mx-3 mt-2 row">Roll Id: <p
                                                                                class="font-weight--normal mx-2">{{$item_data->RollID}}</p>
                                                                        </div>
                                                                        <div class="mx-3 mt-2 row">
                                                                            <div class="row">
                                                                                <div class="col-md-2">Sizes:</div>
                                                                                <div class="col-md-10">
                                                                                    @php
                                                                                    $sizes = json_decode( unserialize($item->item_data ), true );
                                                                                    @endphp
                                                                                    @foreach($sizes['CutPieces'] as $item_sizes)
                                                                                        @php
                                                                                            $lenght_feet =  (int)floor($item_sizes['ATSLength'] / 12);
                                                                                            $width_feet =  (int)floor($item_sizes['ATSWidth'] / 12);
                                                                                            $lenght_inch =  $item_sizes['ATSLength'] % 12;
                                                                                            $width_inch =   $item_sizes['ATSWidth'] % 12;
                                                                                            if (!empty($item_sizes['SergingType'])) {
                                                                                                $serging_charges = 0;
                                                                                            // $cut_piece_serging_charges = (($lenght_feet + $width_feet) * 2) * $item_sizes['SergingCharges'];
                                                                                                $cut_piece_serging_charges = ((($lenght_feet * 12 + $lenght_inch) + ($width_feet * 12 + $width_inch)) * 2 / 12) * $item_sizes['SergingCharges'];
                                                                                                $serging_charges += $cut_piece_serging_charges;
                                                                                                $sum_surging_charges += $serging_charges;
                                                                                            }
                                                                                        @endphp
                                                                                        <div
                                                                                            class="mytooltip badge badge-default broadloom-badge side-bar-broadloom-badge"
                                                                                            style="margin: 2px 1px !important;background: @if($item_sizes['LengthStatus'] == 'F') blue @else #660000 @endif">
                                                                                            {{ $width_feet . "'" . $width_inch . "\"" . " x " . $lenght_feet  . "'" . $lenght_inch . "\"" }}
                                                                                            @if(!empty($item_sizes['SergingType']))
                                                                                                <span
                                                                                                    class="tooltiptext">
                                                                                                    {{-- <strong>Serging Rate: ${{ number_format($item_sizes['SergingCharges'], ConstantsController::ALLOWED_DECIMALS) }}</strong> --}}
                                                                                                    <strong>Serging Charges: {{ $item->item_currency }}{{ number_format($serging_charges, 2) }}</strong>
                                                                                                </span>
                                                                                            @endif
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-md-2 text-right align-content-center">{{$item->item_currency}}{{number_format($item->item_total + $item->unit_price + $sum_surging_charges + $item->rugpad_price,  2)}}</div>
                                                        </div>
                                                        <hr class="mx-4" style="border-top-color: rgb(161, 161, 161);">
                                                    @endif
                                                @endforeach
                                            @else
                                                @foreach ($quoteCartData as $item)
                                                @if($item->is_bd_child != 1)
                                                    @php
                                                        if (isset($item->item_data) && $item->item_data) {
                                                            $item_data = json_decode(unserialize($item -> item_data));
                                                            //$item_data = json_decode($item -> item_data);
                                                        }
                                                        $total_price += $item->item_price;
                                                        $sum_surging_charges=0; $sergingTotal=0;
                                                    @endphp
                                                    <div class="row px-5">
                                                        <div class="col-md-10">
                                                            <div class="row">
                                                                <div class="col-3"><img
                                                                        src="{{ CommonController::getApiFullImage($item_data->ImageName) }}"
                                                                        alt="{{$item_data->ItemID}}" height="50px"
                                                                        width="80px"
                                                                        onerror="this.onerror=null; this.src='{{url('/').ConstantsController::SPARS_LOGO}}'">
                                                                </div>
                                                                @php
                                                                    $decodedData = json_decode(unserialize($item->item_data), true);
                                                                    $colorID = substr($decodedData['ColorID'], 0, 3);
                                                                @endphp
                                                                <div class="col-9" style="font-size: 12px;">
                                                                    <div class="mx-3 mt-2 font-weight--bold row">Design: <p
                                                                            class="font-weight--normal d-flex flex-wrap mx-1">{{ Str::after($item->item_name, 'DESIGN: ') }} {{$colorID}}
                                                                            <span class="cfa-rem {{$item->cfa != 1 ? 'd-none' : ''}}">CFA Required</span>
                                                                            <span class="cfa-rem {{$item->remnant_shipable != 1 ? 'd-none' : ''}}">Remnant Required</span>
                                                                        </p>
                                                                    </div>
                                                                    <div class="mx-3 mt-2 row">Roll Id: <p
                                                                            class="font-weight--normal mx-2">{{$item_data->RollID}}</p>
                                                                    </div>
                                                                    <div class="mx-3 mt-2 row">
                                                                        <div class="row">
                                                                            <div class="col-md-2">Sizes:</div>
                                                                            <div class="col-md-10">
                                                                                @php
                                                                                $sizes = json_decode( unserialize($item->item_data ), true );
                                                                                @endphp
                                                                                @foreach($sizes['CutPieces'] as $item_sizes)
                                                                                    @php
                                                                                        $lenght_feet =  (int)floor($item_sizes['ATSLength'] / 12);
                                                                                        $width_feet =  (int)floor($item_sizes['ATSWidth'] / 12);
                                                                                        $lenght_inch =  $item_sizes['ATSLength'] % 12;
                                                                                        $width_inch =   $item_sizes['ATSWidth'] % 12;
                                                                                        if (!empty($item_sizes['SergingType'])) {
                                                                                            $serging_charges = 0;
                                                                                        // $cut_piece_serging_charges = (($lenght_feet + $width_feet) * 2) * $item_sizes['SergingCharges'];
                                                                                            $cut_piece_serging_charges = ((($lenght_feet * 12 + $lenght_inch) + ($width_feet * 12 + $width_inch)) * 2 / 12) * $item_sizes['SergingCharges'];
                                                                                            $serging_charges += $cut_piece_serging_charges;
                                                                                            $sum_surging_charges += $serging_charges;
                                                                                        }
                                                                                    @endphp
                                                                                    <div
                                                                                        class="mytooltip badge badge-default broadloom-badge side-bar-broadloom-badge"
                                                                                        style="margin: 2px 1px !important;background: @if($item_sizes['LengthStatus'] == 'F') blue @else #660000 @endif">
                                                                                        {{ $width_feet . "'" . $width_inch . "\"" . " x " . $lenght_feet  . "'" . $lenght_inch . "\"" }}
                                                                                        @if(!empty($item_sizes['SergingType']))
                                                                                            <span
                                                                                                class="tooltiptext">
                                                                                                {{-- <strong>Serging Rate: ${{ number_format($item_sizes['SergingCharges'], ConstantsController::ALLOWED_DECIMALS) }}</strong> --}}
                                                                                                <strong>Serging Charges: {{ $item->item_currency }}{{ number_format($serging_charges, 2) }}</strong>
                                                                                            </span>
                                                                                        @endif
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="col-md-2 text-right align-content-center">{{$item->item_currency}}{{number_format($item->item_total + $item->unit_price + $sum_surging_charges + $item->rugpad_price,  2)}}</div>
                                                    </div>
                                                    <hr class="mx-4" style="border-top-color: rgb(161, 161, 161);">
                                                @endif
                                                @endforeach
                                            @endif
                                            @if(isset($item))
                                                <div class="row px-5">
                                                    <div class="col-md-6 font-weight-bold">Cut Cost</div>

                                                    <div
                                                        class="col-md-6 font-weight-bold text-right section_2_subtotal">{{$item->item_currency}}{{$total_price}}</div>
                                                </div>
                                            @endif
                                            <hr class="mx-4" style="border-top-color: rgb(161, 161, 161);">
                                            <div class="row px-5">
                                                <div class="col-md-9">Serging Cost</div>
                                                <div class="col-md-3 text-right section_2_serging_charges">$0.00</div>
                                            </div>
                                            <hr class="mx-4" style="border-top-color: rgb(161, 161, 161);">
                                            <div class="row px-5">
                                                <div class="col-md-9">Rug Pad</div>
                                                <div class="col-md-3 text-right section_2_rugpad_charges">$0.00</div>
                                            </div>
                                            <hr class="mx-4" style="border-top-color: rgb(161, 161, 161);">
                                            <div class="row px-5">
                                                <div class="col-md-4">Shipping Charges</div>
                                                <div class="col-md-8 text-right section_2_shipping_charges">will be calculated at time of Shipping</div>
                                                {{-- <div class="col-md-3 text-right section_2_shipping_charges">$0.00</div> --}}
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
                                                    {{-- <p class="text-center">Your personal data will be used to process
                                                        your
                                                        order, support your experience throughout this website, and for
                                                        other purposes described in our privacy policy.</p> --}}
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
                            @endif
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
                                    <div class="col p-0">
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
                                    <div class="col p-0">
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
                                    <div class="col p-0">
                                        {{-- <strong class="cart_total">${{$cart->cart_total}}</strong> --}}
                                        <strong class="order_placed_total">${{$cart->cart_total}}</strong>
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
                                @if(empty($quoteCartData))
                                @foreach ( $cart->items as $item)
                                    @if($item->is_bd_child != 1)
                                        <div class="row">
                                            <div class="col-3">
                                                {{-- <img
                                                    src="{{ CommonController::getApiFullImage($item_data->ImageName) }}"
                                                    alt="{{$item_data->ItemID}}" height="80px" width="80px"
                                                    onerror="this.onerror=null; this.src='{{url('/').ConstantsController::SPARS_LOGO}}'"> --}}
                                                <img
                                                    src="{{ CommonController::getApiFullImage($item->item_image) }}"
                                                    alt="{{$item->item_id}}" height="80px" width="80px"
                                                    onerror="this.onerror=null; this.src='{{url('/').ConstantsController::SPARS_LOGO}}'">
                                            </div>
                                            @php
                                                $decodedData = json_decode(unserialize($item->item_data), true);
                                                $colorID = substr($decodedData['ColorID'], 0, 3);
                                            @endphp
                                            <div class="col-9" style="font-size: 12px">
                                                <div class="mx-3 mt-2 font-weight--bold row">Design:
                                                    <p class="font-weight--normal mx-2 d-flex flex-wrap mx-1">{{ Str::after($item->item_name, 'DESIGN: ') }} {{$colorID}}
                                                        <span class="cfa-rem {{$item->cfa != 1 ? 'd-none' : ''}}">CFA Required</span>
                                                        <span class="cfa-rem {{$item->remnant_shipable != 1 ? 'd-none' : ''}}">Remnant Required</span> </p>
                                                </div>
                                                {{-- <div class="mx-3 mt-2 row">SKU: <p class="font-weight--normal mx-2">N/A</p> --}}
                                                <div class="mx-3 mt-2 row">Roll Id: <p
                                                        class="font-weight--normal mx-2">{{ $item->bd_roll_id }}</p>
                                                </div>
                                                <div class="mx-0 mt-2 row">
                                                    <div class="col-2">Size:
                                                    </div>
                                                    @php
                                                        $sizes = json_decode( unserialize($item->item_data ), true );
                                                        //$sizes = json_decode($item->item_data, true );
                                                    @endphp
                                                    <div class="col-md-12 col-lg-8 col-sm-12">

                                                    @foreach($sizes['CutPieces'] as $item_sizes)
                                                        @php
                                                            $lenght_feet =  (int)floor($item_sizes['ATSLength'] / 12);
                                                            $width_feet =  (int)floor($item_sizes['ATSWidth'] / 12);
                                                            $lenght_inch =  $item_sizes['ATSLength'] % 12;
                                                            $width_inch =   $item_sizes['ATSWidth'] % 12;
                                                        @endphp
                                                        <div
                                                            class="mytooltip badge badge-default broadloom-badge side-bar-broadloom-badge"
                                                            style="margin: 2px 2px !important;background: @if($item_sizes['LengthStatus'] == 'F') blue @else #660000 @endif">
                                                            {{ $width_feet . "'" . $width_inch . "\"" . " x " . $lenght_feet  . "'" . $lenght_inch . "\"" }}
                                                            @if(!empty($item_sizes['SergingType']))
                                                                <span
                                                                    class="tooltiptext">
                                                                        {{-- <strong>Serging Charges: ${{ number_format($item_sizes['SergingCharges'], ConstantsController::ALLOWED_DECIMALS) }}</strong> --}}
                                                                    {{-- <strong>Serging Charges::: ${{ number_format($item_sizes['SergingCharges'],2) }}</strong> --}}
                                                                <strong>Serging Charges: {{ $item->item_currency }}{{ number_format($serging_charges, 2) }}</strong>

                                                                </span>
                                                            @endif
                                                        </div>

                                                    @endforeach
                                                </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                @else
                                @foreach ($quoteCartData as $item)
                                    @if($item->is_bd_child != 1)
                                        <div class="row">
                                            <div class="col-3">
                                                {{-- <img
                                                    src="{{ CommonController::getApiFullImage($item_data->ImageName) }}"
                                                    alt="{{$item_data->ItemID}}" height="80px" width="80px"
                                                    onerror="this.onerror=null; this.src='{{url('/').ConstantsController::SPARS_LOGO}}'"> --}}
                                                <img
                                                    src="{{ CommonController::getApiFullImage($item->item_image) }}"
                                                    alt="{{$item->item_id}}" height="80px" width="80px"
                                                    onerror="this.onerror=null; this.src='{{url('/').ConstantsController::SPARS_LOGO}}'">
                                            </div>
                                            @php
                                                $decodedData = json_decode(unserialize($item->item_data), true);
                                                $colorID = substr($decodedData['ColorID'], 0, 3);
                                            @endphp
                                            <div class="col-9" style="font-size: 12px">
                                                <div class="mx-3 mt-2 font-weight--bold row">Design:
                                                    <p class="font-weight--normal mx-2 d-flex flex-wrap mx-1">{{ Str::after($item->item_name, 'DESIGN: ') }} {{$colorID}}
                                                        <span class="cfa-rem {{$item->cfa != 1 ? 'd-none' : ''}}">CFA Required</span>
                                                        <span class="cfa-rem {{$item->remnant_shipable != 1 ? 'd-none' : ''}}">Remnant Required</span> </p>
                                                </div>
                                                {{-- <div class="mx-3 mt-2 row">SKU: <p class="font-weight--normal mx-2">N/A</p> --}}
                                                <div class="mx-3 mt-2 row">Roll Id: <p
                                                        class="font-weight--normal mx-2">{{ $item->bd_roll_id }}</p>
                                                </div>
                                                <div class="mx-0 mt-2 row">
                                                    <div class="col-2">Size:
                                                    </div>
                                                    @php
                                                        $sizes = json_decode( unserialize($item->item_data ), true );
                                                        //$sizes = json_decode($item->item_data, true );
                                                    @endphp
                                                    <div class="col-md-12 col-lg-8 col-sm-12">

                                                    @foreach($sizes['CutPieces'] as $item_sizes)
                                                        @php
                                                            $lenght_feet =  (int)floor($item_sizes['ATSLength'] / 12);
                                                            $width_feet =  (int)floor($item_sizes['ATSWidth'] / 12);
                                                            $lenght_inch =  $item_sizes['ATSLength'] % 12;
                                                            $width_inch =   $item_sizes['ATSWidth'] % 12;
                                                        @endphp
                                                        <div
                                                            class="mytooltip badge badge-default broadloom-badge side-bar-broadloom-badge"
                                                            style="margin: 2px 2px !important;background: @if($item_sizes['LengthStatus'] == 'F') blue @else #660000 @endif">
                                                            {{ $width_feet . "'" . $width_inch . "\"" . " x " . $lenght_feet  . "'" . $lenght_inch . "\"" }}
                                                            @if(!empty($item_sizes['SergingType']))
                                                                <span
                                                                    class="tooltiptext">
                                                                                            {{-- <strong>Serging Charges: ${{ number_format($item_sizes['SergingCharges'], ConstantsController::ALLOWED_DECIMALS) }}</strong> --}}
                                                                                            <strong>Serging Charges: {{ $item->item_currency }}{{ number_format($serging_charges, 2) }}</strong>
                                                                                        </span>
                                                            @endif
                                                        </div>

                                                    @endforeach
                                                </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                @endif
                            </div>

                            {{-- @php $samNameItems = []; $bd_cart_price = 0; @endphp
                            @foreach($cart->items as $item)
                                @php
                                    if(!in_array($item->item_name, $samNameItems)){
                                        $bd_cart_price += $item->item_price;
                                        $samNameItems[] = $item->item_name;
                                    }
                                @endphp
                            @endforeach --}}

                            <div class="col-md-3 text-right align-content-center" id="lastpage-subtotal">${{$cart->cart_total}}</div>
                            {{-- <div class="col-md-3 text-right align-content-center">${{$bd_cart_price}}</div> --}}
                            <div class="col-md-9">
                                <hr class="mx-4" style="border-top-color: whitesmoke;">
                            </div>
                            <div class="col-md-4 align-content-center font-weight--bold">Cut Cost</div>
                            <div class="col-md-4 align-content-center text-right font-weight--bold order-detail-subtotal">
                                ${{$cart->cart_total}}</div>
                            {{-- <div class="col-md-4 align-content-center text-right font-weight--bold">
                                ${{$bd_cart_price}}</div> --}}
                            <div class="col-md-9">
                                <hr class="mx-4" style="border-top-color: whitesmoke;">
                            </div>
                            <div class="col-md-4 align-content-center">Serging Cost</div>
                            <div class="col-md-4 align-content-center text-right order-detail-serging">$0.00</div>
                            <div class="col-md-9 mb-3">
                                <hr class="mx-4" style="border-top-color: whitesmoke;">
                            </div>
                            <div class="col-md-4 align-content-center">Rug Pad</div>
                            <div class="col-md-4 align-content-center text-right order-detail-rugpad">$0.00</div>
                            <div class="col-md-9 mb-3">
                                <hr class="mx-4" style="border-top-color: whitesmoke;">
                            </div>
                            <div class="col-md-4 align-content-center font-weight--bold" style="font-size: 20px">Total
                            </div>
                            <div class="col-md-4 align-content-center text-right font-weight--bold mb-5 cart_total_final"
                                 style="font-size: 20px"></div>
                            <div class="col-sm-8 my-5 row justify-content-center">
                                <a href="/dashboard/home" class="add-to-cart-button btn btn-dark align-content-center text-left mt-5"
                                   id="add_cart">
                                    Go to dashboard &nbsp; &nbsp; &nbsp;<i class="fa fa-long-arrow-right pl-5"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        {{-- HASH MODAL --}}
        <div class="modal fade bd-example-modal-lg popupModal login-modal-popup" id="checkOut_popup" tabindex="-1"
            role="dialog" aria-labelledby="checkOutLabel" aria-hidden="true">
            <div class="backdrop" style="display:none;"></div>
        <div class="modal-dialog modal-lg wunst" role="document">
            <div class="modal-content">
                <div class="modal-header col-sm-12 justify-content-center flex-column">
                    <h2 class="title d-flex flex-column justify-content-center text-center" style="margin: 0 auto;">
                    </h2>
                </div>
                <div class="modal-body thanku flex-column justify-content-center" style="margin-top:-0;">
                    <p class="thanku-msg text-center"></p>
                    <a href="{{ route('frontend.home') }}" style="padding: 6px 12px;"
                        class="btn btn-dark text-uppercase checkout-signin mt-20 col pull-left btn-back-to-home">BACK TO
                        HOME</a>
                </div>
            </div>
        </div>
        </div>
        {{-- HASH MODAL --}}
        @include('frontend.' . $active_theme->theme_abrv . '.components.footer')
    </div>
    @include('frontend.' . $active_theme->theme_abrv . '.components.login-modal')
@endsection

@section('styles')
    <style>
        .muted-bd-fields {
            /* opacity: 0.4; */
            pointer-events: none;
            cursor: not-allowed;
        }
        .cfa-rem{
            border: 1px solid #660000;
            color: #660000;
            padding: 2px;
            border-radius: 5px;
            margin: 0px 1px;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {

            // SET ORDER SHIP DATE
            var today = new Date();
            var day = ("0" + today.getDate()).slice(-2);
            var month = ("0" + (today.getMonth() + 1)).slice(-2);
            var year = today.getFullYear();
            var formattedDate = year + '-' + month + '-' + day;
            $('.order-ship-date').val(formattedDate);

            // var subtotal = parseFloat($(".section_2_subtotal").text().replace('$', " "));
            var subtotal = parseFloat($(".section_2_subtotal").text().replace('$', " ").replace(',', ""));
            var formatted_subtotal = subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            var shippingCharges = parseFloat($(".section_2_shipping_charges").text().replace('$', ''));
            var sergingTotal = parseFloat($("#sergingTotal").val());
            var serging_charges = parseFloat($(".serging_charges").text().replace('$', " ").replace(',', ""));
            var formatted_serg_charges = serging_charges.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            var cutting_charges = parseFloat($(".cutting_charges").text().replace('$', " ").replace(',', ""));
            var rugpad_charges = parseFloat($(".rugpad_charges").text().replace('$', " ").replace(',', ""));
            var formatted_rugpad_charges = rugpad_charges.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            var formatted_cut_charges = cutting_charges.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            shippingCharges = 0;
            var total = subtotal + serging_charges + rugpad_charges + shippingCharges;
            var formatted_total = total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            $(".section_2_subtotal").text("$" + formatted_subtotal);
            $(".section_2_serging_charges").text("$" + formatted_serg_charges);
            $(".section_2_rugpad_charges").text("$" + formatted_rugpad_charges);
            $(".section_2_cutting_charges").text("$" + formatted_cut_charges);
            $(".section_2_cart_total").text("$" + formatted_total);
            $(".order_placed_total").text("$" + formatted_total);

            // $('.delete-row').click(function () {
            //     $(this).closest('tr').remove();
            // });

            function updateTotalPrice() {
                var quantity = parseInt($('#item_qty').val());
                var itemPrice = @if(isset($item) && is_numeric($item->item_price)) parseFloat("{{ $item->item_price }}")
                @else '' @endif;
                // Assuming item_price is a numeric value
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
                //var subtotal = parseFloat($("#item_subtotal_price").text());
                var subtotal = parseFloat($("#item_subtotal_price").text().replace('$', " ").replace(',', ""));
                var formatted_subtotal = subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                var shippingCharges = parseFloat($(".shipping_charges").text().replace('$', ''));
                var sergingTotal = parseFloat($(".serging_charges").text().replace('$', " ").replace(',', ""));
                var sergingCharges = parseFloat($(".section_2_serging_charges").text().replace('$', " ").replace(',', ""));
                var formatted_sergingCharges = sergingCharges.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                var rugpadCharges = parseFloat($(".section_2_rugpad_charges").text().replace('$', " ").replace(',', ""));
                var formatted_rugpadChargess = rugpadCharges.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                var cuttingCharges = parseFloat($(".section_2_cutting_charges").text().replace('$', " ").replace(',', ""));
                var formatted_cuttingCharges = cuttingCharges.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                // var orderDeatilSubTotal = subtotal + sergingTotal
                var orderDeatilSubTotal = parseFloat($(".section_2_subtotal").text().replace('$', " ").replace(',', ""));
                var formatted_orderDeatilSubTotal = orderDeatilSubTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                //subtotal = parseFloat($("#inside-hidden-subtotal").val().replace('$', " ").replace(',', ""));

                shippingCharges = 0;
                var total = subtotal + rugpadCharges + sergingTotal  + shippingCharges;

                $('.order-detail-subtotal').text("$" + formatted_orderDeatilSubTotal);
                $('.order-detail-serging').text("$" + formatted_sergingCharges);
                $('.order-detail-rugpad').text("$" + formatted_rugpadChargess);
                $('.order-detail-cutting').text("$" + formatted_cuttingCharges);
                $(".cart_total").text("$" + formatted_subtotal);
                // $(".cart_total_final").text("$" + total.toFixed(2));
                $(".cart_total_final").text("$" + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
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
                        window.location.reload();
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

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#place_order').click(function () {
                var form = $('#customer_info')[0];

                var firstName = $('input[name="FirstName"]')[0];
                var streetaddress = $('input[name="Address1"]')[0];
                var country = $('#countries')[0];
                var city = $('input[name="City"]')[0];
                var state = $("#state_dropdown")[0];
                var zipCode = $('input[name="Zip"]')[0];
                var refNo = $('input[name="reference_number"]')[0];
                var shipDate = $('input[name="ship_date"]')[0];
                $('#lastpage-subtotal').text("$" + $('#inside-hidden-subtotal').val());

                // if ($('.ship-method-select').val() == 'BEST' || $('.ship-method-select').val() === 'FD58' || $('.ship-method-select').val() === 'FD51'
                // || $('.ship-method-select').val() === 'FD50' || $('.ship-method-select').val() === 'OTHER' || $('.ship-method-select').val() === 'UT01'
                // || $('.ship-method-select').val() === 'UT03') {
                if ( 
                 $('.ship-method-select').val() === 'OTHER' || $('.ship-method-select').val() === 'Third Party') {
                    if($('#ship_instructions').val().trim() == ''){
                        console.log($('.ship-method-select').val());
                        alert(`Order Notes are required`);
                        return true;
                    }
                }

                // form.checkValidity()
                if (firstName.checkValidity() && streetaddress.checkValidity()  && country.checkValidity()  && city.checkValidity()  && zipCode.checkValidity() && refNo.checkValidity() && shipDate.checkValidity()) {
                    $('#customer_info').find(':disabled').prop('disabled', false);
                    var formData = $('#customer_info').serialize();
                    $('#customer_info').find(':disabled').prop('disabled', true);
                    var quoteCartData = $('#quote-cart-data').val();
                    var quoteNo = $('#quote-no').val();
                    formData += '&quoteCartData=' + encodeURIComponent(quoteCartData);
                    formData += '&quoteNo=' + encodeURIComponent(quoteNo);
                    console.log('form data', formData);
                    console.log('checking testing')
                    $.ajax({
                        url: '{{route("frontend.checkout.place_order")}}',
                        type: "POST",
                        data: formData,
                        success: function (response) {
                            if (response.success) {
                                if(!response.webhook){
                                    $('#orderno').text('');
                                    var spanText = response.msg.match(/\s+(\d+)/);
                                    var newOrderNo = spanText ? spanText[1] : '';
                                    // $('#orderno').text(newOrderNo);
                                    $('#orderno').text(response.order_no);
                                    console.log(response.msg);
                                    console.log(spanText);
                                    console.log(newOrderNo);
                                    $('.stepper-heading').text('Order Complete');
                                    $('.section-3').addClass('active');
                                    $('#section1').attr('style', 'display:none;');
                                    $('#section2').attr('style', 'display:none;');
                                    $('#section3').attr('style', 'display:block;');
                                    $('.badge.badge-pill.badge-primary.position-absolute.cartCount').text('0');

                                    $.ajax({
                                        url: "{{ route('delete-cart-items') }}",
                                        type: "GET",
                                        success: function (deleteResponse) {
                                            if (deleteResponse) {
                                                toastr.success('The order has been successfully processed, and the cart is now empty.', {
                                                    hideDuration: 10000,
                                                    closeButton: true,
                                                });
                                            } else {
                                                toastr.error('Someting went wrong while empty the cart after place order.', {
                                                    hideDuration: 10000,
                                                    closeButton: true,
                                                });
                                            }
                                        }
                                    })
                                }else{
                                    $('#checkOut_popup .title').html('<i class="bi bi-info-circle-fill" style="color:#c90f41;font-size:30px;"></i>Oops!');
                                    $('#checkOut_popup .btn-back-to-home').attr('data-dismiss', 'modal');
                                    $('#checkOut_popup .thanku-msg').html(response.msg);
                                    $('#checkOut_popup').modal({backdrop: 'static', keyboard: false});
                                    $('#checkOut_popup').modal('show');
                                }
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
                } else {
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

            $(document).on('click', 'input[name="shipping-address"]', function () {
                var addressValue = $(this).val();
                // console.log('addressValue', addressValue);
                if (addressValue == 'existing-address') {
                    var referenceNumberValue = $('input[name="reference_number"]').val();
                    var shipValue = $('.order-ship-date').val();
                    //$("#countries").val(0);
                    //$("#state_dropdown").val('');
                    $(".disable-toggle").addClass("muted-bd-fields");
                    $(".disable-toggle").removeAttr("required");
                    // $("input").not("[type='radio']").val('');


                    $('#select-address').trigger('change');
                    $('input[name="reference_number"]').val(referenceNumberValue);
                    $('.order-ship-date').val(shipValue);
                    $('#select-address').prop("disabled", false);
                } else {
                     $(".disable-toggle").removeClass("muted-bd-fields");
                    // $(".hidden-inp").val("");
                    // $('#hidden-address_id').val("");
                    // $(".disable-toggle").val("");
                    // $("#countries").val(0);
                    // $("#state_dropdown").val('');
                     $(".disable-toggle").attr("required", true);
                     $("#bd-address2").removeAttr("required")
                    // $('#select-address').prop("disabled", true);
                }
            });


            $('#select-address').on('change', function () {
                var selectaddress = $('#select-address').val();
                var parts = selectaddress.split('~~~');
                var firstPart = parts[0];
                var secondPart = parts[1];
                console.log('firstPart', firstPart);
                console.log('secondPart',secondPart);

                // $('.hidden-address_id').val('');
                if (firstPart != undefined) {
                    $('#hidden-address_id').val(firstPart);
                }
                // $('.hidden-inp').val('');
                if (secondPart != undefined) {
                    $('.hidden-inp').val(secondPart);
                }

                let valSet = JSON.parse($('.hidden-inp').val());
                $('input[name="FirstName"]').val(valSet.FirstName);
                $('input[name="Address1"]').val(valSet.Address1);
                $('input[name="Address2"]').val(valSet.Address2);
                $('input[name="City"]').val(valSet.City);
                $('input[name="Zip"]').val(valSet.Zip);
                $('#customer_state').val(valSet.State);
                $('#customer_country').val(valSet.Country);
                // console.log(valSet.Country);
                $('#countries').find('option').each(function() {
                    console.log($(this).text());
                    if ($.trim($(this).text()) === $.trim(valSet.Country)) {
                        $(this).prop('selected', true);
                        return false;
                       console.log($(this).val());// Exit the loop once the correct option is found
                    }
                });
                $('#countries').change();
            });

            @if(isset($cust_country))
            var custCountry = "{{$cust_country}}";
            var selectedValue = $('#countries').find('option').filter(function () {
                return $(this).text().trim() === custCountry.trim();
            }).attr('origincode');
            setTimeout(() => {
                console.log('selectedValue :: ', selectedValue);
                states(selectedValue);
            }, 500);
            @endif

            $('#countries').change(function () {
                let selectedOption = $(this).find('option:selected');
                // Get the origincode attribute from the selected option
                let selectedCountry = selectedOption.attr('origincode');
                console.log('selectedCountry :: ', selectedCountry);

                if (selectedCountry) {
                    states(selectedCountry);
                }
            });

            function states(countryno) {
                $.ajax({
                    url: "{{route('checkout.states')}}",
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: {country: countryno},
                    success: function (response) {
                        if (response.Success) {
                            console.log("if");
                            $('#state_dropdown').empty();
                            $('#state_dropdown').append('<option value="">Select a state*</option>');
                            $.each(response.States, function (index, value) {
                                var option = $('<option>', {
                                    value: value.StateCode.toString(),
                                    text: value.StateName
                                });
                                // console.log(`${value.StateCode} == ${$('#customer_state').val()}`);
                                if (value?.StateCode.toLowerCase() == $('#customer_state').val()?.toLowerCase()) {
                                    option.prop('selected', true);
                                }
                                $('#state_dropdown').append(option);
                            });
                        } else {
                            console.log("if");
                            $('#state').val('');
                            $('#state_dropdown').empty();
                            $('#state_dropdown').append('<option value="">No States Available</option>');
                        }
                    },
                    error: function (xhr, status, error) {
                        alert(error);
                    }
                });
            }

        });


        $(document).ready(function () {
            console.log("triger 1592");
            var selectaddress = $('#select-address').val();
            var parts = selectaddress.split('~~~');
            var firstPart = parts[0];
            var secondPart = parts[1];

            // $('.hidden-address_id').val('');
            if (firstPart != undefined) {
                $('#hidden-address_id').val(firstPart);
            }
            // $('.hidden-inp').val('');
            if (secondPart != undefined) {
                $('.hidden-inp').val(secondPart);
            }

            let valSet = JSON.parse($('.hidden-inp').val());
            $('input[name="Address1"]').val(valSet.Address1);
            $('input[name="City"]').val(valSet.City);
            $('input[name="Zip"]').val(valSet.Zip);
            $('#countries').change();
        });

        $('#state_dropdown').prop('disabled', true);
        $('#countries').prop('disabled', true);
        $('input[name="shipping-address"]').on('change', function () {
            if($(this).val() != "other"){
                $('#state_dropdown').prop('disabled', true);
                $('#countries').prop('disabled', true);
            }else {
                $('#state_dropdown').prop('disabled', false);
                $('#countries').prop('disabled', false);
            }
        });

        $('.ship-method-select').on('change', function() {
            const selectedValue = $(this).val();
            if (selectedValue == 'BEST' || selectedValue === 'FD58' || selectedValue === 'FD51' || selectedValue === 'FD50' || selectedValue === 'OTHER'
                || selectedValue === 'UT01' || selectedValue === 'UT03'
            ) {
                $('#ship_instructions').prop('required', true);
            } else {
                $('#ship_instructions').prop('required', false);
            }

            // if(selectedValue == 'BEST' || selectedValue === 'AMZX'){
            //     //$('.section_2_shipping_charges').text('TBD');
            //     $('.section_2_shipping_charges').text('will be calculated at time of Shipping');
            // }else{
            //     $('.section_2_shipping_charges').text('will be calculated at time of Shipping');
            // }
        });
    </script>
@endsection

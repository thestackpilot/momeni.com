@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Auth;

@endphp

@extends('dashboard.layouts.app')
@section('title','Dashboard | Place Broadloom Order')
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
body{
    font-family: 'ProximaNovaFont' !important;
    line-height: 1.74 !important;
    font-size: 16px !important;
    font-style: normal !important;
}
.font-ropa-sans{
    font-family: "Ropa Sans", sans-serif !important;
}
.font-prox{
    font-family: 'ProximaNovaFont' !important;
}
.table thead th{
    font-family: 'ProximaNovaFont' !important;
}
.broadloom-badge{
    position: relative;
}
.broadloom-badge a {
    position: absolute;
    top: -12px;
    padding: 5px 6px;
    border-radius: 50%;
    border: 2px solid #fff;
    color: #fff;
    font-size: 10px;
}
.badge {
    font-size: 0.9rem !important;
}
.add-piece-btn{
    background: #282828;
    border: 2px solid #282828;
    padding: 10px 20px 7px;
    margin: 0 10px;
    color: #fff;
}
.admin-side .btn.btn-primary {
    min-width: 180px !important;
}
.cfa-rem {
    color: rgb(102, 0, 0);
    font-size: 9px;
    border-width: 1px;
    border-style: solid;
    border-color: rgb(102, 0, 0);
    border-image: initial;
    padding: 2px;
    border-radius: 5px;
    margin: 0px 1px;
}
.font-weight--normal {
    font-weight: 400;
}
.font-weight--bold {
    font-weight: 700;
}
.fa-2x {
    font-size: 2em;
}
.mytooltip {
    position: relative;
    display: inline-block;
    cursor: pointer;
}
.mytooltip .tooltiptext {
    visibility: hidden;
    width: 190px;
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 5px;
    padding: 10px 0;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 30%;
    margin-left: -70px;
    opacity: 0;
    transition: opacity 0.3s;
    font-size: 14px !important;
    opacity: 0;
    transition: opacity 0.3s;
}

.mytooltip:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}

.mytooltip .tooltiptext::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #333 transparent transparent transparent;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
    font-weight: 700 !important;
}
.table td, .table th {
    padding: .75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
.cfa-remnat-rug{
    flex-direction: row;
}
.broadloom-order{
    min-height: 40px !important;
    line-height: normal !important;
    width: auto !important;
    background: #660000 !important;
    color: #fff !important;
    border: 1px solid transparent !important;
    min-width: 225px !important;
}
.broadloom-order:hover{
    background: #282828 !important;
}
.select2-container .select2-selection--single {
    font-size: 14px !important;
    color: #333333 !important;
    height: 40px !important;
    border: 1px solid #e4e4e4 !important;
    border-radius: 1px !important;
    background: transparent !important;
}
.select2-container .select2-selection--single .select2-selection__rendered{
    padding: 10px !important
}
.select2-container--default .select2-selection--single .select2-selection__arrow b {
    top: 75% !important;
}
.label-without-form{
    font-size: 18px !important;
    color: #333333 !important;
}
@media (max-width: 767px) {
.cfa-remnat-rug{
    flex-direction: column;
}
}
@media (max-width: 1420px) and (min-width: 768px) {
.broadloom-order {
    min-width: 180px !important;
}
}
</style>
@endsection
@section('content')
<div class="wrapper admin-side">
   @include('dashboard.components.header')
   <main class="main-content">
      <section class="collection-section">
         <div class="container">
            <div class="d-flex flex-row">
               <div class="col-lg-3 col-sm-6 col-6 sidebar-main">
                  @include('dashboard.components.sidebar')
               </div>
               <div class="col-lg-9 col-sm-12 col-12 py-0">
                @if (Session::has('message'))
                  <div class="alert alert-{{Session::get('message')['type']}}">
                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     {!!Session::get('message')['body']!!}
                  </div>
                @endif
                @if (Session::has('message') && Session::get('message')['type'] == 'success')
                    <script>
                        const keysToRemove = [
                            'address1', 'address2', 'city', 'country', 'customer_po',
                            'email', 'first_name', 'last_name', 'postal_code',
                            'shipping-address', 'state', 'ship_via_id', 'req_ship_date'
                        ];

                        keysToRemove.forEach(key => {
                            if (localStorage.getItem(key) !== null) {
                                localStorage.removeItem(key);
                            }
                            const field = document.querySelector(`[name="${key}"]`);
                            if (field) {
                                field.value = '';
                            }
                        });
                    </script>
                @endif

                  <div class="account-content p-5">
                    <h1 class="section-title text-center mb-3 mt-3 font-ropa">Place Broadloom Order</h1>
                    <input type="hidden" name="" id="cuttype" value="">
                    <input type="hidden" name="" id="sergingtypeno" value="">
                    <input type="hidden" name="" id="charges" value="">
                    <input type="hidden" name="" id="desc" value="">
                    <input type="hidden" name="" id="cutpiece_id" value="">
                    <input type="hidden" name="" id="TempSalesOrderNo" value="">
                    <input type="hidden" name="" id="totalsqft" value="">
                    <input type="hidden" id="unit-price" name="unit-price" value="">
                    <input type="hidden" id="unit-price-cal" name="unit-price-cal" value="">
                    <input type="hidden" name="" id="locationid" value="">
                    <input type="hidden" id="item_json" value=""></input>
                    <input type="hidden" name="rug_pad_id" id="rug_pad_id" value="">
                    <input type="hidden" name="rug_pad_price" id="rug_pad_price" value="">
                    <input type="hidden" name="rug_pad_price_ext" id="rug_pad_price_ext" value="">
                    <form method="POST" class="place-order-form" action="{{ route('dashboard.place_bl_order_post') }}" class="pt-3">
                        @csrf
                        <input type="hidden" name="quoteCartData" id="quoteCartData" value="">
                        <div class="row">
                           @if($filters)
                           @foreach($filters as $filter)
                           @if($filter['type'] == 'hidden')
                           <input name="{{str_replace(' ', '_', strtolower($filter['title']))}}" value="{{$filter['value']}}" class="form-control" {!!isset($filter['attributes']) ? $filter['attributes'] : '' !!} type="{{$filter['type']}}" />
                           @else
                           <div class="mb-3 col-md-3 col-sm-12 pe-1 pe-lg-3">
                              <label for="{{$filter['placeholder'] ? str_replace(' ', '_', strtolower($filter['placeholder'])) : str_replace(' ', '_', strtolower($filter['title']))}}" class="form-label">{{$filter['placeholder'] ? $filter['placeholder'] : $filter['title']}}</label>
                              @if($filter['type'] == 'select')
                              <select name="{{str_replace(' ', '_', strtolower($filter['title']))}}" class="form-control" id="test">
                                 @foreach($filter['options'] as $option)
                                 <option {{old(str_replace(' ', '_', strtolower($filter['title']))) && old(str_replace(' ', '_', strtolower($filter['title']))) == $option['value'] ? 'selected' : ($filter['value'] == $option['value'] ? 'selected' : '' ) }} value="{{$option['value']}}">{{$option['label']}}</option>
                                 @endforeach
                              </select>
                              @elseif($filter['type'] == 'date')
                              <div class="input-group">
                                    <input name="{{str_replace(' ', '_', strtolower($filter['title']))}}" value="{{$filter['value']}}" class="form-control datepicker {{isset($filter['class']) ? $filter['class'] : ''}}" type="text" {!! isset($filter["attribues"]) ? $filter["attribues"] : "" !!} />
                                    <span class="input-group-addon">
                                       <i class="bi bi-calendar"></i>
                                    </span>
                              </div>
                              @else
                              <input name="{{str_replace(' ', '_', strtolower($filter['title']))}}" {!!$filter['attribues']!!} value="{{old(str_replace(' ', '_', strtolower($filter['title']))) ? : $filter['value']}}" class="form-control" type="{{$filter['type']}}" />
                              @endif
                           </div>
                           @endif
                           @endforeach
                           @endif
                        </div>
                        @php  $is_bd_item = false; @endphp
                        @foreach ($cart->items as $row)
                            @php
                                $is_bd_item = $row->broadloom_item == 1 ? true : false;
                            @endphp
                        @endforeach
                        <div class="row addresses-section">
                        <div class="d-flex justify-content-center m-5">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        </div>
                        <div class="other-address d-none" style="">
                        <input type="hidden" name="address_id" />
                        <div class="d-flex flex-row justify-content-between column-gap-20 mb-3">
                            <input type="text" data-required="true" class="form-control bg-white " name="first_name" value="{{old('first_name')}}" aria-describedby="FirstName" maxlength="35" placeholder="First Name*">
                            <input type="text" class="form-control bg-white" name="last_name" value="{{old('last_name')}}" aria-describedby="LastName" maxlength="35" placeholder="Last Name">
                        </div>
                        <div class="d-flex flex-row justify-content-between column-gap-20 mb-3">
                            <input type="email" data-required="true" class="form-control bg-white" name="email" value="{{old('email')}}" aria-describedby="Email" maxlength="60" placeholder="Email*">
                        </div>
                        <div class="d-flex flex-column">
                            <input type="text" data-required="true" class="form-control bg-white mb-3" value="{{old('address1')}}" name="address1" aria-describedby="Address" maxlength="35" placeholder="Address*">
                            <input type="text" class="form-control bg-white mb-3" value="{{old('address2')}}" name="address2" aria-describedby="Apartment" maxlength="35" placeholder="Apartment, suite, etc. (optional)">
                            <select  data-default="{{old('state')}}" id="state_dropdown" class="form-control bg-white reter checkout-dropdown my-2 other-state d-none"></select>
                            <input type="text" data-required="true" class="form-control bg-white mb-3" value="{{old('city')}}" name="city" maxlength="35" aria-describedby="City" placeholder="City*">
                            <select name="country" id="countries" class="form-control bg-white mb-3 other-country d-none" aria-describedby="country" required>
                                <option value="" disabled selected>Select your country*</option>
                                @foreach ($countries['Countries'] as $country)
                                    <option value="{{ $country['OriginCode'] }}" {{ old('country') == $country['OriginCode'] ? 'selected' : '' }}
                                        origincode="{{ $country['CountryNo'] }}">
                                        {{ $country['Description'] }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden"class="form-control bg-white mb-3 default-country" value="{{old('country')}}" maxlength="35" aria-describedby="Country" placeholder="Country*">
                            <!-- name="country" -->
                            <input type="hidden" name="state" id="stateid" class="form-control bg-white mb-3 default-state" value="{{old('state')}}" maxlength="50" aria-describedby="State" placeholder="State*"> 
                            <!-- name="state"  -->
                        </div>
                        <div class="d-flex flex-row justify-content-between column-gap-20 mb-3">
                            <input type="text" data-required="true" class="form-control bg-white" value="{{old('postal_code')}}" name="postal_code" maxlength="10" aria-describedby="PostalCode" placeholder="Postal Code*">
                        </div>
                        </div>
                        <hr />
                        <button type="submit" class="btn btn-primary submit-btn-hide" style="visibility: hidden;">Submit</button>
                    </form>

                    {{-- CUT PIECE SECTION --}}
                    {{-- cutpiece-section d-none --}}
                    <div class="row col-md-12">
                        <div class="row">
                            <div class="mb-3 col-md-3 col-sm-12 pe-1 pe-lg-3">
                                <label class="label-without-form">Item ID</label>
                                <select name="item_id" id="itemDropdown" class="form-control">
                                    <option value="">Select Item Id</option>
                                    @foreach($itemIds as $item)
                                        <option value="{{$item['KeyID']}}">{{$item['Description']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-3 col-sm-12 pe-1 pe-lg-3">
                                <label class="label-without-form">Roll ID</label>
                                <select name="roll_id" id="rollDropdown" class="form-control" disabled>
                                </select>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="mb-3">
                                    <label class="label-without-form">Cut Width   <span class="Twidth-max-error d-none" style="font-size:0.6rem; font-weight:800; color:red;"></span></label>
                                    <div>
                                        <input type="hidden" class="form-control Twidth-ats-max" id="Twidth-ats-max" value="">
                                        <input type="hidden" class="form-control TwidthInch-ats-max" id="TwidthInch-ats-max" value="">
                                    </div>
                                    <div class="mb-3 d-flex align-items-center justify-content-between">
                                        <div class="input-group me-2">
                                            <input type="number" class="form-control Twidth text-center small-input" name="Twidth" id="Twidth" placeholder="00" min="0" disabled style="text-align:right;">
                                            <span class="input-group-text TwidthGroup">Ft</span>
                                        </div>
                                        <div class="input-group ms-2">
                                            <input type="number" class="form-control TwidthInch text-center small-input" name="TwidthInch" id="TwidthInch" disabled placeholder="00" min="0" max="11" style="text-align:right;">
                                            <span class="input-group-text TwidthInchGroup">In</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="mb-3">
                                    <label class="label-without-form">Cut Length</label>
                                    <span class="Tlength-max-error d-none" style="font-size:0.6rem; font-weight:800; color:red;"></span>
                                    <div>
                                        <input type="hidden" class="form-control Tlength-ats-max" id="Tlength-ats-max" value="">
                                        <input type="hidden" class="form-control TlengthInch-ats-max" id="TlengthInch-ats-max" value="">
                                    </div>
                                    <div class="mb-3 d-flex align-items-center justify-content-between">
                                        <div class="input-group me-2">
                                            <input type="number" class="form-control Tlength text-center small-input" name="Tlength" id="Tlength" placeholder="00" min="0"style="text-align:right;">
                                            <span class="input-group-text TlengthGroup">Ft</span>
                                        </div>
                                        <div class="input-group ms-2">
                                            <input type="number" class="form-control text-center TlengthInch small-input" name="TlengthInch" id="TlengthInch" placeholder="00" min="0" max="11" style="text-align:right;">
                                            <span class="input-group-text TlengthInchGroup">In</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <div class="row">
                            <div class="mb-3 col-md-3 col-sm-12 pe-1 pe-lg-3">
                                <label class="label-without-form">SQ-FT Price <span class="font-prox">($)</span></label>
                                <input type="text" name="q-ft" id="sq-ft" value="" class="form-control" disabled>
                                <input type="hidden" class="form-control" id="ats-qty" value="" disabled="" style="text-align:right;">
                            </div>
                            <div class="mb-3 col-md-3 col-sm-12 pe-1 pe-lg-3">
                                <label class="label-without-form">SQ-YRD Price <span class="font-prox">($)</span></label>
                                <input type="text" name="sq-yrd" id="sq-yrd" value="" class="form-control" disabled>
                            </div>
                            <div class="mb-3 col-md-3 col-sm-12 pe-1 pe-lg-3">
                                <label class="label-without-form">EXT Price <span class="font-prox">($)</span></label>
                                <input type="text" name="sq-ext" id="sq-ext" class="form-control" disabled>
                                <input type="hidden" class="form-control" id="without-format-sq-ext" value="" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class=""><input type="checkbox" name="" id="surging_check">
                                    <strong> With Serging </strong></label>
                                    <select name="" id="surging_options" class="form-control" disabled="disabled">
                                        <option value="0" charges="">Select Option</option>
                                        @foreach ($surging_types as $row)
                                            <option value="{{$row['SergingTypeNo']}}" charges="{{$row['Charges']}}" desc="{{$row['Description']}}">{{$row['Description']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="label-without-form">Customer Instructions</label>
                                <textarea name="" id="cust-inst" class="form-control" rows="20" style="height:100px;"></textarea>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-9 col-md-9 col-6 text-start" id="cut_piece_parent">
                            </div>
                            <div class="col-lg-3 col-md-3 col-12 text-end">
                                <button class="add-piece-btn broadloom-btns add-cut-piece-btn mb-3" id="cut_piece_btn">
                                    Add Cut Piece
                                </button>
                            </div>
                        </div>

                        <hr />

                        <div class="row mt-3 cfa-remnat-rug">
                            <div class="col-lg-3 col-md-3 col-12">
                                <div class="form-group my-2">
                                    <label for="" class="mx-1"><input type="checkbox" name="" id="cfa_check">
                                    <strong>CFA Required</strong></label>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-12">
                                <div class="form-group my-2">
                                    <label for="" class="mx-1"><input type="checkbox" name="" id="remnant_check">
                                    <strong>Remnant Required</strong></label>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-12">
                                <div class="form-group my-2 rug-checkbox">
                                    <label for="" class="mx-1"><input type="checkbox" name="" id="add_rugpad">
                                    <strong>Rug Pad</strong></label>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-12 text-end">
                                <button class="btn btn-primary add_cart">ADD TO CART</button>
                            </div>
                        </div>
                    </div>
                    <hr />

                    {{-- CART SECTION --}}
                    <div class="account-content p-5 {{ count((array) $cart->items) && $is_bd_item ? '' : 'd-none' }}" id="cart-detail-area">
                        <div class="row mt-4 mb-5">
                            <div class="col-md-9 col-sm-12">
                                <div class="table-responsive">
                                    @if((count((array) $cart->items)))
                                        <table id="" class="table for-data-table">
                                            <input type="hidden" name="item" id="item_ids" value="[]">
                                            <input type="hidden" name="quantity" id="quantities" value="[]">
                                            @foreach ($cart->items as $row)
                                                @php
                                                    $cust = $row->item_customer_id;
                                                @endphp
                                            @endforeach
                                            <input type="hidden" name="customer" id="customer_id" value="{{ $cust }}">
                                            <thead>
                                            <tr>
                                                <th>Product</th>
                                                @foreach ($cart->items as $item)
                                                    @if(!$item->broadloom_item)
                                                        <th>Quantity</th>
                                                    @endif
                                                @endforeach
                                                <th>Cut Cost</th>
                                                <th>Serging Cost</th>
                                                <th>Rug Pad</th>
                                                <th>Sub Total</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if (count((array) $cart->items))
                                                @php  $subPriceTotal = 0;  $priceTotal = 0; $sergingTotal = 0;  $cuttingTotal = 0; $rugPadTotal = 0; $dashboardTotal = 0;  @endphp
                                                @foreach ($cart->items as $item)
                                                    @php
                                                        if (isset($item->item_data) && $item->item_data) {
                                                            $item_data = json_decode(unserialize($item -> item_data));
                                                        }
                                                        $sum_surging_charges = 0;
                                                        $serging_charges = 0;
                                                    @endphp
                                                    @if($item->broadloom_item && $item->is_bd_child != 1)
                                                    <tr>
                                                        <th class="" scope="row">
                                                            <input type="hidden" name="sales_rep_customer_check" class="sales_rep_customer_check" value="{{$item->item_customer_id}}">
                                                            <div class="row">
                                                                <div
                                                                    class="col-1 justify-content-center align-content-center delete-row"
                                                                    style="color: red;cursor: pointer;"
                                                                    onclick="removeItemFromCartInDashboard('{{$item -> item_id}}','{{csrf_token()}}','{{$item -> item_customer_id}}', '{{$item->broadloom_item}}', '{{$item->bd_roll_id}}', '{{$item->rand_str}}')">
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
                                                                    <div class="mt-1 row" style="display:flex; align-items: center;">
                                                                        <div class="col-md-5 col-lg-5 p-0 font-weight--bold"> Design:</div>
                                                                        <div class="col-md-7 col-lg-7 p-0 font-weight--normal" style="align-items: center;">
                                                                            {{ Str::after($item_data->ItemName, 'DESIGN: ') }} {{substr($item_data->ColorID, 0, 3)}}
                                                                        </div>
                                                                        <div class="col-md-10 col-lg-10 p-0" style="align-items: center;">
                                                                            <span class="cfa-rem {{$item->cfa != 1 ? 'd-none' : ''}}">CFA Required</span>
                                                                            <span class="cfa-rem {{$item->remnant_shipable != 1 ? 'd-none' : ''}}">Remnant Required</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mt-1 row" style="display:flex; align-items: center;">
                                                                        <div class="col-md-5 col-lg-5 p-0 font-weight--bold"> Roll Id: </div>
                                                                        <div class="col-md-7 col-lg-7 p-0 font-weight--normal" style="align-items: center;">
                                                                            {{ $item_data->RollID }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="mt-1 row" style="display:flex; align-items: center;">
                                                                            <div class="col-md-5 col-lg-5 p-0 font-weight--bold"> Sizes:</div>
                                                                            <div class="col-md-7 col-lg-7 p-0" style="align-items: center;">
                                                                                @php
                                                                                $sizes = json_decode( unserialize($item->item_data ), true );
                                                                                $sum_surging_charges = 0;
                                                                                @endphp
                                                                                @foreach($sizes['CutPieces'] as $key=>$item_sizes)
                                                                                    @php
                                                                                        $lenght_feet =  (int)floor($item_sizes['ATSLength'] / 12);
                                                                                        $width_feet =  (int)floor($item_sizes['ATSWidth'] / 12);
                                                                                        $lenght_inch =  $item_sizes['ATSLength'] % 12;
                                                                                        $width_inch =   $item_sizes['ATSWidth'] % 12;
                                                                                        if (!empty($item_sizes['SergingType'])) {
                                                                                            $serging_charges = 0;
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
                                                                                                <strong>Serging charges: {{ $item->item_currency }}{{ number_format($serging_charges, 2) }}</strong>
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
                                                            <span class="font-prox">$</span>{{ number_format($item->item_price, 2) }}</td>
                                                        <td class="align-content-center">
                                                            @php
                                                                $sergingTotal += $sum_surging_charges;
                                                                number_format($sergingTotal, 2);
                                                                $cuttingTotal += $item->unit_price;
                                                                number_format($cuttingTotal, 2);
                                                            @endphp
                                                            <span class="font-prox">$</span>{{ number_format($sum_surging_charges + $item->unit_price, 2) }}
                                                        </td>
                                                        <td class="align-content-center">
                                                            @php
                                                                $rugPadTotal += $item->rugpad_price;
                                                                number_format($rugPadTotal, 2);
                                                            @endphp
                                                            <span class="font-prox">$</span>{{ number_format($item->rugpad_price, 2) }}
                                                        </td>
                                                        <td class="align-content-center"><span class="font-prox">$</span><span
                                                                id="item_total_price">{{ number_format($sum_surging_charges + $item->rugpad_price + $item->unit_price + $item->item_total, 2)  }}
                                                            @php
                                                                $dashboardTotal += ($sum_surging_charges + $item->rugpad_price + $item->unit_price + $item->item_total);
                                                                $subPriceTotal += $sum_surging_charges + $item->unit_price + $item->item_total
                                                            @endphp
                                                        </span>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                                <input type="hidden" id="sergingTotal" value="{{ number_format($sergingTotal, 2) }}">
                                                <input type="hidden" id="sergingTotal" value="{{ number_format($sergingTotal, 2) }}">
                                                <input type="hidden" name="inside-hidden-subtotal" id="inside-hidden-subtotal" value="{{ number_format( $subPriceTotal, 2)}}">
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
                                    @endif
                                </div>
                            </div>
                            @if((count((array) $cart->items)))
                            <div class="col-md-3 col-sm-12 border">
                                <div class="d-flex justify-content-around align-items-left flex-column">
                                    <p class="mt-2 mb-2 text-center fa-2x">Cart Totals</p>
                                    <div class="row mt-3">
                                        <div class="col-md-7">Cut Cost:</div>
                                        <div class="col-md-5 text-right cart_total">{{ $cart->cart_currency }}{{ number_format($priceTotal, 2) }}</div>
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
                                        <div class="col-md-5">Shipping Charges:</div>
                                        <div class="col-md-7 text-right shipping_charges" id="shippingCharges">Will be calculated at the time of shipping</div>
                                    </div>
                                    <hr style="border-top-color: whitesmoke;">
                                    <div class="row mt-3">
                                        <div class="col-md-6 font-weight-bold">Total:</div>
                                        <div class="col-md-6 font-weight-bold text-right cart_total_final">{{ $cart->cart_currency }}{{  number_format($dashboardTotal, 2)}}</div>
                                    </div>
                                    <button class="broadloom-order btn my-2 mx-2 cutpiece-section1" id="proceed_to_order">
                                        Place Order <i class="fa fa-long-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                  </div>
               </div>
            </div>
         </div>
      </section>
   </main>
   @include('dashboard.components.footer')
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
let input_lenght_ats = 0;
let added_cut_pieces = [];
var item_object = "";
var unitprice = 0.00;
var ATS_ROLL_LENGHT = "";

GetCutingService();
SalesRepCustomerCartCheck();


$(document).ready(function() {
    const fields = [
        'first_name', 'last_name', 'email', 'address1', 'address2',
        'state', 'city', 'country', 'postal_code'
    ];

    $('#itemDropdown').select2();
    $('#rollDropdown').select2();

    $("input[name='customer_po']").on('input', function() {
        localStorage.setItem('customer_po', $(this).val());
    });

    $("[name='req_ship_date']").on('change', function() {
        localStorage.setItem('req_ship_date', $(this).val());
    });

    $('#state_dropdown').on('change', function() {
         localStorage.setItem('state', $(this).val());
    });
    $('#countries').on('change', function() {
        localStorage.setItem('country', $(this).val());
    });

    if (localStorage.getItem('customer_po')) {
        $("input[name='customer_po']").val(localStorage.getItem('customer_po'));
    }

    if (localStorage.getItem('ship_via_id')) {
        console.log('ship_via_id :: ', localStorage.getItem('ship_via_id'))
        $("[name='ship_via_id']").val(localStorage.getItem('ship_via_id'));
        $(`option[value='${localStorage.getItem('ship_via_id')}']`, $("[name='ship_via_id']")).attr('selected', 'selected').trigger('change');
    }

    if (localStorage.getItem('req_ship_date')) {
        console.log('req_ship_date :: ', localStorage.getItem('req_ship_date'))
        $("[name='req_ship_date']").val(localStorage.getItem('req_ship_date'));
    }
    if (localStorage.getItem('customer_po')) {
        $("input[name='customer_po']").val(localStorage.getItem('customer_po'));
    }

    $(document).on('change', '[name="shipping-address"]',function() {
        localStorage.setItem('shipping-address', $(this).val());
    });
    if (localStorage.getItem('shipping-address')) {
        $("input[name='shipping-address']").val(localStorage.getItem('shipping-address'));
    }

    $(document).on('change', '[name="shipping-address"]',function() {
        let selectedValue = $('input[name="shipping-address"]:checked').val();
        if(selectedValue == "other"){
            $('.default-country').addClass('d-none');
            $('.default-state').addClass('d-none');
            $('.other-country').removeClass('d-none');
            $('.other-state').removeClass('d-none');
        }else{
            $('.default-country').removeClass('d-none');
            $('.default-state').removeClass('d-none');
            $('.other-country').addClass('d-none');
            $('.other-state').addClass('d-none');
        }
    });

    fields.forEach(field => {
        const inputSelector = `input[name='${field}']`;
        $(inputSelector).on('input', function() {
            localStorage.setItem(field, $(this).val());
        });
    });

    function loadDropShip() {
        fields.forEach(field => {
            if(field == "country" || field == "state"){
                var inputSelector = `select[name='${field}']`;
            }else{
                var inputSelector = `input[name='${field}']`;
            }
            if (localStorage.getItem(field) && localStorage.getItem('shipping-address') == "other") {
                const storedValue = localStorage.getItem(field);
                if ($(inputSelector).is('input')) {
                    $(inputSelector).val(storedValue);
                }
                else if ($(inputSelector).is('select')) {
                    $(inputSelector).val(storedValue).trigger('change');
                }
            }
        });
    }


    function removeDropShip(){
        fields.forEach(key => {
            localStorage.removeItem(key);
        });
    }

    $(document).on('change', '.select-address', function() {
        $('.address-card').addClass('d-none');
        $(`.address-card.${$(this).val()}`).removeClass('d-none');
        if(localStorage.getItem('shipping-address')){
            // $(`.address-card.${localStorage.getItem('shipping-address')} input[type="radio"]`).click();
            if(localStorage.getItem('shipping-address') == "other"){
                $('.addresses-section input[type="radio"]:last').click()
                loadDropShip();
            }else{
                $(`.address-card.${$(this).val()} input[type="radio"]`).click();
                removeDropShip();
            }
        }else{
            $(`.address-card.${$(this).val()} input[type="radio"]`).click();
        }
    }).change();

    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    function update_totals() {
        return;
        let total = 0;
        $('#data_body tr').each(function() {
        var price = typeof $('.item-price', $(this)).val().length ? $('.item-price', $(this)).val() : 0;
        var quantity = typeof $('.item-quantity', $(this)).val().length ? $('.item-quantity', $(this)).val() : 0;
        var sub_total = (parseFloat(price) * parseFloat(quantity));
        if (isNaN(sub_total)) sub_total = 0;
        $('.item-total-price', $(this)).html(
            sub_total.toLocaleString('en-US', {
                style: 'currency',
                currency: 'USD',
            })
        );
        total = total + sub_total;
        });
        if (isNaN(total)) total = 0;
        $('.items-grand-total').html(
        total.toLocaleString('en-US', {
            style: 'currency',
            currency: 'USD',
        })
        );
    }

    $(document).on('keyup, change', "table input[type='number']", function() {
        update_totals();
    });

    $("[name='customer_id']").on("change", function(e) {
        $('.addresses-section').html(`
                <div class="d-flex justify-content-center m-5">
                    <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                    </div>
                </div>
                `);
        $('.other-address').addClass('d-none');
        $('.cutpiece-section').addClass('d-none');

        let addresses = '<div class="col-md-12">';
        $.post('{{route("dashboard.customeraddresses")}}', {
        customer: $(this).val(),
        _token: '{{csrf_token()}}'
        }, function(data) {
        if (typeof data !== 'undefined' && data.success && typeof data.addresses !== 'undefined') {
            const selectedValue = localStorage.getItem('ship_via_id') || data?.addresses?.CustomerShipVias?.[0]?.ShipViaID;
            const dropdown = $('[name="ship_via_id"]');
            const optionExists = dropdown.find(`option[value="${selectedValue}"]`).length > 0;
            dropdown.val(optionExists ? selectedValue : (localStorage.getItem('ship_via_id') ? localStorage.getItem('ship_via_id') : dropdown.find('option:first').val())).change();

            var selectedAddress = '{{old("address_id") ? old("address_id") : "" }}';
            addresses += '<div class="row mb-4">';
            addresses += '<div class="col-md-12">';
            addresses += '<label class="label-without-form">Address</label>';
            addresses += '<select class="form-control select-address">';
            data.addresses.ShipToAddresses.forEach((address) => {
                addresses += '<option ' + (selectedAddress == address.AddressID ? 'selected' : '') + ' value="' + address.AddressID + '">' + address.FirstName + ' ' + address.LastName + '</option>';
            });
            addresses += '</select>';
            addresses += '</div>';
            addresses += '</div>';
            addresses += '<div class="row">';

            data.addresses.ShipToAddresses.forEach((address) => {
                addresses += '<div class="col-md-12 address-card d-none ' + address.AddressID + '">';
                addresses += '<div class="border border-light card mb-3">';
                addresses += '<div class="card-body text-dark">';
                addresses += '<h5 class="align-items-center card-title d-flex">';
                addresses += '<input type="radio" name="shipping-address" id="' + address.AddressID + '" value="' + address.AddressID + '">';
                addresses += '<div class="shipping-address-data d-none">' + JSON.stringify(address) + '</div>';
                addresses += '<label style="width: 90%;" class="m-0 pl-2" for="' + address.AddressID + '">' + address.AddressID + '</label>';
                addresses += '</h5>';
                addresses += '<p class="card-text">';
                addresses += '<label style="width: 100%;" for="' + address.AddressID + '">';
                addresses += '<span><b>' + address.FirstName + ' ' + address.LastName + '</b></span><br>';
                addresses += '<span><b>' + address.Address1 + '</b></span><br>';
                addresses += '<span>' + address.City + '</span>, ';
                addresses += '<span>' + address.State + '</span> ';
                addresses += '<span>' + address.Zip + '</span><br>';
                addresses += '<span>' + address.Country + '</span><br>';
                addresses += '<span>' + address.Phone1 + '</span><br>';
                addresses += '</label>';
                addresses += '</p>';
                addresses += '</div>';
                addresses += '</div>';
                addresses += '</div>';
            });
        }
        addresses += '</div>';
        addresses += '<div class="row">';
        addresses += '<div class="col-md-12">';
        addresses += '<div class="border border-light card mb-3">';
        addresses += '<div class="card-body text-dark">';
        addresses += '<h5 class="align-items-center card-title d-flex">';
        addresses += '<input type="radio" name="shipping-address" id="other" value="other">';
        addresses += '<label style="width: 90%;" class="m-0 pl-2" for="other">Drop Ship</label>';
        addresses += '</h5>';
        addresses += '</div>';
        addresses += '</div>';
        addresses += '</div>';
        addresses += '</div>';
        addresses += '</div>';
        $('.addresses-section').html(addresses);
        $('.select-address').change();
        bind_radio_clicks();
        });
        bind_radio_clicks();
    }).change();

    function bind_radio_clicks() {
        $('.addresses-section input[type="radio"]')
        .off('click')
        .on('click', function() {
            if ($(this).val() === 'other') {
                $('[name="address_id"]').val('');
                $('.other-address').removeClass('d-none');
                $('.other-address input').each(function() {
                    if ($(this).attr('type') !== 'hidden') {
                    $(this).val($(this).attr('data-old-val'));
                    $(this).attr('data-old-val', '');
                    }
                });
            } else {
                $('.other-address').addClass('d-none');
                const address_data = JSON.parse($('.shipping-address-data', $(this).parent()).html());
                $('.other-address input').each(function() {
                    if ($(this).attr('type') !== 'hidden' && $('[name="address_id"]').val() == '') {
                    $(this).attr('data-old-val', $(this).val());
                    }
                });
                console.log('address_data', address_data);

                $('[name="address_id"]').val($(this).val());
                $('[name="first_name"]').val(address_data.FirstName);
                $('[name="last_name"]').val(address_data.LastName);
                $('[name="email"]').val(address_data.Email);  //mango
                $('[name="address1"]').val(address_data.Address1);
                $('[name="address2"]').val(address_data.Address2);
                $('[name="city"]').val(address_data.City);
                $('[name="state"]').val(address_data.State);
                $('[name="postal_code"]').val(address_data.Zip);
                $('[name="country"]').val(address_data.Country);
            }

            $('.cutpiece-section').removeClass('d-none');
        });

        if ($('.addresses-section input[type="radio"]').val() === 'undefined' || $('.addresses-section input[type="radio"]').val() !== 'other'){
            if(localStorage.getItem('shipping-address')){
                if(localStorage.getItem('shipping-address') == 'other') {
                    $('.addresses-section input[type="radio"]:last').click()
                    loadDropShip();
                }else{
                    $('.addresses-section input[type="radio"]:first').click();
                    removeDropShip();
                }
            }else{
                $('.addresses-section input[type="radio"]:first').click();
            }
        }
    }

    $('#countries').on('change', function () {
        let selectedOption = $(this).find('option:selected');
        let selectedCountry = selectedOption.attr('origincode');
        if (selectedCountry) {
            states(selectedCountry);
        }
    });

    $(document).on('change', '[name="shipping-address"]',function() {
        if ( $(this).val() == 'other' ) {
        $('.address-card').addClass('disabled-card');
        $(this).closest('.card-body').removeClass('disabled-card');
        } else {
        $('.address-card').removeClass('disabled-card');
        $('[name="shipping-address"][value="other"]').closest('.card-body').addClass('disabled-card');
        }
    }).change();

    // CUT PIECE BROADLOOM ITEM ID CHANGE
    $('#itemDropdown').on('change', function () {
        const selectedItemId = $(this).val();
        const $relatedDropdown = $('#rollDropdown');
        $relatedDropdown.empty();
        $relatedDropdown.prop('disabled', true);
        $relatedDropdown.append(`
            <option value="">Loading...</option>
        `);
        if (selectedItemId) {
            $.ajax({
                url: '/dashboard/fetch-item-id-data',
                method: 'POST',
                data: {
                    item_id: selectedItemId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('#item_json').val('');
                    $('#item_json').val(response.item_json);
                    $('#rug_pad_id').val(response.rug_pad);
                    item_object = JSON.parse($('#item_json').val());
                    if($('#rug_pad_id').val() != ''){
                        $('#add_rugpad').prop('disabled', false);
                        $.post('{{ route('frontend.item.ats') }}', {
                            _token: '{{ csrf_token() }}',
                            item_id: $('#rug_pad_id').val(),
                            customer_id: $('[name="customer_id"]').val(),
                        }, function (response) {
                            $('#rug_pad_price').val(response.data.Price);
                        });
                    }else{
                        $('#add_rugpad').prop('disabled', true).prop('checked', false);
                    }

                },
                error: function (xhr, status, error) {
                    console.error('Error fetching related data:', error);
                }
            });

            $.ajax({
                url: '/dashboard/fetch-item-roll-data',
                method: 'POST',
                data: {
                    item_id: selectedItemId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $relatedDropdown.empty();
                    $relatedDropdown.prop('disabled', false);
                    $relatedDropdown.append('<option value="" width="" length="">Select an option</option>');
                    $.each(response.data, function (key, value) {
                        $relatedDropdown.append(`
                            <option
                                value="${value.RollID}"
                                width="${value.TotalWidth}"
                                length="${value.ATSLength}"
                                SQFT="${value.TotalSQFT}"
                                cutpieceID="${value.CutPieceID}"
                                cutType="${value.CutType}"
                                location="${value.LocationID}">
                                ${value.RollID}
                                ${(value.CutPieceID ? `(${value.CutPieceID})` : '')} - (${Math.floor(value.ATSLength / 12)}' - ${value.ATSLength % 12}'')
                            </option>
                        `);
                    });
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching related data:', error);
                }
            });
        }
    });

    // CUT PIECE BROADLOOM ROLL ID CHANGE
    $('#rollDropdown').change(function () {
        sessionStorage.removeItem('roll_ats_lenght');
        input_lenght_ats = 0;
        $('#sq-ext').val('');
        $.ajax({
            method: 'POST',
            url: '{{ route('frontend.item.ats') }}',
            data: {
                '_token': '{{ csrf_token() }}',
                'item_id': $("#itemDropdown").val(),
                'customer_id': "{{ Auth::user()->is_customer }}" == 1 ? "{{ Auth::user()->customer_id }}" : $('[name="customer_id"]').val(),

            },
            success: function (response) {

                console.log('response here', response);
                var formattedprice= parseFloat(response.data['Price']).toFixed(2);
                $("#sq-ft").val(formattedprice);
                updatePrices();
                $.ajax({
                    url: "{{ route('broadloom.removeAllCutPiece') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        TempSalesOrderNo: null,
                        logged_user_no: '{{ isset(Auth::user()->spars_logged_user_no)? Auth::user()->spars_logged_user_no : '' }}',
                    },
                    type: 'POST',
                    success: function (response) {
                        console.log('all cut response on change', response);
                    }
                })
            },
            error: function (response) {
                console.log('error res', response);
            }
        });
        var selectedOption = $(this).find('option:selected');
        var width = selectedOption.attr('width');
        var length = selectedOption.attr('length');
        originalHeight = selectedOption.attr('length');
        ATS_ROLL_LENGHT = length;
        ATS_ROLL_WIDTH = width;
        let lengthfeet = Math.floor(length / 12);
        originalHeightInFeet = lengthfeet;
        let lengthinches = length % 12;
        originalHeightInInches = lengthinches;
        let widthfeet = Math.floor(width / 12);
        originalWidthInFeet = widthfeet;
        let widthinches = width % 12;
        originalWidthInInches = widthinches;

        $('.Twidth').val(widthfeet);
        $('.Tlength').val(lengthfeet);
        sessionStorage.setItem('roll_ats_lenght', lengthfeet);
        $('#TlengthInch').val(lengthinches);
        $('#TwidthInch').val(widthinches);
        $('.Tlength').val(lengthfeet);
        $('#lenght-width').text(`${lengthfeet}'-${lengthinches}''/`);
        $(".Tlength-max-error").addClass("d-none").text("");
        $(".Tlength").css('border-color', '');
        $(".Tlength").next('.input-group-prepend').find('.input-group-text').css({'border-color': '', 'background-color': '', 'color': ''});
        $(".Twidth-max-error").addClass("d-none").text("");
        $(".Twidth").css('border-color', '');
        $(".Twidth").next('.input-group-prepend').find('.input-group-text').css({'border-color': '', 'background-color': '', 'color': ''});

        $('#Tlength-ats-max').val(lengthfeet);
        $('#TlengthInch-ats-max').val(lengthinches);
        $('#Twidth-ats-max').val(widthfeet);
        $('#TwidthInch-ats-max').val(widthinches);

        $('#roll_id').val(selectedOption.attr('value'));
        $('#cutpiece_id').val(selectedOption.attr('cutpieceID'));
        $('#atslength').val(selectedOption.attr('length'));
        $('#totalwidth').val(selectedOption.attr('width'));
        $('#totalsqft').val(selectedOption.attr('SQFT'));
        $('#cuttype').val(selectedOption.attr('cutType'));
        $('#locationid').val(selectedOption.attr('location'));

    });

    $(".Tlength").on("input", function () {
        var max_len = parseFloat($("#Tlength-ats-max").val());
        var max_inch = $("#TlengthInch-ats-max").val();
        var current_val = parseFloat($(this).val());

        var lengthinch = $('#TlengthInch').val();
        var max_len_inch = parseFloat($("#TlengthInch-ats-max").val());

        if(current_val > max_len){
            $(".Tlength-max-error").removeClass("d-none").text(`(Value cannot be greater than ${max_len}'${max_len_inch}")`)
            $('#Tlength').css('border-color', 'red');
            $('.TlengthGroup').css({'border-color': 'red', 'background-color': 'red', 'color': 'white'});
        }else{
            $(".Tlength-max-error").addClass("d-none").text("");
            $('#Tlength').css('border-color', '');
            $('.TlengthGroup').css({'border-color': '', 'background-color': '', 'color': ''});
        }

        if ($(this).val() < originalHeightInFeet) {
            $("#TlengthInch").attr("max", 11);
        }
        updatePrices();
    });

    $(".Twidth").on("input", function () {
        var max_width = parseFloat($("#Twidth-ats-max").val());
        var max_width_inch = $("#TwidthInch-ats-max").val();
        var current_val = parseFloat($(this).val());

        var widthinch = $('#TwidthInch').val();
        var max_width_inch = parseFloat($("#TwidthInch-ats-max").val());

        if(current_val > max_width){
            $(".Twidth-max-error").removeClass("d-none").text(`(Value cannot be greater than ${max_width}'${max_width_inch}")`);
            $('#Twidth').css('border-color', 'red');
            $('.TwidthGroup').css({'border-color': 'red', 'background-color': 'red', 'color': 'white'});
        }else{
            $(".Twidth-max-error").addClass("d-none").text("");
            $('#Twidth').css('border-color', '');
            $('.TwidthGroup').css({'border-color': '', 'background-color': '', 'color': ''});
        }

        if ($(this).val() < originalWidthInFeet) {
            $("#TwidthInch").attr("max", 11);
        }
        updatePrices();
    });

    $("#TlengthInch, #TwidthInch").on("change", function () {
        var length = parseFloat($(".Tlength").val());
        var max_len = parseFloat($("#Tlength-ats-max").val());
        var lengthinch = $('#TlengthInch').val();
        var max_len_inch = parseFloat($("#TlengthInch-ats-max").val());

        var width = parseFloat($(".Twidth").val());
        var max_width = parseFloat($("#Twidth-ats-max").val());
        var widthinch = $('#TwidthInch').val();
        var max_width_inch = parseFloat($("#TwidthInch-ats-max").val());
        if((length == max_len || length > max_len) && lengthinch > max_len_inch){
            $(".Tlength-max-error").removeClass("d-none").text(`(Value cannot be greater than ${max_len}'${max_len_inch}")`);
            $('#TlengthInch').css('border-color', 'red');
            $('.TlengthInchGroup').css({'border-color': 'red', 'background-color': 'red', 'color': 'white'});
        }else{
            $(".Tlength-max-error").addClass("d-none").text("");
            $('#TlengthInch').css('border-color', '');
            $('.TlengthInchGroup').css({'border-color': '', 'background-color': '', 'color': ''});
        }

        if((width == max_width || width > max_width) && widthinch > max_width_inch){
            $(".Twidth-max-error").removeClass("d-none").text(`(Value cannot be greater than ${max_width}'${max_width_inch}")`);
            $('#TwidthInch').css('border-color', 'red');
            $('.TwidthInchGroup').css({'border-color': 'red', 'background-color': 'red', 'color': 'white'});
        }else{
            $(".Twidth-max-error").addClass("d-none").text("");
            $('#TwidthInch').css('border-color', '');
            $('.TwidthInchGroup').css({'border-color': '', 'background-color': '', 'color': ''});
        }

        updatePrices();
    });

    // SURGING CHECKBOX AND SELECT ENABLE DISBALE
    $('#surging_check').change(function () {
        const isChecked = $(this).is(':checked');
        if (isChecked) {
            $('#surging_options').prop('disabled', false);
        } else {
            $('#surging_options').prop('disabled', true);
            $('#surging_options').val(0);
            $('#surging_charges').val("");
            $('#Twidth').val( $('#Twidth-ats-max').val() );
            $('#TwidthInch').val( $('#TwidthInch-ats-max').val() );
        }
        $('#Twidth').prop('disabled', !isChecked).attr('title', isChecked ? '' : 'Select Serging to Cut by Width');
        $('#TwidthInch').prop('disabled', !isChecked).attr('title', isChecked ? '' : 'Select Serging to Cut by Width');
    });

    $('#surging_options').change(function () {
        var selectedOption = $(this).find('option:selected');
        charges = selectedOption.attr('charges');
        $('#surging_charges').val(charges);
        $('#charges').val(charges);
        $('#sergingtypeno').val(selectedOption.attr('value'));
        $('#desc').val(selectedOption.attr('desc'));
    });

    // RUG-PAD CFA
    $('#add_rugpad').on('change', function() {
        if (this.checked) {
            $('#cfa_check').prop('checked', false).prop('disabled', true);
        } else {
            $('#cfa_check').prop('disabled', false);
        }
    });

    // ADD CUT PIECE
    $('#cut_piece_btn').click(function () {
        add_cut_pieces();
    });

    // ADD TO CART
    $('.add_cart').click(function () {

        let rollDropdown = $("#rollDropdown").val();
        let itemDropdown = $("#itemDropdown").val();
        let Tlength = $("#Tlength").val();
        let TlengthInch = $("#TlengthInch").val();
        let Twidth = $("#Twidth").val();
        let TwidthInch = $("#TwidthInch").val();
        let isValid = true;

        if (!rollDropdown) {
            toastr.error("Please select a Roll");
            isValid = false;
            return false;
        }
        if (!itemDropdown) {
            toastr.error("Please select an Item.");
            isValid = false;
            return false;
        }

        if (!Tlength) {
            toastr.error("Please enter Length.");
            isValid = false;
            $('.add_cart').prop('disabled', true);
            return false;
        }
        if (!TlengthInch) {
            toastr.error("Please enter Length Inches");
            isValid = false;
            return false;
        }
        if (!Twidth) {
            toastr.error("Please enter Width.");
            isValid = false;
            return false;
        }
        if (!TwidthInch) {
            toastr.error("Please enter Width Inches.");
            isValid = false;
            return false;
        }

        if (!isValid) {
            return false;
        }else{
            pushToCart();
        }

    });

    // PLACE ORDER
    $('#proceed_to_order').on('click', function(){
        $('.place-order-form').trigger('submit');
    });

    $('.place-order-form').on('submit', function() {
        var allOk = true;

        $('input[data-required="true"], select[data-required="true"]').each(function() {
        if ($(this).is(':visible')) {
            if (typeof $(this).val().length === 'undefined') {
                $('html, body').animate({ scrollTop: $(this).offset().top - 20 }, 1000);
                $(this).addClass('is-invalid');
                allOk = false;
            } else if ($(this).val().trim().length < 1) {
                $('html, body').animate({ scrollTop: $(this).offset().top - 20 }, 100);
                $(this).addClass('is-invalid');
                allOk = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        }
        });

        if (allOk && $('input[type="email"]').is(':visible') && !validateEmail($('input[type="email"]').val())) {
            $('html, body').animate({ scrollTop: $(this).offset().top - 20 }, 100);
            $('input[type="email"]').addClass('is-invalid');
            allOk = false;
        }

        if ( $('input[name="customer_id"]').is(':visible') && !parseInt($('input[name="customer_id"]').val()) ) {
            $('html, body').animate({ scrollTop: $(this).offset().top - 20 }, 100);
            $('input[name="customer_id"]').addClass('is-invalid');
            allOk = false;
        } else {
            $('input[name="customer_id"]').removeClass('is-invalid');
        }


        if ($("#countries").val() == "") {
            $("#countries").addClass('is-invalid');
            $('html, body').animate({ scrollTop: $("#countries").offset().top - 20 }, 100);
            allOk = false;
        }

        if (allOk && $("#state_dropdown").val() == "") {
            $("#state_dropdown").addClass('is-invalid');
            $('html, body').animate({ scrollTop: $("#state_dropdown").offset().top - 20 }, 100);
            allOk = false;
        }

        return allOk;
    });




});

function add_cut_pieces() {
    if($('#surging_check').is(':checked') && $('#surging_options').val() == 0){
        toastr.error('Kindly choose serging type', {
            hideDuration: 10000,
            closeButton: true,
        });
        return true;
    }
    if(!$(".Tlength-max-error").hasClass("d-none") || !$(".Twidth-max-error").hasClass("d-none")){
        toastr.error('Lenght/Width must not be greater than ATS ROLL', {
            hideDuration: 10000,
            closeButton: true,
        });
        return true;
    }
    let actual_length = parseInt($("#Tlength").val());
    let actual_width = parseInt($("#Twidth").val());
    var length_inch = parseInt($("#TlengthInch").val());
    var width_inch = parseInt($("#TwidthInch").val());
    let length = actual_length * 12 + parseInt($("#TlengthInch").val());
    let width = actual_width * 12 + parseInt($("#TwidthInch").val());
    let sqtft = parseFloat(actual_length + "." + $("#TlengthInch").val()) * parseFloat(actual_width + "." + $("#TwidthInch").val());

    if (isNaN(actual_length) || isNaN(actual_width) || isNaN(length_inch) || isNaN(width_inch) ||
            $("#Tlength").val().trim() === '' || $("#Twidth").val().trim() === '' ||
            $("#TlengthInch").val().trim() === '' || $("#TwidthInch").val().trim() === '') {
            toastr.error('Lenght/Width  (feet/inches)  are required', {
                hideDuration: 10000,
                closeButton: true,
            });
            return true;
    }

    let roll_ats_lenght = sessionStorage.getItem('roll_ats_lenght');
    input_lenght_ats += actual_length;
    if (input_lenght_ats > roll_ats_lenght) {
        input_lenght_ats -= actual_length;
        toastr.error('Roll ATS not available', {
            hideDuration: 10000,
            closeButton: true,
        });
        return true;
    }
    $data= {
            '_token': '{{ csrf_token() }}',
            'roll_id': $("#rollDropdown").val(),
            'tempsalesorderno': $("#TempSalesOrderNo").val(),
            'item_id':  $("#itemDropdown").val(),
            'cutpiece_id': $("#cutpiece_id").val(),
            'atslength': length,
            'totalwidth': width,
            'totalsqft': sqtft,
            'cuttype': $("#cuttype").val(),
            'locationid': $("#locationid").val(),
            'charges': $("#charges").val(),
            'desc': "",
            'waste': "N",
            'Remnant': "N",
            'AvailableForSale': "",
            'IsremnantShipable': "",
            'LineNo': "1",
            'UserRemarks': $("#cust-inst").val(),
            'sergingtypeno': $("#sergingtypeno").val(),
            'logged_user_no': '{{ Auth::user()->spars_logged_user_no }}'
        };
    $("#cust-inst").val('');
    $.ajax({
        url: "{{ route('broadloom.cutPiece') }}",
        method: 'POST',
        data: $data,
        success: function (data) {
            if (data.cut_piece.OutPut.Success) {
                $("#TempSalesOrderNo").val(data['cut_piece']['OutPut']['AddCutPieces'][0]['TempSalesOrderNo'])
                var divContent = '<input type="hidden" id="size_price" name="size_price[]" value=""></input<div>';
                var sizes = [];
                var line_no = 1;
                let totalLen = 1;
                let totalWid = 1;
                let totalSqftPrice = 0;
                let totalMaxLen = 0;
                let totalMaxLenSerg = 0;
                let lenInchCal = 0;
                let widInchCal = 0;
                let lenInchSergCal = 0;
                let widInchSergCal = 0;
                let totalAddWidSerg = 0;
                let totalAddWid = 0;
                let totalAddLen = 0;
                let totalAddLenSerg = 0;
                let lenghtWithInches = 0;
                let widthWithInches = 0;
                let lenghtWithInchesSerg = 0;
                let widthWithInchesSerg = 0;
                let mxlenf = 0;
                let mxlen = 0;
                let unitpriceCal = 0;

                $.each(data['cut_piece']['OutPut']['AddCutPieces'], function (index, item) {
                    let lengthFeet = Math.floor(item.ATSLength / 12);
                    let lengthInches = item.ATSLength % 12;
                    let widthFeet = Math.floor(item.ATSWidth / 12);
                    let widthInches = item.ATSWidth % 12;

                    console.log(`cut piece serging:  ${item}`);


                    let surging = '';
                    if (item.SergingType != "0") {
                        surging = ' Serging';
                        console.log('in serging add');
                        unitpriceCal += parseFloat($('#unit-price').val());
                    }
                    //console.log('unitpriceCal', unitpriceCal);

                    mxlenf = mxlenf + lengthFeet;
                    mxlen = mxlen + lengthInches;

                    lenInchCal = (lengthInches * 0.0833333);
                    widInchCal = (widthInches * 0.0833333);
                    lenghtWithInches = parseFloat((lengthFeet + lenInchCal));
                    widthWithInches = parseFloat((widthFeet + widInchCal));

                    if (lenghtWithInches > totalMaxLen) {
                        totalMaxLen = lenghtWithInches;
                    }

                    totalLen *= lengthFeet;
                    totalWid *= widthFeet;
                    totalAddWid += widthWithInches;

                    var color = item.LengthStatus == 'F' ? 'Blue' : '#660000';
                    var item_id = $("#itemDropdown").val();
                    divContent +=
                        '<div class="badge badge-default broadloom-badge mx-2 my-2" id="' + item_id + '_' + item.CutPieceID + '_' + item.RollID + '_' + item.CPTempLine_No + '" style="background-color:' +
                        color + '">';
                    var size = {};
                    size.size = widthFeet + `'` + widthInches + `" x ` + lengthFeet + `'` +
                        lengthInches + `"` + surging;
                    divContent += size.size;
                    divContent += '<a  href="javascript:void(0)" onclick="removeCutPiece(`' + item_id + '_' + item.CutPieceID + '_' + item.RollID + '_' + item.CPTempLine_No + '`, `' + item.CutPieceID + '`, `' + item.RollID + '`, `' + item.CPTempLine_No + '`,  `' + item.LengthStatus + '`,  `' + lengthFeet + '`,  `' + widthFeet + '`)" style="background: ' + color + '"><i class="fa fa-times"></i></a></div>';
                    let totalLengthInInches = lengthFeet * 12 + lengthInches;
                    let totalWidthInInches = widthFeet * 12 + widthInches;

                    // Calculate total area in square inches
                    let totalAreaInSquareInches = totalLengthInInches * totalWidthInInches;
                    // Convert square inches to square feet
                    let totalAreaInSquareFeet = totalAreaInSquareInches /
                        144; // 1 square foot = 144 square inches
                    // Convert square feet to square yards
                    let totalAreaInSquareYards = totalAreaInSquareFeet /
                        9; // 1 square yard = 9 square feet
                    // Calculate the SQ-YRD Price ($) and EXT Price ($)
                    let sqYrdPrice = $("#sq-ft").val() / 9; // Price per square yard
                    let extPrice = totalAreaInSquareYards * sqYrdPrice;
                    // size.price = extPrice.toFixed(2);

                    if (item.LengthStatus == 'F') {
                        sizes.push(size);
                    }
                    line_no = line_no + 1;
                });

                $('#surging_check').prop('checked', false);
                $('#surging_options').prop('disabled', true);
                $('#Twidth').prop('disabled', true);
                $('#TwidthInch').prop('disabled', true);
                $('#surging_options').val('0')
                $('#surging_charges').val('');
                $("#sergingtypeno").val('');
                $('#Twidth').val( $('#Twidth-ats-max').val() );
                $('#TwidthInch').val( $('#TwidthInch-ats-max').val() );

                if(data?.cut_piece?.OutPut?.AddCutPieces?.[0]?.TotalUsedLength){
                    var totalUsedLength  = data['cut_piece']['OutPut']['AddCutPieces'][0]['TotalUsedLength'];
                    var finallength = (totalUsedLength / 12); // Converting Length to Feet:
                    var widthMaxRollFeet = parseFloat($('#Twidth-ats-max').val());
                    var widthMaxRollInch = parseFloat($('#TwidthInch-ats-max').val());
                    var widthInchesToFeet = parseFloat(widthMaxRollInch) / 12 // Converts width from inches to feet.
                    var finalWidth =  widthMaxRollFeet + widthInchesToFeet; // Adds the converted inches to width in feet to get the total width in feet.
                    totalSqftPrice = finallength * finalWidth;
                    //console.log('totalSqftPrice', totalSqftPrice.toFixed(2));
                }else{
                    totalSqftPrice = 0;
                }

                $("#ats-qty").val(totalSqftPrice.toFixed(2));
                $('#max-width').text(`${mxlenf}'-${mxlen % 12}''`);
                updatePrices();

                divContent += `</div>`;

                $('#cut_piece_parent').html(divContent);
                $('#size_price').val(JSON.stringify(sizes));

                item_object.CutPieces = data['cut_piece']['OutPut']['AddCutPieces'];
                $('#cut_pieces_json').val(JSON.stringify(data['cut_piece']['OutPut']['AddCutPieces']));
                $('#item_json').val(JSON.stringify(item_object));
                toastr.success(data.cut_piece.OutPut.Message, {
                    hideDuration: 10000,
                    closeButton: true,
                });
                $('#rollDropdown').prop('disabled', true);
                unitpriceCal = unitpriceCal.toFixed(2);
                $('#unit-price-cal').val(unitpriceCal);
            } else {
                //console.log('data.cut_piece.OutPut.Message', data.cut_piece.OutPut.Message);
                toastr.error(data.cut_piece.OutPut.Message, {
                    hideDuration: 10000,
                    closeButton: true,
                });
                var cutpieces = data['cut_piece']['OutPut']['AddCutPieces'];
                input_lenght_ats  = 0;
            }

        },
        error: function (xhr, status, error) {
            console.error("Error occurred:", status, error);
        }
    });
}

function removeCutPiece(id, cut_piece_id, roll_id, line_no, lenghtStatus, lengthfeet, widthfeet) {
    input_lenght_ats -= lengthfeet;
    if (lenghtStatus != 'F') {
        toastr.error('Remnant cannot be removed', {
            hideDuration: 10000,
            closeButton: true,
        });
    } else {
        $.ajax({
            url: "{{ route('broadloom.removeCutPiece') }}",
            data: {
                _token: "{{ csrf_token() }}",
                TempSalesOrderNo: $('#TempSalesOrderNo').val(),
                RollID: roll_id,
                CutPieceID: cut_piece_id,
                line_no: line_no,
                logged_user_no: '{{ Auth::user()->spars_logged_user_no }}',
            },
            type: 'POST',
            success: function (response) {
                console.log('response',response);

                var cutpieceLen = response['OutPut']['AddCutPieces'];
                if (cutpieceLen.length == 0) {
                    $('#show-cut-piece-btn').addClass('d-none');
                    $('#add_to_cart').addClass('d-none');
                    $('#rollDropdown').prop("disabled", false);
                }
                if (response['OutPut']['Success']) {
                    $('#cut_piece_parent').empty();
                    var divContent = '<input type="hidden" id="size_price" name="size_price[]" value=""></input<div>';
                    var sizes = [];
                    var line_no = 1;
                    let totalLen = 1;
                    let totalWid = 1;
                    let totalSqftPrice = 0;
                    let totalMaxLen = 0;
                    let lenInchCal = 0;
                    let widInchCal = 0;
                    let totalAddWid = 0;
                    let lenghtWithInches = 0;
                    let widthWithInches = 0;
                    let mxlenf = 0;
                    let mxlen = 0;
                    let unitpriceCal = 0;
                    $('#unit-price-cal').val('');

                    $.each(response['OutPut']['AddCutPieces'], function (index, item) {
                        console.log('rm cut piece res', response['OutPut']['AddCutPieces']);
                        let lengthFeet = Math.floor(item.ATSLength / 12);
                        let lengthInches = item.ATSLength % 12;
                        let widthFeet = Math.floor(item.ATSWidth / 12);
                        let widthInches = item.ATSWidth % 12;

                        mxlenf = mxlenf + lengthFeet;
                        mxlen = mxlen + lengthInches;

                        let surging = '';
                        if (item.SergingType != "0" && item.LengthStatus != "R") {
                            surging = ' Serging';
                            unitpriceCal += parseFloat($('#unit-price').val());
                        }
                        console.log('unitpriceCal', unitpriceCal);

                        lenInchCal = (lengthInches * 0.0833333);
                        widInchCal = (widthInches * 0.0833333);
                        lenghtWithInches = parseFloat((lengthFeet + lenInchCal));
                        widthWithInches = parseFloat((widthFeet + widInchCal));

                        if (lenghtWithInches > totalMaxLen) {
                            totalMaxLen = lenghtWithInches;
                        }

                        totalLen *= lengthFeet;
                        totalWid *= widthFeet;
                        totalAddWid += widthWithInches;

                        var color = item.LengthStatus == 'F' ? 'Blue' : '#660000';

                        var item_id = $("#itemDropdown").val();
                        divContent +=
                            '<div class="badge badge-default broadloom-badge mx-2 my-2" id="' + item_id + '_' + item.CutPieceID + '_' + item.RollID + '_' + item.CPTempLine_No + '" style="background-color:' +
                            color + '">';
                        var size = {};
                        item.LengthStatus == "B" ?
                            size.size = lengthFeet + `'` + lengthInches + `" x ` + widthFeet + `'` +
                            widthInches + `"` :
                            size.size = widthFeet + `'` + widthInches + `" x ` + lengthFeet + `'` +
                            lengthInches + `"` + surging;

                        divContent += size.size;
                        divContent += '<a  href="javascript:void(0)" onclick="removeCutPiece(`' + item_id + '_' + item.CutPieceID + '_' + item.RollID + '_' + item.CPTempLine_No + '`, `' + item.CutPieceID + '`, `' + item.RollID + '`, `' + item.CPTempLine_No + '`,  `' + item.LengthStatus + '`,  `' + lengthFeet + '`,  `' + widthFeet + '`)" style="background: ' + color + '"><i class="fa fa-times"></i></a></div>';
                        let totalLengthInInches = lengthFeet * 12 + lengthInches;
                        let totalWidthInInches = widthFeet * 12 + widthInches;

                        // Calculate total area in square inches
                        let totalAreaInSquareInches = totalLengthInInches * totalWidthInInches;

                        // Convert square inches to square feet
                        let totalAreaInSquareFeet = totalAreaInSquareInches /
                            144; // 1 square foot = 144 square inches

                        // Convert square feet to square yards
                        let totalAreaInSquareYards = totalAreaInSquareFeet /
                            9; // 1 square yard = 9 square feet

                        // Calculate the SQ-YRD Price ($) and EXT Price ($)
                        let sqYrdPrice = $("#sq-ft").val() / 9; // Price per square yard
                        let extPrice = totalAreaInSquareYards * sqYrdPrice;
                        // size.price = extPrice.toFixed(2);

                        if (item.LengthStatus == 'F') {
                            console.log('in size');
                            sizes.push(size);
                        }
                        line_no = line_no + 1;
                    });

                    //  totalSqftPrice = (totalMaxLen * totalAddWid);
                    if( response?.OutPut?.AddCutPieces?.[0]?.TotalUsedLength ){
                        var totalUsedLength  = response['OutPut']['AddCutPieces'][0]['TotalUsedLength'];
                        var finallength = (totalUsedLength / 12); // Converting Length to Feet:
                        var widthMaxRollFeet = parseFloat($('#Twidth-ats-max').val());
                        var widthMaxRollInch = parseFloat($('#TwidthInch-ats-max').val());
                        var widthInchesToFeet = parseFloat(widthMaxRollInch) / 12 // Converts width from inches to feet.
                        var finalWidth =  widthMaxRollFeet + widthInchesToFeet; // Adds the converted inches to width in feet to get the total width in feet.
                        totalSqftPrice = finallength * finalWidth;
                        console.log('totalSqftPrice', totalSqftPrice.toFixed(2));
                    }else{
                        totalSqftPrice = 0;
                    }


                    $("#ats-qty").val(totalSqftPrice.toFixed(2));
                    $('#max-width').text(`${mxlenf}'-${mxlen % 12}'`);
                    updatePrices();

                    divContent += `</div>`;

                    $('#cut_piece_parent').html(divContent);
                    $('#size_price').val(JSON.stringify(sizes));
                    item_object.CutPieces = response['OutPut']['AddCutPieces'];
                    $('#cut_pieces_json').val(JSON.stringify(response['OutPut']['AddCutPieces']));
                    $('#item_json').val(JSON.stringify(item_object));
                    unitpriceCal = unitpriceCal.toFixed(2);
                    $('#unit-price-cal').val(unitpriceCal);

                    toastr.success('Cut Piece Removed', {
                        hideDuration: 10000,
                        closeButton: true,
                    });

                    if(response['OutPut']['AddCutPieces'].length > 0){
                        $('#show-cut-piece-btn').click();
                        setTimeout(() => {
                            $("#hide-cut-piece-btn").removeClass('d-none').prop('disabled', false);
                        }, 1000);
                    }else{
                        $("#hide-cut-piece-btn").addClass('d-none').prop('disabled', false);
                        $(".cut-pieces-wrapper").hide();
                    }
                } else {
                    toastr.error('Remnant cannot be removed', {
                        hideDuration: 10000,
                        closeButton: true,
                    });
                }
            }
        })
    }
}

function updatePrices() {
    let perSquareFeetPrice = $("#sq-ft").val()
    let lengthFeet = parseInt($("#Tlength").val());
    let lengthInches = parseInt($("#TlengthInch").val());
    let widthFeet = parseInt($("#Twidth").val());
    let widthInches = parseInt($("#TwidthInch").val());
    let totalLengthInInches = lengthFeet * 12 + lengthInches;
    let totalWidthInInches = widthFeet * 12 + widthInches;
    let totalAreaInSquareInches = totalLengthInInches * totalWidthInInches;
    let totalAreaInSquareFeet = totalAreaInSquareInches / 144;
    let totalAreaInSquareYards = totalAreaInSquareFeet / 9;


    let sqYrdPrice = perSquareFeetPrice * 9;

    let extPrice = $("#sq-ft").val() * $("#ats-qty").val();
    let extpriceRug = $("#rug_pad_price").val() * $("#ats-qty").val();

    console.log('extPrice',extPrice);

    var formatExt =  extPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    $("#sq-ext").val(formatExt);
    $('#without-format-sq-ext').val(parseFloat(extPrice).toFixed(2));
    $('#rug_pad_price_ext').val(parseFloat(extpriceRug).toFixed(2));
    $("#sq-yrd").val( sqYrdPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) );
}

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
                    console.log($('#customer_state').val());
                    if (value.StateCode == $('#customer_state').val()) {
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

function GetCutingService(){
    $.ajax({
        url: '{{ route('broadloom.GetCutingService') }}',
        method: 'GET',
        success: function(response) {
            unitprice = parseFloat(response.OutPut.UnitPrice).toFixed(2);
            $("#unit-price").val(unitprice);
        },
        error: function(error) {
            console.error('Error occurred in GetCutingService call : ', error);
        }
    });
}

function getDimensionsInInches(sizeStr) {
    let [widthStr, lengthStr] = sizeStr.split(' x ');

    let [widthFeet, widthInches] = widthStr.split("'");
    let [lengthFeet, lengthInches] = lengthStr.split("'");

    widthFeet = parseInt(widthFeet);
    widthInches = parseInt(widthInches.replace('"', ''));
    lengthFeet = parseInt(lengthFeet);
    lengthInches = parseInt(lengthInches.replace('"', ''));

    let totalWidthInches = (widthFeet * 12) + widthInches;
    let totalLengthInches = (lengthFeet * 12) + lengthInches;

    return {
        width: totalWidthInches,
        length: totalLengthInches,
        originalSize: sizeStr
    };
}

function pushToCart() {
    item = JSON.parse($('#item_json').val());
    console.log('PUHS TO CART item', item);
    let surging_type = $('#surging_options').val() ? $('#surging_options').val() : "0";
    item.SQFTPrice = $('#sq-ft').val();
    item.SQFTArea = $('#totalsqft').val();
    item.CutPieceID = $('#cutpiece_id').val();
    item.RollID = $("#roll_id").val();
    item.SergingCharges = $('#surging_charges').val();
    item.SergingType = surging_type;
    item.location_id = $('#locationid').val();
    item.cut_type = $('#cuttype').val();
    item.Serging = $('#surging_check').is(':checked') ? 'Y' : 'N'
    $('#item_json').val(JSON.stringify(item));

    let jsonString = $('#size_price').val();
    let sizesArray = JSON.parse(jsonString);

    let dimensionsInInches = sizesArray.map(item => getDimensionsInInches(item.size));
    // console.log('dimensionsInInches', dimensionsInInches);

    var bd_cutpiece_len = 0;
    var bd_cutpiece_wid = 0;
    for (var i = 0; i < dimensionsInInches.length; i++) {
        bd_cutpiece_len += dimensionsInInches[i].length;
        bd_cutpiece_wid += dimensionsInInches[i].width;
    }

    var payload = {
        itemId: item.ItemID,
        customerId: $('[name="customer_id"]').val(),
        rollid: $("#rollDropdown").val()
    };

    $.ajax({
        url: '/broad-loom-full-size',
        method: 'GET',
        data: payload,
        success: function (response) {
            console.log('response',response);
            if (response.success) {
                var rollIdForError = $("#rollDropdown").val();
                if ((parseInt(response.bd_cutpiece_len) + parseInt(bd_cutpiece_len)) > ATS_ROLL_LENGHT) {
                    toastr.error(`The selected length of the roll (${rollIdForError}) has already been consumed.`, {
                        hideDuration: 10000,
                        closeButton: true,
                    });
                    $('#add_to_cart').removeClass('btn-muted');
                    return false;
                } else {
                    add_to_cart();
                }
            }

            if (!response.success) {
                console.log("Till add to cart")
               // add_to_cart();
            }
        },
        error: function (error) {
            console.error('Check Size:', error);
        }
    });
}

function add_to_cart(){
    $('#add_rugpad').prop('disabled', true);
    item = JSON.parse($('#item_json').val());
    let surging_type = $('#surging_options').val() ? $('#surging_options').val() : "0";
    item.SQFTPrice = $('#sq-ft').val();
    item.SQFTArea = $('#totalsqft').val();
    item.CutPieceID = $('#cutpiece_id').val();
    item.RollID = $("#rollDropdown").val();
    item.SergingCharges = $('#surging_charges').val();
    item.SergingType = surging_type;
    item.location_id = $('#locationid').val();
    item.cut_type = $('#cuttype').val();
    item.Serging = $('#surging_check').is(':checked') ? 'Y' : 'N'
    console.log(`item before:  ${item}`);

    $('#item_json').val(JSON.stringify(item));
    let jsonString = $('#size_price').val();
    let sizesArray = JSON.parse(jsonString);
    let customer_instruction = $('#cust-inst').val();

    let dimensionsInInches = sizesArray.map(item => getDimensionsInInches(item.size));
    let maxLengthObj = dimensionsInInches.reduce((maxObj, currentObj) => {
        return currentObj.length > maxObj.length ? currentObj : maxObj;
    }, dimensionsInInches[0]);
    let maxLengthFeet = Math.floor(maxLengthObj.length / 12);
    let maxLengthInches = maxLengthObj.length % 12;

    let maxWidthFeet = Math.floor(maxLengthObj.width / 12);
    let maxWidthInches = maxLengthObj.width % 12;

    var max_len_size = `${maxWidthFeet}'${maxWidthInches}" x ${maxLengthFeet}'${maxLengthInches}"`;

    var bd_cutpiece_len = 0;
    var bd_cutpiece_wid = 0;
    for (var i = 0; i < dimensionsInInches.length; i++) {
        bd_cutpiece_len += dimensionsInInches[i].length;
        bd_cutpiece_wid += dimensionsInInches[i].width;
    }

    var cfa_check = $("#cfa_check").is(":checked") ? 1 : 0;
    var remnant_check = $("#remnant_check").is(":checked") ? 1 : 0;
    var randomString = Math.random().toString(36).substring(2, 12);

    $.ajax({
        url: "{{ route('check-cart-item') }}",
        type: "GET",
        success: function (response) {
            if (response) {
                if (confirm('Rugs item is already in the cart, adding this item will remove the previous Rugs item from your cart, are you sure you want to proceed ?')) {
                    $.ajax({
                        url: "{{ route('delete-cart-items') }}",
                        type: "GET",
                        success: function (response) {
                            if (response) {
                                //  ADD TO CART API HIT
                                $.ajax({
                                    method: 'POST',
                                    url: '{{ route('frontend.cart.add') }}',
                                    data: {
                                        '_token': '{{ csrf_token() }}',
                                        'cart_item_id': item.ItemID,
                                        'cart_customer_id': $('[name="customer_id"]').val(),
                                        'cart_item_name': item.ItemName,
                                        'cart_item_quantity': 1,
                                        'cart_item_color': item.ItemColor,
                                        'cart_item_size': max_len_size,
                                        'cart_item_price': $("#without-format-sq-ext").val(),
                                        'item_surging_price': $('#surging_charges').val(),
                                        'cart_item_currency': '$',
                                        'cart_item_image': item.ImageNameArray && item.ImageNameArray.length > 0 ? item.ImageNameArray[0] : 'https://media.momeni.com/Full_Img/',
                                        'cart_item_data': $('#item_json').val(),
                                        'cart_item_broadloom': 1,
                                        'logged_user_no': '{{ Auth::user()->spars_logged_user_no }}',
                                        'temp_sales_order_no': $('#TempSalesOrderNo').val(),
                                        'bd_roll_id': $("#rollDropdown").val(),
                                        'bd_cutpiece_len': bd_cutpiece_len,
                                        'bd_cutpiece_wid': bd_cutpiece_wid,
                                        'user_remarks': customer_instruction,
                                        'cfa': cfa_check,
                                        'remnant_shipable': remnant_check,
                                        'unit_price': $("#unit-price-cal").val(),
                                        'sqft_area': $("#ats-qty").val(),
                                        'rand_str': randomString,
                                        'is_bd_child': 0,
                                        'rugpad_price': $('#add_rugpad').is(':checked') ? $('#rug_pad_price_ext').val() : '',
                                    },
                                    success: function (response) {
                                        if (response.success) {
                                            console.log("new ", $('#item_json').length);
                                            if ($('#add_rugpad').is(':checked')) {
                                                add_rug_pad(item, max_len_size, bd_cutpiece_len, bd_cutpiece_wid, randomString)
                                            }
                                            toastr.success(response.message, {
                                                        hideDuration: 10000,
                                                        closeButton: true,
                                                    });
                                                    $('#add_to_cart').removeClass('btn-muted');

                                                    $('#cut_piece_parent .broadloom-badge').remove();
                                                    $('#rollDropdown').val('');
                                                    $('#Tlength').val('');
                                                    $('#TlengthInch').val('');
                                                    $('#Twidth').val('');
                                                    $('#TwidthInch').val('');
                                                    $('#sq-ft').val('');
                                                    $('#sq-yrd').val('');
                                                    $('#sq-ext').val('');
                                                    $('#surging_options').val('');
                                                    $('#surging_options').prop('disabled', true);
                                                    $('#surging_check').prop('checked', true);
                                                    $('#surging_charges').val('');
                                                    $('#cust-inst').val('');
                                                    $("#cfa_check").prop("checked", false);
                                                    $("#remnant_check").prop("checked", false);
                                                    $('#rollDropdown').removeAttr("disabled");
                                                    $('#cut-pieces').empty();
                                                    $('#add_rugpad').prop('disabled', false).prop('checked', false);
                                                    $('#Twidth').prop('disabled', true);
                                                    $('#TwidthInch').prop('disabled', true);
                                                    $('.add_cart').prop('disabled', false);
                                                    setTimeout(() => {
                                                      window.location.reload();
                                                    }, 3000);
                                                    $.ajax({
                                                        url: "{{ route('broadloom.removeAllCutPiece') }}",
                                                        data: {
                                                            _token: "{{ csrf_token() }}",
                                                            TempSalesOrderNo: null,
                                                            logged_user_no: '{{ isset(Auth::user()->spars_logged_user_no)? Auth::user()->spars_logged_user_no : '' }}',
                                                        },
                                                        type: 'POST',
                                                        success: function (response) {
                                                            console.log('all cut response on change', response);
                                                        }
                                                    })
                                        } else {
                                            toastr.warning(response.message, {
                                                hideDuration: 10000,
                                                closeButton: true,
                                            });
                                            $('#add_to_cart').removeClass('btn-muted');
                                        }
                                    },
                                    error: function (response) {
                                        toastr.warning(response.message, {
                                            hideDuration: 10000,
                                            closeButton: true,
                                        });
                                        $('#add_to_cart').removeClass('btn-muted');
                                    }
                                });
                            } else {
                                toastr.error('Someting went wrong', {
                                    hideDuration: 10000,
                                    closeButton: true,
                                });
                            }
                        }
                    });
                }
            } else {
                //  ADD TO CART API HIT
                console.log(`else add: ${JSON.stringify(item)}`);
                console.log(item.ItemID);
                $.ajax({
                    method: 'POST',
                    url: '{{ route('frontend.cart.add') }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'cart_item_id': item.ItemID,
                        'cart_customer_id': $('[name="customer_id"]').val(),
                        'cart_item_name': item.ItemName,
                        'cart_item_quantity': 1,
                        'cart_item_color': item.ItemColor,
                        'cart_item_size': max_len_size,//$('#size_price').val(),
                        'cart_item_price': $("#without-format-sq-ext").val(),
                        'item_surging_price': $('#surging_charges').val(),
                        'cart_item_currency': '$',
                        'cart_item_image': item.ImageNameArray && item.ImageNameArray.length > 0 ? item.ImageNameArray[0] : 'https://media.momeni.com/Full_Img/',
                        'cart_item_data': $('#item_json').val(),
                        'cart_item_broadloom': 1,
                        'logged_user_no': '{{ Auth::user()->spars_logged_user_no }}',
                        'temp_sales_order_no': $('#TempSalesOrderNo').val(),
                        'bd_roll_id': $("#rollDropdown").val(),
                        'bd_cutpiece_len': bd_cutpiece_len,
                        'bd_cutpiece_wid': bd_cutpiece_wid,
                        'user_remarks': customer_instruction,
                        'cfa': cfa_check,
                        'remnant_shipable': remnant_check,
                        'unit_price': $("#unit-price-cal").val(),
                        'sqft_area': $("#ats-qty").val(),
                        'rand_str': randomString,
                        'is_bd_child': 0,
                        'rugpad_price': $('#add_rugpad').is(':checked') ? $('#rug_pad_price_ext').val() : '',
                    },
                    success: function (response) {
                        if (response.success) {
                            if ($('#add_rugpad').is(':checked')) {
                                add_rug_pad(item, max_len_size, bd_cutpiece_len, bd_cutpiece_wid, randomString)
                            }
                            console.log("new ", $('#item_json').length);
                            toastr.success(response.message, {
                                        hideDuration: 10000,
                                        closeButton: true,
                                    });
                                    $('#add_to_cart').removeClass('btn-muted');

                                    $('#cut_piece_parent .broadloom-badge').remove();
                                    $('#rollDropdown').val('');
                                    $('#Tlength').val('');
                                    $('#TlengthInch').val('');
                                    $('#Twidth').val('');
                                    $('#TwidthInch').val('');
                                    $('#sq-ft').val('');
                                    $('#sq-yrd').val('');
                                    $('#sq-ext').val('');
                                    $('#surging_options').val('');
                                    $('#surging_check').prop('checked', false);
                                    $('#surging_charges').val('');
                                    $('#cust-inst').val('');
                                    $("#cfa_check").prop("checked", false);
                                    $("#remnant_check").prop("checked", false);
                                    $('#surging_options').prop('disabled', true);
                                    $('#show-cut-piece-btn').addClass('d-none');
                                    $('#hide-cut-piece-btn').addClass('d-none');
                                    $('#add_to_cart').addClass('d-none');
                                    $('#rollDropdown').removeAttr("disabled");
                                    $('#cut-pieces').empty();
                                    $('#add_rugpad').prop('disabled', false).prop('checked', false);
                                    $('#Twidth').prop('disabled', true);
                                    $('#TwidthInch').prop('disabled', true);
                                    $('.add_cart').prop('disabled', false);
                                    setTimeout(() => {
                                         window.location.reload();
                                    }, 3000);
                        } else {
                            toastr.warning(response.message, {
                                hideDuration: 10000,
                                closeButton: true,
                            });
                            $('#add_to_cart').removeClass('btn-muted');
                        }
                    },
                    error: function (response) {
                        toastr.warning(response.message, {
                            hideDuration: 10000,
                            closeButton: true,
                        });
                        $('#add_to_cart').removeClass('btn-muted');
                    }
                });
            }
        }
    });
}

function add_rug_pad(item, max_len_size, bd_cutpiece_len, bd_cutpiece_wid, randomString){
    let jsonData = JSON.parse($('#item_json').val());
    jsonData.SQFTPrice = $('#rug_pad_price').val();
    jsonData.CutPieces = jsonData.CutPieces.map(cutPiece => {
        return {
            ...cutPiece,
            ItemID:  $('#rug_pad_id').val(),
            RollID: "TBA",
            SergingCharges: "",
            SergingType: null,
            UserRemarks: "",
        };
    });

    $.ajax({
        method: 'POST',
        url: '{{ route('frontend.cart.add') }}',
        data: {
            '_token': '{{ csrf_token() }}',
            'cart_item_id': $('#rug_pad_id').val(),
            'cart_customer_id': $('[name="customer_id"]').val(),
            'cart_item_name': item.ItemName,
            'cart_item_quantity': 1,
            'cart_item_color': item.ItemColor,
            'cart_item_size': max_len_size,
            'cart_item_price': $('#rug_pad_price_ext').val(),
            'item_surging_price': '',
            'cart_item_currency': '$',
            'cart_item_image': item.ImageNameArray[0],
            'cart_item_data': JSON.stringify(jsonData),
            'cart_item_broadloom': 1,
            'logged_user_no': '{{ Auth::user()->spars_logged_user_no }}',
            'temp_sales_order_no': $('#TempSalesOrderNo').val(),
            'bd_roll_id': 'TBA',
            'bd_cutpiece_len': bd_cutpiece_len,
            'bd_cutpiece_wid': bd_cutpiece_wid,
            'user_remarks': '',
            'cfa': '',
            'remnant_shipable': $("#remnant_check").is(":checked") ? 1 : 0,
            'unit_price': $("#unit-price-cal").val(),
            'sqft_area': $("#ats-qty").val(),
            'rand_str': randomString,
            'is_bd_child': 1,
            'rugpad_price': '',
        },
        success: function (response) {
            $('#rug_pad_price_ext').val('');
            toastr.success(response.message, {
                hideDuration: 10000,
                closeButton: true,
            });
            refreshAjaxPage();
        },
        error: function (xhr, status, error) {
            console.error("Error RugPad: ", error);
        }
    });
}

function removeItemFromCartInDashboard(itemId,token,customerId,checkbditem,rollId,randStr){
    if (confirm("Are you sure to remove this Item?"))
    {
        var formData =
        {
            itemId:itemId,
            customerId:customerId,
            checkbditem:checkbditem,
            rollId:rollId,
            randStr:randStr,
            _token:token
        };

        $.ajax(
        {
            method: "POST",
            url: "{{route('frontend.cart.remove')}}",
            data: formData
        })
        .done(function (response)
        {
            if(response.success == 1){
                window.location.reload();
                toastr.success(response.message,{
                    hideDuration: 10000,
                    closeButton: true,
                });
                refreshAjaxPage();
            }else{
                toastr.error(response.message,{
                    hideDuration: 10000,
                    closeButton: true,
                });
            }
        });
    }
}

function refreshAjaxPage(){
    $.ajax({
        method: 'GET',
        url: window.location.href,
        data: {
            '_token': '{{ csrf_token() }}',
            'refresh': true,
        },
        success: function (response) {
            var new_html = $($.parseHTML(response));
            console.log(`New Html :: ${JSON.stringify(new_html)}`);

            $('#cart-detail-area').html(new_html.find('#cart-detail-area').html());

            var $customerSelect = $('[name="customer_id"]');
            var selectedValue = $customerSelect.val();
            var salesRepValue = $('.sales_rep_customer_check').val();
            var matchFound = false;
            if ($customerSelect.is('select')) {
                $customerSelect.find('option').each(function() {
                    if ($(this).val() === salesRepValue) {
                        matchFound = true;
                        $customerSelect.val(salesRepValue);
                        return false;
                    }
                });
                $customerSelect.prop('disabled', matchFound);
            }
        }
    });
}

function SalesRepCustomerCartCheck(){
    var $customerSelect = $('[name="customer_id"]');
    var selectedValue = $customerSelect.val();
    var salesRepValue = $('.sales_rep_customer_check').val();
    var matchFound = false;

    if ($customerSelect.is('select')) {
        $customerSelect.find('option').each(function() {
            if ($(this).val() === salesRepValue) {
                matchFound = true;
                $customerSelect.val(salesRepValue);
                return false;
            }
        });
        $customerSelect.prop('disabled', matchFound);
    }
}
$('select[name="ship_via_id"]').on('change', function() {
    localStorage.setItem('ship_via_id', $(this).val());
            const selectedValue = $(this).val();
            // if (selectedValue == 'BEST' || selectedValue === 'FD58' || selectedValue === 'FD51' || selectedValue === 'FD50' || selectedValue === 'OTHER'
            //     || selectedValue === 'UT01' || selectedValue === 'UT03'
            // ) {
            //     $('#ship_instructions').prop('required', true);
            // } else {
            //     $('#ship_instructions').prop('required', false);
            // }

            if(selectedValue == 'BEST' || selectedValue === 'AMZX'){
                $('#shippingCharges').text('Will be calculated at the time of shipping');
            }else{
                $('#shippingCharges').text('Will be calculated at the time of shipping');
            }
        });
        $(document).ready(function () {
    $('#state_dropdown').on('change', function () {
        var selectedState = $(this).val();       // Get selected value
        $('#stateid').val(selectedState);        // Set it to the hidden input
    });

    // Optional: trigger once on page load if preselected
    $('#state_dropdown').trigger('change');
});

</script>
@endsection


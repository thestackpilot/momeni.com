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
            {{-- @include('frontend.'.$active_theme -> theme_abrv.'.components.breadcrumbs') --}}
            {{-- <div class="d-none" id="item_id" value="{{$roll_pieces['OutPut']["RollsAndCutPieces"][0]['ItemID']}}"></div> --}}
            <input type="hidden" name="" id="item_id" value="">
            <div class="container broadloom-wrapper" style="background-color: whitesmoke;">
                <div class="fa-3x font-weight--bold text-center">Shopping cart</div>
                <div class="steppers">
                    <ol>
                        <li class="checkout-step active">
                            <span>1</span>
                            Shopping Cart
                        </li>
                        <li class="checkout-step">
                            <span>2</span>
                            Checkout
                        </li>
                        <li>
                            <span>3</span>
                            Order Complete
                        </li>
                    </ol>
                </div>
            </div>
            {{-- @include('frontend.' . $active_theme->theme_abrv . '.components.shopping-cart-header') --}}
            <div class="site-wrapper-reveal">
                <div class="container mt-2">
                    <div class="row mt-4 mb-5">
                        <div class="col-md-9 col-sm-12" style="background-color: grey:">
                            <div class="table-responsive">
                                <table id="" class="table for-data-table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>SubTotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if(count((array)$cart->items))
                                        @foreach($cart->items as $item)
                                            @php
                                                if(isset($item -> item_data) && $item -> item_data) {
                                                   //$item_data = json_decode(unserialize($item -> item_data));
                                                   $item_data = json_decode($item->item_data, true);
                                                   //dd($item_data, $cart);
                                                }
                                            @endphp
                                        <tr>
                                            <th class="" scope="row"><div class="row">
                                                <div class="col-1 justify-content-center align-content-center delete-row" style="color: red;cursor: pointer;">x</div>
                                                <div class="col-3"><img src={{$item_data['ImageName']}} alt="{{$item_data['ItemID']}}" height="100px"></div>
                                                <div class="col-8" style="font-size: 12px">
                                                    <div class="mx-3 mt-2 font-weight--bold row">Design: <p class="font-weight--normal mx-2">{{$item_data['ItemName']}}</p></div>
                                                    <div class="mx-3 mt-2 row">SKU: <p class="font-weight--normal mx-2">N/A</p></div>
                                                    <div class="mx-3 mt-2 row">Size: <p class="font-weight--normal mx-2">{{$item->item_size}}</p></div>
                                                </div>
                                            </div>
                                        </th>
                                            <td class="">
                                                <div class="d-flex flex-row qty-styles mb-2">
                                                    <a href="javascript:void(0);" class="qty-minus qty-action"> - </a>
                                                    <input type="number" id="item_qty" name="quantity" autocomplete="off"
                                                        onkeydown="if(this.key==='.'){this.preventDefault();}"
                                                        class="form-control" min="1" max="9999" maxlength="4"
                                                        step="1" required value="{{$item->item_quantity}}" />
                                                    <a href="javascript:void(0);" class="qty-add qty-action"> + </a>
                                                </div>
                                            </td>
                                            <td class="">{{$item->item_currency}}{{$item->item_price}}</td>
                                            <td class="">{{$item->item_currency}}{{$item->item_total}}</td>
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
                                    <a href="" class="add-to-cart-button btn btn-dark align-content-center" id="add_cart">
                                        Update Cart
                                    </a>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 border">
                                <div class="d-flex justify-content-around align-items-left flex-column">
                                    <p class="mt-2 mb-2 text-center fa-2x">Cart Totals</p>
                                    <div class="row mt-3">
                                        <div class="col-md-6">SubTotal:</div>
                                        <div class="col-md-6 text-right">{{$cart_details['cart_currency']}}{{$cart_details['cart_total']}}</div>
                                    </div>
                                    <hr style="border-top-color: whitesmoke;">
                                    <div class="row">
                                        <div class="col-md-9">Shipping Charges:</div>
                                        <div class="col-md-3 text-right">N/A</div>
                                    </div>
                                    <hr style="border-top-color: whitesmoke;">
                                    <div class="row mt-3">
                                        <div class="col-md-6 font-weight-bold">Total:</div>
                                        <div class="col-md-6 font-weight-bold text-right">N/A</div>
                                    </div>
                                    <a href="" class="add-to-cart-button text-left btn btn-dark col-md-12 mt-3 mb-3" id="add_cart">
                                        Proceed to Checkout <i class="px-4 fa fa-long-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                </div>
        </main>
        @include('frontend.' . $active_theme->theme_abrv . '.components.footer')
    </div>
    @include('frontend.' . $active_theme->theme_abrv . '.components.login-modal')
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.delete-row').click(function() {
            $(this).closest('tr').remove();
        });

        $('.qty-minus').click(function(e) {
            e.preventDefault();
            var input = $(this).closest('.qty-styles').find('input[name="quantity"]');
            var currentValue = parseInt(input.val());
            if (currentValue > 1) {
                input.val(currentValue - 1);
            }
        });

        // Event listener for clicking on the plus button
        $('.qty-add').click(function(e) {
            e.preventDefault();
            var input = $(this).closest('.qty-styles').find('input[name="quantity"]');
            var currentValue = parseInt(input.val());
            input.val(currentValue + 1);
        });

        });
    </script>
@endsection

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

            <div class="site-wrapper-reveal">
                <div class="container broadloom-wrapper" style="background-color: whitesmoke;">
                    <h3>Shopping cart</h3>
                    <br>
                </div>
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
                                        <tr>
                                            <th class="" scope="row">1</th>
                                            <td class="">
                                                <div class="d-flex flex-row qty-styles mb-2">
                                                    <a href="javascript:void(0);" class="qty-minus qty-action"> - </a>
                                                    <input type="number" id="item_qty" name="quantity" autocomplete="off"
                                                        onkeydown="if(this.key==='.'){this.preventDefault();}"
                                                        class="form-control" min="1" max="9999" maxlength="4"
                                                        step="1" required value="" />
                                                    <a href="javascript:void(0);" class="qty-add qty-action"> + </a>
                                                </div>
                                            </td>
                                            <td class="">$87.6</td>
                                            <td class="">$87.6</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="mt-4 d-flex justify-content-end mx-5">
                                    <a href="" class="add-to-cart-button btn btn-dark" id="add_cart">
                                        Update Cart
                                    </a>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 border">
                            <div class="d-flex justify-content-around row">
                                <p class="font-weight--bold mt-2 mb-2">Cart Totals</p>
                                <p>SubTotal:</p><span>$45</span>
                                <p>Shipping Charges:</p><span>$45</span>
                                <p style="font-weight--bold">Total:</p><span class="font-weight--bold">$55</span>
                                <a href="" class="add-to-cart-button btn btn-dark" id="add_cart">
                                    Update Cart
                                </a>
                            </div>
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

        });
    </script>
@endsection

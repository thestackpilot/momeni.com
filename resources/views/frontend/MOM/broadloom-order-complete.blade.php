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
            <input type="hidden" name="" id="item_id" value="">
            <div class="container broadloom-wrapper" style="background-color: whitesmoke;">
                <div class="fa-3x font-weight--bold text-center">Shopping cart</div>
                <div class="steppers">
                    <ol>
                        <li class="checkout-step active">
                            <span>1</span>
                            Shopping Cart
                        </li>
                        <li class="checkout-step active">
                            <span>2</span>
                            Checkout
                        </li>
                        <li class="active">
                            <span>3</span>
                            Order Complete
                        </li>
                    </ol>
                </div>
            </div>
            <div class="container">
                <div class="mt-5 mb-4 text-center" style="color: green; font-size:22px;">
                    Your order is processed and you will get the confirmation soon. Your Order Detail is:
                </div>
                <div class="container my-5">
                    <div class="row justify-content-center">
                        <div class="col-sm-3 text-center p-3 order-complete-border" style="border-left: 2px dashed green;">
                            <div class="row">
                                <div class="col">
                                    <div>Order Number</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <strong>4818</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 text-center p-3 order-complete-border" style="border-left: 2px dashed green;">
                            <div class="row">
                                <div class="col">
                                    <div>Date</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <strong>January 18, 2022</strong>
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
                                    <strong>$55.00</strong>
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
                            <div class="row">
                                <div class="col-3"><img src="/MOM/images/product/1.jpg" alt="" height="100px">
                                </div>
                                <div class="col-9" style="font-size: 12px">
                                    <div class="mx-3 mt-2 font-weight--bold row">Design: 
                                        <p class="font-weight--normal mx-2">Sim-I Ivory</p>
                                    </div>
                                    <div class="mx-3 mt-2 row">SKU: <p class="font-weight--normal mx-2">Sim-I Ivory</p>
                                    </div>
                                    <div class="mx-3 mt-2 row">Size: <p class="font-weight--normal mx-2">3x5 Rugs</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-right align-content-center">$45.00</div>
                        <div class="col-md-9">
                            <hr class="mx-4" style="border-top-color: whitesmoke;">
                        </div>
                        <div class="col-md-4 align-content-center font-weight--bold">SubTotal</div>
                        <div class="col-md-4 align-content-center text-right font-weight--bold">$45.00</div>
                        <div class="col-md-9">
                            <hr class="mx-4" style="border-top-color: whitesmoke;">
                        </div>
                        <div class="col-md-4 align-content-center">Shipping Charges</div>
                        <div class="col-md-4 align-content-center text-right">$10.00</div>
                        <div class="col-md-9 mb-3">
                            <hr class="mx-4" style="border-top-color: whitesmoke;">
                        </div>
                        <div class="col-md-4 align-content-center font-weight--bold" style="font-size: 20px">Total</div>
                        <div class="col-md-4 align-content-center text-right font-weight--bold mb-5" style="font-size: 20px">$55.00</div>
                        <div class="col-sm-8 my-5 row justify-content-center">
                            <a href="" class="add-to-cart-button btn btn-dark align-content-center text-left mt-5" id="add_cart">
                                Go to dashboard &nbsp; &nbsp; &nbsp;<i class="fa fa-long-arrow-right pl-5"></i>
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

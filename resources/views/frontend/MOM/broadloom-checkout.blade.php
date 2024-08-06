@php
    // $active_theme object is available containing the theme developer json loaded.
    // This is for the theme developers who want to load further view assets

    use App\Http\Controllers\ConstantsController;
    use App\Http\Controllers\CommonController;

@endphp

@section('title', 'Broadloom Checkout')
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
                        <li>
                            <span>3</span>
                            Order Complete
                        </li>
                    </ol>
                </div>
            </div>
            {{-- @include('frontend.' . $active_theme->theme_abrv . '.components.shopping-cart-header') --}}
            <div class="site-wrapper-reveal">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 my-5">
                            <div class="mb-5">
                                <form class="needs-validation" novalidate>
                                    <div class="row">
                                        <div class="col-md-5 mb-2">
                                            <label for="" class="form-label mb-0" style="font-size: 14px">First Name <span class="text-danger" style="font-size: 18px">*</span></label>
                                            <input class="form-control" type="text" id=""
                                                placeholder="" value="" required>
                                        </div>
                                        <div class="col-md-5 mb-2">
                                            <label for="" class="form-label mb-0" style="font-size: 14px">Last Name</label>
                                            <input class="form-control" type="text" id=""
                                                placeholder="" value="" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 mb-2">
                                            <label for="" class="form-label mb-0" style="font-size: 14px">Email</label>
                                            <input class="form-control" type="email" id=""
                                                placeholder="" value="" required>
                                        </div>
                                        <div class="col-md-5 mb-2">
                                            <label for="" class="form-label mb-0" style="font-size: 14px">Phone</label>
                                            <input class="form-control" type="number" id=""
                                                placeholder="" value="" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-10 mb-2">
                                            <label for="" class="form-label mb-0" style="font-size: 14px">Street Address</label>
                                            <input class="form-control" type="text" id=""
                                                placeholder="" value="" required>
                                        </div>
                                        <div class="col-md-10 mb-2">
                                            <input class="form-control" type="text" id=""
                                                placeholder="" value="" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 mb-2">
                                            <label for="" class="form-label mb-0" style="font-size: 14px">Town/ City</label>
                                            <input class="form-control" type="text" id=""
                                                placeholder="" value="" required>
                                        </div>
                                        <div class="col-md-5 mb-2">
                                            <label for="" class="form-label mb-0" style="font-size: 14px">State</label>
                                            <input class="form-control" type="text" id=""
                                                placeholder="" value="" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 mb-2">
                                            <label for="" class="form-label mb-0" style="font-size: 14px">Zip Code</label>
                                            <input class="form-control" type="text" id=""
                                                placeholder="" value="" required>
                                        </div>
                                    </div>
                                    <p class="font-weight--bold " style="font-size: 18px" >Additional Information</p>
                                    <div class="row">
                                        <div class="col-md-5 mb-2 align-content-center">
                                            <input class="form-check-input" type="checkbox" id="" required>
                                            <label class="form-check-label" for="" style="font-size: 14px">Ship Complete</label>
                                        </div>
                                        <div class="col-md-5 mb-2">
                                            <label for="" class="form-label mb-0" style="font-size: 14px">P.O or Reference Number</label>
                                            <input class="form-control" type="text" id=""
                                                placeholder="" value="" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 mb-2">
                                            <label for="" class="form-label mb-0" style="font-size: 14px">Shipping Date</label>
                                            <input class="form-control" type="text" id=""
                                                placeholder="" value="" required>
                                        </div>
                                        <div class="col-md-5 mb-2">
                                            <label for="" class="mb-0" style="font-size: 14px">Order Notes (optional)</label>
                                            <textarea class="form-control" id="" style="height: 8rem;" placeholder=""></textarea>
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
                                    <div class="row px-5">
                                        <div class="col-md-9"><div class="row">
                                            <div class="col-3"><img src="/MOM/images/product/1.jpg" alt="" height="100px"></div>
                                            <div class="col-9" style="font-size: 12px">
                                                <div class="mx-3 mt-2 font-weight--bold row">Design: <p class="font-weight--normal mx-2">Sim-I Ivory</p></div>
                                                <div class="mx-3 mt-2 row">SKU: <p class="font-weight--normal mx-2">Sim-I Ivory</p></div>
                                                <div class="mx-3 mt-2 row">Size: <p class="font-weight--normal mx-2">3x5 Rugs</p></div>
                                            </div>
                                        </div></div>
                                        <div class="col-md-3 text-right align-content-center">$45</div>
                                    </div>
                                    <hr class="mx-4" style="border-top-color: rgb(161, 161, 161);">
                                    <div class="row px-5">
                                        <div class="col-md-6 font-weight-bold">SubTotal</div>
                                        <div class="col-md-6 font-weight-bold text-right">$55</div>
                                    </div>
                                    <hr class="mx-4" style="border-top-color: rgb(161, 161, 161);">
                                    <div class="row px-5">
                                        <div class="col-md-9">Shipping Charges</div>
                                        <div class="col-md-3 text-right">$55</div>
                                    </div>
                                    <hr class="mx-4" style="border-top-color: rgb(161, 161, 161);">
                                    <div class="row my-4 px-5">
                                        <div class="col-md-9 font-weight--bold">Total</div>
                                        <div class="col-md-3 font-weight--bold text-right">$55</div>
                                    </div>
                                    <hr class="mx-4" style="border-top-color: rgb(161, 161, 161);">
                                    <div class="row my-4 px-5 justify-content-center">
                                        <div class="col-md-12">
                                            <p class="text-center">Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our privacy policy.</p>
                                            <div class="text-center">
                                                <a href="" class="add-to-cart-button btn btn-dark align-content-center text-left" id="add_cart">
                                                    Place Order &nbsp; &nbsp; &nbsp;<i class="fa fa-long-arrow-right pl-5"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
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

@section('scripts')
    <script>
        $(document).ready(function() {

        });
    </script>
@endsection

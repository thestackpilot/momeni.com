@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp

@extends('dashboard.layouts.app')
@section('title','Dashboard | My Account')
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
                        <div class="account-content p-5">
                            <h1 class="section-title text-center mb-3 mt-3 font-ropa">Your Account</h1>
                            @if (Session::has('message'))
                            <div class="alert alert-{{Session::get('message')['type']}}">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                {{Session::get('message')['body']}}
                            </div>
                            @endif
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <div class="d-flex flex-row settings">
                                <a href="javascript:void(0)" data-related-form="personal-settings" class="me-3 active mr-2"> Personal Setting </a>
                                <a href="javascript:void(0)" data-related-form="account-information" class="me-3 mr-2"> Account Information </a>
                                <a href="javascript:void(0)" data-related-form="change-password" class="me-3 mr-2"> Change Password </a>
                                @if($active_theme_json->general->allow_custom_cost)
                                <a href="javascript:void(0)" data-related-form="custom-cost" class="me-3 mr-2"> Custom Costs </a>
                                @endif
                                @if($active_theme_json->general->allow_freight_percentage)
                                <a href="javascript:void(0)" data-related-form="custom-freight" class="me-3 mr-2"> Freight Percentage </a>
                                @endif
                            </div>

                            <form style="display: none !important;" method="post" action="{{route('dashboard.myaccount.changepass')}}" class="change-password d-flex flex-lg-row flex-sm-column flex-dir-col flex-wrap mt-3 dafault-form p-1 pt-3">
                                @csrf
                                <div class="mb-3 col-md-4 col-sm-12 pe-1 pe-lg-3">
                                    <label for="existing-password" class="form-label">Existing Password*</label>
                                    <input type="password" name="existing-password" data-required="true" id="existing-password" value="{{old('existing-password')}}" class="form-control" placeholder="******">
                                </div>
                                <div class="mb-3 col-md-4 col-sm-12 pe-1 pe-lg-3">
                                    <label for="new-password" class="form-label">New Password*</label>
                                    <input type="password" name="new-password" data-required="true" id="new-password" value="{{old('new-password')}}" class="form-control" placeholder="********">
                                </div>
                                <div class="mb-3 col-md-4 col-sm-12 pe-1 pe-lg-3">
                                    <label for="confirm-password" class="form-label">Confirm Password*</label>
                                    <input type="password" name="confirm-password" data-required="true" id="confirm-password" value="{{old('confirm-password')}}" class="form-control" placeholder="********">
                                </div>
                                <div class="mb-3 justify-content-end pe-1 pe-lg-3">
                                    <span>You will be logged out after you have changed your Password.</span>
                                </div>
                                <div class="mb-3 justify-content-end pe-1 pe-lg-3 col-md-12 d-flex">
                                    <button type="submit" class="btn btn-primary text-uppercase mt-2" style="width: auto;background: #660000;color:#fff;">Update</button>
                                </div>
                            </form>
                            <form style="display: none !important;" method="post" action="{{route('dashboard.myaccount.update')}}" class="custom-cost d-flex flex-lg-row flex-sm-column flex-dir-col flex-wrap mt-3 dafault-form p-1 pt-3">
                                @csrf
                                <input type="hidden" name="form-type" value="{{ConstantsController::FORM_TYPES['update-cost']}}" />
                                <div class="mb-3 col-md-6 pe-1 pe-lg-3">
                                    <label for="cost-type" class="form-label">Active Cost Type*</label>
                                    <select name="cost-type" id="cost-type" class="form-control">
                                        @foreach(ConstantsController::COST_TYPES as $key => $value)
                                        <option value="{{$key}}" {{Auth::user() && strcmp($key, Auth::user()->getDataAttribute('cost-type', '')) === 0 ? 'selected' : ''}}>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6 pe-1 pe-lg-3 my-msrp d-none">
                                    <label for="msrp-multiplier" class="form-label">MSRP Multiplier*</label>
                                    <input type="number" name="msrp-multiplier" data-required="true"  data-double="true" maxlength="4" max="99.99" min="1" step="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="msrp-multiplier" value="{{Auth::user() ? Auth::user()->getDataAttribute('msrp-multiplier', '') : ''}}" class="form-control" placeholder="1">
                                </div>
                                <div class="mb-3 justify-content-end pe-1 pe-lg-3 col-md-12 d-flex">
                                    <button type="submit" class="btn btn-primary text-uppercase mt-2" style="width: auto;background: #660000;color:#fff;">Update</button>
                                </div>
                            </form>
                            <form style="display: none !important;" method="post" action="{{route('dashboard.myaccount.update')}}" class="custom-freight d-flex flex-lg-row flex-sm-column flex-dir-col flex-wrap mt-3 dafault-form p-1 pt-3">
                                @csrf
                                <input type="hidden" name="form-type" value="{{ConstantsController::FORM_TYPES['update-freight']}}" />
                                @php
                                $customer_values = (array)Auth::user()->getDataAttribute('freight-percentage', '');
                                @endphp
                                <div class="mb-3 col-md-6 pe-1 pe-lg-3">
                                    <label for="freight-percentage" class="form-label">Freight Percentage*</label>
                                    <input type="number" max="100" name="freight-percentage[{{Auth::user()->customer_id}}]" data-required="true" id="freight-percentage" value="{{$customer_values && array_key_exists(Auth::user()->customer_id, $customer_values) ? $customer_values[Auth::user()->customer_id] : ''}}" class="form-control" placeholder="15">
                                </div>
                                <div class="mb-3 justify-content-end pe-1 pe-lg-3 col-md-12 d-flex">
                                    <button type="submit" class="btn btn-primary text-uppercase mt-2" style="width: auto;background: #660000;color:#fff;">Update</button>
                                </div>
                            </form>
                            <form style="display: none !important;" method="post" action="{{route('dashboard.myaccount.update')}}" class="account-information d-flex flex-lg-row flex-sm-column flex-dir-col flex-wrap mt-3 dafault-form p-1 pt-3">
                                @csrf
                              <div class="col-lg-12 col-sm-12 col-12 py-0">
                        <!-- TODO - ADD THEME CONDITION HERE -->
                        @if($active_theme_json->general->allow_sales_rep_details)
                        @if (1 || $active_customer)
                        <div class="p-3 account-content mb-4 py-5 lr-accountInfo">
                            <h1 class="section-title text-center mb-3 mt-0 font-ropa">
                                Customer Information
                                <i class="bi bi-info-circle" style="font-size: 20px;" data-toggle="tooltip" data-placement="top" title="This information is of the customer which is currently selected in the cart."></i>
                            </h1>

                             @if ( $customers )
                            <form class="d-flex" method="get">
                                <div class="col-md-8 d-inline">
                                    <select class="form-control" name="customer">
                                        @foreach($customers as $customer)
                                            <option value="{{$customer['value']}}" {{ $active_customer && $active_customer == $customer['value'] ? 'selected' : '' }}>{{$customer['label']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary w-100" id="getSelected" >Select</button>
                                </div>
                            </form>
                            @else
                            <p style="font-size:24px;color: #EA7410;" class="px-2">{{$active_customer ? $active_customer : 'You have a customer account.'}}</p>
                            @endif
                            {{-- <p style="font-size:24px;color: #EA7410;" class="px-2">{{$active_customer}}</p> --}}
                            @if ($active_customer)
                            @if ($client_address && isset($client_address['CustomerAddress']))
                            <div class="d-flex flex-column mt-4 kinda-table">
                                <div class="align-items-center d-flex justify-content-between">
                                    <h6 class="col-md-2">
                                        Customer Address : <br>
                                        <!-- <a href="javascript:void(0);" class="col-md-12 text-right add-address">Add New Address</a> -->
                                    </h6>
                                    <select class="form-control select-address">
                                        @foreach($client_address['CustomerAddress']['ShipToAddresses'] as $address)
                                        <option value="{{$address['AddressID']}}">{{$address['AddressID']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @foreach($client_address['CustomerAddress']['ShipToAddresses'] as $address)
                            <div class="d-flex flex-column mt-4 kinda-table d-none address-section address-{{$address['AddressID']}}">
                                <a href="javascript:void(0);" class="col-md-12 text-right update-address">Edit</a>
                                <span class="d-none address-details">{{json_encode($address)}}</span>
                                <div class="d-flex justify-content-between flex-row even-row">
                                    <h6>First Name</h6>
                                    <p>{{$address['FirstName']}}</p>
                                </div>
                                <div class="d-flex justify-content-between flex-row">
                                    <h6>Address</h6>
                                    <p>{{$address['Address1']}} {{$address['Address2']}}</p>
                                </div>
                                <div class="d-flex justify-content-between flex-row even-row">
                                    <h6>Email</h6>
                                    <p>{{$address['Email']}}</p>
                                </div>
                                <div class="d-flex justify-content-between flex-row">
                                    <h6>Office Phone</h6>
                                    <p>{{$address['Phone1']}}</p>
                                </div>
                                <div class="d-flex justify-content-between flex-row even-row">
                                    <h6>Postal Code</h6>
                                    <p>{{$address['Zip']}}</p>
                                </div>
                            </div>
                            @endforeach
                            @endif
                            @endif
                        </div>
                        @endif
                        @if ($parent)
                        <div class="p-3 account-content mb-4 py-5 lr-accountInfo">
                            <h1 class="section-title text-center mb-3 mt-0 font-ropa">Admin Account</h1>
                            <p style="font-size:24px;color: #EA7410;" class="px-2">{{$parent->company}}</p>
                            <div class="d-flex flex-column mt-4 kinda-table ">
                                <div class="d-flex justify-content-between flex-row even-row">
                                    <h6>First Name</h6>
                                    <p>{{$parent->firstname}}</p>
                                </div>
                                <div class="d-flex justify-content-between flex-row">
                                    <h6>Address</h6>
                                    <p>{{$parent->street_address}}</p>
                                </div>
                                <div class="d-flex justify-content-between flex-row even-row">
                                    <h6>Email</h6>
                                    <p>{{$parent->email}}</p>
                                </div>
                                <div class="d-flex justify-content-between flex-row">
                                    <h6>Office Phone</h6>
                                    <p>{{$parent->phone}}</p>
                                </div>
                                <div class="d-flex justify-content-between flex-row even-row">
                                    <h6>Postal Code</h6>
                                    <p>{{$parent->postal_code}}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endif
                        <div class="p-3 account-content mb-4 py-5 lr-accountInfo">
                            <h1 class="section-title text-center mb-3 mt-0 font-ropa">My Account</h1>
                            <p style="font-size:24px;color: #EA7410;" class="px-2">{{Auth::user()->company}}</p>
                            <div class="d-flex flex-column mt-4 kinda-table ">
                                <!-- <h3 class="section-title text-center mb-3 mt-0 font-ropa d-none lr-theme-only"></h3> -->
                                <a href="{{route('dashboard.myaccount')}}" class="col-md-12 text-right">Edit</a>
                                <div class="d-flex justify-content-between flex-row even-row">
                                    <h6>First Name</h6>
                                    <p>{{Auth::user()->firstname}}</p>
                                </div>
                                <div class="d-flex justify-content-between flex-row">
                                    <h6>Address</h6>
                                    <p>{{Auth::user()->street_address}}</p>
                                </div>
                                <div class="d-flex justify-content-between flex-row even-row">
                                    <h6>Email</h6>
                                    <p>{{Auth::user()->email}}</p>
                                </div>
                                <div class="d-flex justify-content-between flex-row">
                                    <h6>Office Phone</h6>
                                    <p>{{Auth::user()->phone}}</p>
                                </div>
                                <div class="d-flex justify-content-between flex-row even-row">
                                    <h6>Postal Code</h6>
                                    <p>{{Auth::user()->postal_code}}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                                
                            </form>
                            <form method="post" action="{{route('dashboard.myaccount.update')}}" class="personal-settings d-flex flex-lg-row flex-sm-column flex-dir-col flex-wrap mt-3 dafault-form p-1 pt-3">
                                @csrf
                                <input type="hidden" name="form-type" value="{{ConstantsController::FORM_TYPES['profile']}}" />
                                <!-- <div class="mb-3 col-md-3 col-sm-12 pe-1 pe-lg-3">
                                    <label for="fullname" class="form-label">User Name*</label>
                                    <input type="text" class="form-control" value="Johndoe" placeholder="Oriental.Rug">
                                </div> -->
                                <div class="mb-3 col-md-3 col-sm-12 pe-1 pe-lg-3">
                                    <label for="firstname" class="form-label">First Name*</label>
                                    <input type="text" name="firstname" data-required="true" id="firstname" value="{{(old('firstname')) ? old('firstname') : Auth::user()->firstname}}" maxlength="35" class="form-control" placeholder="Oriental">
                                </div>
                                <!-- <div class="mb-3 col-md-3 col-sm-12 pe-1 pe-lg-3">
                                    <label for="PhoneNumber*" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" placeholder="Rug Gallery">
                                </div> -->
                                <div class="mb-3 col-md-3 col-sm-12 pe-1 pe-lg-3">
                                    <label for="lastname" class="form-label">Last Name*</label>
                                    <input type="text" data-required="true" name="lastname" id="lastname" class="form-control" placeholder="L.P" value="{{(old('lastname')) ? old('lastname') : Auth::user()->lastname}}" maxlength="35">
                                </div>
                                <div class="mb-3 col-md-3 col-sm-12 pe-1 pe-lg-3">
                                    <label for="email" class="form-label">Email*</label>
                                    <input type="email" data-required="true" id="email" name="email" class="form-control" placeholder="oriental@example.com" value="{{(old('email')) ? old('email') : Auth::user()->email}}" maxlength="60">
                                </div>
                                <div class="mb-3 col-md-3 col-sm-12 pe-1 pe-lg-3">
                                    <label for="company" class="form-label">Company*</label>
                                    <input type="text" data-required="true" id="company" class="form-control" name="company" placeholder="XYZ & Co." value="{{(old('company')) ? old('company') : Auth::user()->company}}" maxlength="35">
                                </div>
                                <div class="mb-3 col-md-3 col-sm-12 pe-1 pe-lg-3">
                                    <label for="phone" class="form-label">Office Phone</label>
                                    <input type="text" id="phone" class="form-control" name="phone" placeholder="210-342-4362" maxlength="12" minlength="12" data-inputmask="'mask': '999-999-9999'" value="{{(old('phone')) ? old('phone') : Auth::user()->phone}}">
                                </div>
                                <div class="mb-3 col-md-3 col-sm-12 pe-1 pe-lg-3">
                                    <label for="street_address" class="form-label">Address</label>
                                    <input type="text" id="street_address" class="form-control" name="street_address" placeholder="Street # 123" value="{{(old('street_address')) ? old('street_address') : Auth::user()->street_address}}" maxlength="35">
                                </div>
                                <div class="mb-3 col-md-3 col-sm-12 pe-1 pe-lg-3">
                                    <label for="postal_code" class="form-label">Postal Code</label>
                                    <input type="text" id="postal_code" class="form-control" name="postal_code" placeholder="12345" value="{{(old('postal_code')) ? old('postal_code') : Auth::user()->postal_code}}" maxlength="10">
                                </div>
                                <!-- <div class="mb-3 col-md-3 col-sm-12 pe-1 pe-lg-3">
                                    <label for="Inquiry" class="form-label">Account</label>
                                    <input type="number" min="0" class="form-control" placeholder="Cust">
                                </div> -->
                                {{-- <div class="mb-3 col-md-12 col-sm-12">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea style="min-height: 100px;" class="form-control" rows="10" id="description" name="description" aria-describedby="Inquiry " placeholder="Details" maxlength="2000">{{(old('description')) ? old('description') : Auth::user()->getDataAttribute('description','')}}</textarea>
                                </div> --}}
                                <div class="mb-3 justify-content-end pe-1 pe-lg-3 col-md-12 d-flex">
                                    <button type="submit" class="btn btn-primary text-uppercase mt-2" style="width: auto;background: #660000;color:#fff;">Save</button>
                                </div>
                            </form>
                            <!-- <div class="d-flex flex-column">
                                <hr class="minicart-seprator mb-2">
                                <div class="d-flex justify-content-between mb-5 mt-4">
                                    <div class="d-flex flex-column bill-to">
                                        <h6 class="font-ropa">Your Account Representative:</h6>
                                        <p class="mt-3">Oriental Rug Gallery, L.P</p>
                                        <p style="color: #086AD8;text-decoration: underline;">oriental@example.com</p>
                                        <h6 class="font-ropa mt-4">Reward Level: Bronze</h6>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

</div>

      <div class="modal fade address-details-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="showForm" method="POST" action="{{route('dashboard.updatecustomeraddress') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header text-center">
                    Address Details
                </div>
                <div class="modal-body" id="section-details" style="background: #fff;"></div>
                  <div class="modal-footer">
                        <button type="button" class="btn btn-secondary border-0 close-modal" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save-details" data-dismiss="modal">Save</button>
                   </div>
            </div>
        </form>
    </div>
</div>

    @include('dashboard.components.footer')
</div>
@endsection
@section('scripts')
<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>
<script type="text/javascript">
    if ( window.location.hash ) {
        var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
        if (typeof hash !== "undefined" && hash.length) {
            var forms = Array.prototype.slice.call(document.getElementsByTagName('form'));
            forms.map((form) => {
                form.style = 'display: none !important;';
                if ( form.className.indexOf(hash) > -1 )
                    form.style = '';
            });
        }
    }
    $(document).ready(function() {
        function validateEmail(email) {
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }

        $("[data-inputmask]").inputmask({greedy: false, placeholder:""});
        $('.settings a').click(function() {
            $('.settings a').removeClass('active');
            $(this).addClass('active');
            $('form').attr('style', 'display: none !important;');
            $('form.' + $(this).attr('data-related-form')).attr('style', '');
        });

        // TODO - This needs to be improvised
        $('select[name="cost-type"]').change(function() {
            if ($(this).val() != 'msrp') {
                $('.my-msrp').addClass('d-none');
            } else {
                $('.my-msrp').removeClass('d-none');
            }
        }).change();

        // TODO - NEED TO OPTIMIZE THIS
        $('form.change-password').on('submit', function() {
            var allOk = true;
            $('input[data-required="true"]:visible, select[data-required="true"]:visible').each(function() {
                if (typeof $(this).val().length === 'undefined') {
                    $(this).addClass('is-invalid');
                    allOk = false;
                } else if ($(this).val().trim().length < 1) {
                    $(this).addClass('is-invalid');
                    allOk = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // if (allOk && $('input[name="new-password"]').val() != $('input[name="confirm-password"]').val()) {
            //     $('input[name="new-password"], input[name="confirm-password"]').addClass('is-invalid');
            //     allOk = false;
            // }

            return allOk;
        });

        // TODO - NEED TO OPTIMIZE THIS
        $('form.custom-freight, form.custom-cost').on('submit', function() {
            var allOk = true;
            $('input[data-required="true"]:visible, select[data-required="true"]:visible').each(function() {
                if (typeof $(this).val().length === 'undefined') {
                    $(this).addClass('is-invalid');
                    allOk = false;
                } else if ($(this).val().trim().length < 1) {
                    $(this).addClass('is-invalid');
                    allOk = false;
                } else {
                    $(this).removeClass('is-invalid');
                }

                if ($(this).attr('type') == 'number' && typeof $(this).attr('max') !== 'undefined' && $(this).val() > 100) {
                    $(this).addClass('is-invalid');
                } else if (allOk) {
                    $(this).removeClass('is-invalid');
                }
            });

            return allOk;
        });

        // TODO - NEED TO OPTIMIZE THIS
        $('form.personal-settings').on('submit', function() {
            var allOk = true;
            $('input[data-required="true"]:visible, select[data-required="true"]:visible').each(function() {
                if (typeof $(this).val().length === 'undefined') {
                    $(this).addClass('is-invalid');
                    allOk = false;
                } else if ($(this).val().trim().length < 1) {
                    $(this).addClass('is-invalid');
                    allOk = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (allOk && !validateEmail($('input[type="email"]').val())) {
                $('input[type="email"]').addClass('is-invalid');
                allOk = false;
            }

            return allOk;
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('select.select-address').change(function() {
            $('.address-section').addClass('d-none');
            $('.address-' + $(this).val()).removeClass('d-none');
        }).change();

        $(document).on('click', '.address-details-modal .close-modal',function() {
            $('.address-details-modal').modal('hide');
        });

        $(document).on('submit', '.address-details-modal form', function() {
            var all_ok = true;
            const required_fields = ['FirstName', 'Address1', 'Phone1'];
            $('.address-details-modal form input').each(function() {
                if (required_fields.includes($(this).attr('name'))) {
                    if (typeof $(this).val().length == 'undefined' || $(this).val().length < 1) {
                        $(this).addClass('is-invalid');
                        all_ok = false;
                    } else
                        $(this).removeClass('is-invalid');
                }
            });
            return all_ok;
        });

        $(document).on('click', '.update-address', function() {
            console.log('someone hit me');
            
            var form = '';
            var i = 0;
            const data = JSON.parse($('span.address-details', $(this).closest('.address-section')).html());
            for (const key in data) {
                if (key != 'AddressID' && i % 2 == 0) form += `<div class="row">`;
                form += `
                    <div class="mb-3 col-md-6 ${(key == 'AddressID') ? 'd-none' : ''}">
                        <label class="form-label">${(key.replace(/([A-Z|0-9])/g, ' $1').trim()).replace('_', ' ')}</label>
                        <input type="text" class="form-control bg-white" name="${key}" value="${data[key]}" placeholder="${key}">
                    </div>
                `;
                if (key != 'AddressID' && i % 2 == 1) form += `</div>`;
                if (key != 'AddressID') i++;
            }
            form += `
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label class="form-label">Status</label>
                        <select class="form-control bg-white" name="Status">
                            <option value="A">Active</option>
                            <option value="N">In Active</option>
                        </select>
                    </div>
                </div>
            `;
            $('.address-details-modal .modal-body').html(`${form}`);
        document.getElementById('showForm').style.display = 'block';
            $('.address-details-modal').modal('show');
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('getSelected').addEventListener('click', function() {
      const select = document.querySelector('select[name="customer"]');
      const selectedCustomer = select.value;

      if (selectedCustomer) {
        const routeUrl = "{{ route('dashboard.accountinfo') }}";
        window.location.href = `${routeUrl}?customer=${encodeURIComponent(selectedCustomer)}`;
      } else {
        alert('Please select a customer.');
      }
    });
  });
</script>
@endsection

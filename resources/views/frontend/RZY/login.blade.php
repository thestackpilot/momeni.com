@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp 

@extends('frontend.'.$active_theme -> theme_abrv.'.layouts.app')
@section('title','Login')
@section('content')
<div class="wrapper light-grey-bg p-0">
    @include('frontend.'.$active_theme -> theme_abrv.'.components.header')
    <main class="main-content">
        <section class="collection-section">
            <div class="container">
                <div class="d-flex flex-lg-row flex-sm-column flex-dir-col">
                    <div class="col-lg-6 col-sm-12 m-md-2 mb-sm-3 contact-mb3 col-12 p-2">
                        <div class="bg-white p-md-5">
                            <h2 class="text-center">Sign in with Email</h2>
                            <form class="user d-flex flex-column mt-5 dafault-form p-5 pt-3" method="POST" action="{{ route('auth.login') }}">
                                @csrf
                                @if (Session::has('message') && Session::get('message')['referer'] == 'login' )
                                <div class="alert alert-{{Session::get('message')['type']}}">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    {{Session::get('message')['body']}}
                                </div>
                                @endif
                                <!-- @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">User ID</label>
                                    <input type="text" name="email" required value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" id="email" aria-describedby="emailHelp" placeholder="Please enter your User ID">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" required name="password" class="form-control @error('password') is-invalid @enderror " id="password" placeholder="*********">
                                </div>
                                <!-- <div class="mb-3">
                                    <a href="#0" class="link">Forgot Password</a>
                                </div> -->
                                <button type="submit" class="btn btn-primary text-uppercase mt-5">Sign In</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 m-md-2 mb-sm-3 contact-mb3 col-12 p-2">
                        <div class="bg-white p-md-5">
                            <h2 class="text-center">Create an account</h2>
                            <p class="text-center">Please enter the following information</p>
                            <form method="post" action="{{ route('auth.register') }}" class="registration-form d-flex flex-column mt-5 dafault-form p-5 pt-3">
                                @csrf
                                @if (Session::has('message') && Session::get('message')['referer'] == 'registration')
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
                                <div class="mb-3">
                                    <label for="regEmail" class="form-label">Email address*</label>
                                    <input type="email" data-required="true" value="{{ old('reg-email') }}" maxlength="60" name="reg-email" class="form-control @error('reg-email') is-invalid @enderror" id="regEmail" aria-describedby="emailHelp" placeholder="example@domainname.com">
                                </div>
                                <div class="mb-3">
                                    <label for="firstname" class="form-label">First name*</label>
                                    <input type="text" data-required="true" value="{{ old('firstname') }}" maxlength="35" name="firstname" class="form-control @error('firstname') is-invalid @enderror" id="firstname" aria-describedby="Name" placeholder="Natalie">
                                </div>
                                <div class="mb-3">
                                    <label for="lastname" class="form-label">Last name*</label>
                                    <input type="text" data-required="true" value="{{ old('lastname') }}" maxlength="35" name="lastname" class="form-control @error('lastname') is-invalid @enderror" id="lastname" aria-describedby="lastName" placeholder="Davis">
                                </div>
                                <div class="mb-3">
                                    <label for="regPassword" class="form-label">Password*</label>
                                    <input type="password" data-required="true" name="reg-password" maxlength="12" minlength="8" class="form-control @error('password') is-invalid @enderror" id="regPassword" placeholder="****************">
                                </div>
                                <div class="mb-3">
                                    <label for="cpassword" class="form-label">Confirm Password*</label>
                                    <input type="password" data-required="true" name="cpassword" maxlength="12" minlength="8" class="form-control @error('password') is-invalid @enderror" id="cpassword" placeholder="****************">
                                </div>
                                <div class="mb-3">
                                    <label for="company" class="form-label">Company Name*</label>
                                    <input type="text" data-required="true" value="{{ old('company') }}" maxlength="50" name="company" class="form-control @error('company') is-invalid @enderror" id="company" aria-describedby="CompanyName" placeholder="RizzyHome & Co.">
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number*</label>
                                    <input type="text" data-required="true" name="phone" value="{{ old('phone') }}" maxlength="12" minlength="12" class="form-control @error('phone') is-invalid @enderror" id="phone" data-inputmask="'mask': '999-999-9999'" placeholder="123-456-7890">
                                </div>
                                <div class="mb-3">
                                    <label for="Interested*" class="form-label">Interested In*</label>
                                    <input type="text" data-required="true" name="interested-in" value="{{ old('interested-in') }}" maxlength="50" class="form-control @error('interested-in') is-invalid @enderror" id="Interested" aria-describedby="Interested" placeholder="i.e. Dealership">
                                </div>
                                <div class="mb-3">
                                    <label for="PostalCode" class="form-label">Postal Code</label>
                                    <input type="text" name="postal_code" value="{{ old('postal_code') }}" maxlength="10" class="form-control" id="PostalCode" aria-describedby="PostalCode" placeholder="PostalCode">
                                </div>
                                <div class="mb-3">
                                    <label for="City" class="form-label">City</label>
                                    <input type="text" name="city" value="{{ old('city') }}" maxlength="30" class="form-control" id="City" aria-describedby="City" placeholder="City">
                                </div>
                                <div class="mb-3">
                                    <label for="Country" class="form-label">Country</label>
                                    <input type="text" name="country" value="{{ old('country') }}" maxlength="50" class="form-control" id="Country" aria-describedby="Country" placeholder="Country">
                                </div>
                                <div class="mb-3">
                                    <label for="StateProvince" class="form-label">State/Province</label>
                                    <input type="text" name="state" value="{{ old('state') }}" maxlength="50" class="form-control" id="StateProvince" aria-describedby="StateProvince" placeholder="State/Province">
                                </div>
                                <!-- <div class="mb-3">
                                    <label for="faxNumber*" class="form-label">Fax Number*</label>
                                    <input type="number" min="0" class="form-control" id="faxNumber" aria-describedby="faxNumber" placeholder="faxNumber">
                                </div> -->
                                <button type="submit" class="btn btn-primary text-uppercase mt-5">Register</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    @include('frontend.'.$active_theme -> theme_abrv.'.components.footer');
</div>
@endsection
@section('scripts')
<!-- INPUT MASK -->
<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        function validateEmail(email) {
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }

        $("[data-inputmask]").inputmask({greedy: false, placeholder:""});

        $('form.registration-form').on('submit', function() {
            var allOk = true;
            $('input[data-required="true"], select[data-required="true"]').each(function() {
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

            if (allOk && !validateEmail($('input[name="reg-email"]').val())) {
                $('input[name="reg-email"]').addClass('is-invalid');
                allOk = false;
            }
            if (allOk && $('input[name="reg-password"]').val() != $('input[name="cpassword"]').val()) {
                $('input[name="reg-password"], input[name="cpassword"]').addClass('is-invalid');
                allOk = false;
            }

            console.log(allOk);
            return allOk;
        });
    });
</script>
@endsection

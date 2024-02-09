@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp

@extends('frontend.'.$active_theme -> theme_abrv.'.layouts.app')
@section('title','Contact Us')
@section('content')
    <div class="wrapper light-grey-bg p-0">
        @include('frontend.'.$active_theme -> theme_abrv.'.components.header')
        <main class="main-content">
            @if (session('success'))
                <script>
                    toastr.success('Success',"{{ session('success') }}");
                </script>
            @endif
            @if (session('error'))
                <script>
                    toastr.error('Error',"{{ session('error') }}");
                </script>
            @endif
            <section class="collection-section">
                <div class="container">
                    <div class="d-flex flex-lg-row flex-sm-column flex-dir-col">
                        <div class="col-lg-6 col-sm-12 m-md-2 mb-sm-3 contact-mb3 col-12">
                            <div class="bg-white p-sm-5">
                                <h2 class="text-center">Corporate Office</h2>
                                <div class="companyInfo p-4">
                                    <p>900 Marine Drive Calhoun, GA 30701</p>
                                    <p class="d-flex flex-column mt-4">
                                        <a href="tel:7066028857"> 706.602.8857  </a>
                                        <a href="tel:8774997847"> 877.499.7847 (toll free) </a>
                                        <a href="tel:8774997847"> 706.602.3970 (fax) </a>
                                    </p>
                                    <a href="mailto:info@rizzyhome.com" class="link text-decoration">info@rizzyhome.com</a>
                                    <div class="social-icons d-flex flex-row mt-5">
                                    <a href="{{$pages -> all_pages -> sections -> footer_social_media -> facebook_url}}" class="fs-4" target="_blank">
                                        <img src="{{asset('/RZY/images/social/facebook.svg')}}" alt="facebook" />
                                    </a>
                                    <a href="{{$pages -> all_pages -> sections -> footer_social_media -> insta_url}}" class="fs-4" target="_blank">
                                        <img src="{{asset('/RZY/images/social/instagram.svg')}}" alt="instagram" />
                                    </a>
                                    <a href="{{$pages -> all_pages -> sections -> footer_social_media -> twitter_url}}" class="fs-4" target="_blank">
                                        <img src="{{asset('/RZY/images/social/twitter.svg')}}" alt="twitter" />
                                    </a>
                                    <a href="{{$pages -> all_pages -> sections -> footer_social_media -> youtube_url}}" class="fs-4" target="_blank">
                                        <img src="{{asset('/RZY/images/social/youtube.svg')}}" alt="youtube" />
                                    </a>
                                    <a href="{{$pages -> all_pages -> sections -> footer_social_media -> pinterest_url}}" class="fs-4" target="_blank">
                                        <img src="{{asset('/RZY/images/social/pinterest.svg')}}" alt="pinterest" />
                                    </a>
                                    <a href="{{$pages -> all_pages -> sections -> footer_social_media -> linkedin_url}}" class="fs-4" target="_blank">
                                        <img src="{{asset('/RZY/images/social/linkedin.svg')}}" alt="linkedin" />
                                    </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12 m-md-2">
                            <div class="bg-white p-sm-5">
                                <h2 class="text-center">Contact Us</h2>
                                <p class="text-center">Please enter the following information</p>
                                <form id="contact_us" class="d-flex flex-column mt-5 dafault-form p-5 pt-3" method="post" action="{{route('form.submission', ['contact_us'])}}">
                                    @if (Session::has('message'))
                                    <div class="alert alert-{{Session::get('message')['type']}}">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        {{Session::get('message')['body']}}
                                    </div>
                                    @endif
                                    @csrf
                                    <input type="hidden" name="form" value="contact_us" />
                                    <div class="mb-3">
                                        <label for="fullname" class="form-label">Full  name*</label>
                                        <input type="text" class="form-control" id="fullname" name="name" aria-describedby="Name" placeholder="Full Name" maxlength="35" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="InputEmail" class="form-label">Email address*</label>
                                        <input type="email" class="form-control" id="InputEmail" name="email" aria-describedby="emailHelp" placeholder="example@domainname.com" maxlength="60" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="Companyname" class="form-label">Company name*</label>
                                        <input type="text" class="form-control" id="Companyname" name="company" aria-describedby="CompanyName" placeholder="Natalie" maxlength="60" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="PhoneNumber*" class="form-label">Phone Number*</label>
                                        <input type="number" min="0" class="form-control" id="PhoneNumber" name="phone" aria-describedby="PhoneNumber" placeholder="Phone Number" maxlength="35" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="Inquiry" class="form-label">Inquiry Details*</label>
                                        <textarea class="form-control" name="Inquiry" aria-describedby="Inquiry" placeholder="Inquiry Details" style="min-height: 100px;" maxlength="400" required> </textarea>
                                    </div>
                                    <button type="submit " class="btn btn-primary text-uppercase mt-5 ">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </main>
        @include('frontend.'.$active_theme -> theme_abrv.'.components.footer')
    </div>
@endsection
@section('scripts')
    <style>
        .grid-item {
            width: 25%;
        }

        .grid-item--width2 {
            width: 50%;
        }
    </style>
@endsection

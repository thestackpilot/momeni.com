@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp 

@extends('frontend.'.$active_theme -> theme_abrv.'.layouts.app')
@section('title','Feedback')
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
                    <div class="d-flex flex-lg-row flex-sm-column flex-dir-col left-careers-imageside">
                        <div class="col-lg-6 col-sm-12 m-md-2 mb-sm-3 contact-mb3 contact-balance col-12" style="background-image: url("{{url('/').'/images/about-us-side.jpg'}}")">
                            <img src="{{url('/').'/RZY/images/feedback-side.png'}}" alt="about rizzy homes" />
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12 bg-white contact-balance careers-form-box">
                            <div class="w-100">
                                <h2 class="text-center pt-3">Feedback</h2>
                                <p class="text-center">Please enter the following information</p>
                                <form id="feedback" class="d-flex flex-column mt-5 dafault-form p-5 pt-3" method="post" action="{{route('form.submission', ['feedback'])}}">
                                    @if (Session::has('message'))
                                    <div class="alert alert-{{Session::get('message')['type']}}">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        {{Session::get('message')['body']}}
                                    </div>
                                    @endif
                                    @csrf
                                    <input type="hidden" name="form" value="feedback" />
                                    <div class="mb-3">
                                        <label for="InputEmail" class="form-label">Email address*</label>
                                        <input type="email" class="form-control" id="InputEmail" name="email" aria-describedby="emailHelp" placeholder="example@domainname.com" maxlength="60" required>

                                    </div>
                                    <div class="mb-3 row">
                                        <div class="mb-3 col">
                                        <label for="FirstName" class="form-label">First name*</label>
                                        <input type="text" class="form-control" id="fullname" name="fullname" aria-describedby="Name" placeholder="First Name" maxlength="35" required>
                                        </div>
                                        <div class="mb-3 col">
                                        <label for="LastName" class="form-label">Last  name*</label>
                                        <input type="text" class="form-control" id="fullname" name="fullname" aria-describedby="Name" placeholder="Last Name" maxlength="35" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="Inquiry" class="form-label">Feedback</label>
                                        <textarea class="form-control" name="Inquiry" aria-describedby="Inquiry" placeholder="Feedback" maxlength="400" style="min-height: 100px;"> </textarea>
                                    </div>

                                    <button type="submit " class="btn btn-primary text-uppercase mt-2 ">Submit</button>
                                </form>
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

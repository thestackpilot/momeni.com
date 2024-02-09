@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp 

@extends('frontend.'.$active_theme -> theme_abrv.'.layouts.app')
@section('title','Careers')
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
                        <div class="col-lg-6 col-sm-12 m-md-2 mb-sm-3 contact-mb3 contact-balance col-12">
                            <img src="{{url('/').'/RZY/images/careers-side2.png'}}" alt="about rizzy homes" />
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12 contact-balance careers-form-box m-0 bg-white">
                            <div class="">
                                <h2 class="text-center pt-4">Careers</h2>
                                <p class="text-center">Please enter the following information</p>
                                <form id="careers" enctype="multipart/form-data" class="d-flex flex-column dafault-form p-5 pb-3 pt-3" method="post" action="{{route('form.submission', ['careers'])}}">
                                    @if (Session::has('message'))
                                    <div class="alert alert-{{Session::get('message')['type']}}">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        {{Session::get('message')['body']}}
                                    </div>
                                    @endif
                                    @csrf
                                    <input type="hidden" name="form" value="careers" />
                                    <div class="mb-3">
                                        <label for="InputEmail" class="form-label">Email address*</label>
                                        <input type="email" class="form-control" id="InputEmail" name="email" aria-describedby="emailHelp" placeholder="example@domainname.com" required>

                                    </div>
                                    <div class="mb-3 row">
                                        <div class="mb-3 col">
                                        <label for="FirstName" class="form-label">First name*</label>
                                        <input type="text" class="form-control" id="fullname" maxlength="35" name="firstName" aria-describedby="Name" placeholder="First Name" required>
                                        </div>
                                        <div class="mb-3 col">
                                        <label for="LastName" class="form-label">Last  name*</label>
                                        <input type="text" class="form-control" id="fullname" maxlength="35" name="lastName" aria-describedby="Name" placeholder="Last Name" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="mb-3 col">
                                        <label for="PhoneNumber*" class="form-label">Phone Number*</label>
                                        <input type="text" class="form-control" data-inputmask="'mask': '999-999-9999'" maxlength="12"  minlength="12" id="PhoneNumber" name="phone" aria-describedby="PhoneNumber" placeholder="Phone Number" required>
                                        </div>
                                        <div class="mb-3 col">
                                        <label for="careers_file" class="form-label">Choose File</label>
                                        <input class="form-control" style="border: none;" type="file" name="attachment" aria-describedby="careers_file" placeholder="Choose File">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="Inquiry" class="form-label">Inquiry Details</label>
                                        <textarea class="form-control" name="Inquiry" aria-describedby="Inquiry" placeholder="Inquiry Details" style="min-height: 100px;" required> </textarea>
                                    </div>

                                    <button type="submit " class="btn btn-primary text-uppercase pt-2">Submit</button>
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
<!-- INPUT MASK -->
<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("[data-inputmask]").inputmask({greedy: false, placeholder:""});
    });
</script>
<style>
    .grid-item {
        width: 25%;
    }

    .grid-item--width2 {
        width: 50%;
    }
</style>
@endsection

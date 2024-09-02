@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp 

@extends('frontend.'.$active_theme -> theme_abrv.'.layouts.app')
@section('title','About Us')
@section('content')
    <div class="wrapper">
        @include('frontend.'.$active_theme -> theme_abrv.'.components.header')
        <main class="main-content">
            <section class="about-rizzyhome pb-0">
                <div class="container">
                    <h1 class="section-title text-center mb-5">About Us</h1>
                    <div class="col-md-12 flex-lg-row flex-sm-column mt-5 d-flex align-items-center justify-content-between flex-dir-col">
                        <div class="col-lg-5 col-sm-12 col-12">
                            <!-- <p>The direct result of collaboration between two entrepreneurial brothers, Rizzy Home is the combined effort of Rizwan and Shamsu Ansari. Originally started as Rizzy Rugs and Home Texco, Rizzy Home now offers an extensive assortment of rugs, luxury bedding ensembles, designer pillows, accent throws and reclaimed, wooden furniture under one company name.</p>
                            <p class="mt-4">With decades of experience in the home interior business, the Ansari brothers offer an incomparable ability to deliver superior merchandise and have become industry leaders in design, manufacturing and service. Having established U.S. operations in 2007, with an additional 105,000 square feet of warehouse space recently added in Calhoun, Georgia, Rizzy Home is able to efficiently handle all the needs of its quickly growing clientele.</p> -->
                            {!!$pages -> about_us -> sections -> about_us_items -> about_us_description_1!!}
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12 aboutImg">
                            <!-- <img src="{{url('/')}}/RZY/images/about-us-side.jpg" alt="about rizzy homes" /> -->
                            <img src="{{$pages -> about_us -> sections -> about_us_items -> about_us_image_1}}" alt="about rizzy homes" />
                        </div>
                    </div>
                </div>
            </section>
            <section class="about-rizzyhome pt-0">
                <div class="container">
                    <div class="col-md-12 flex-column mt-5 d-flex">
                        <div class="col-md-12 col-sm-12">
                            <!-- <p>In April of 2010, the company decided to expand its rug production capabilities to include a machine-made rug factory located in India. The state of the art facility provides every necessary step of the power-loomed production process under one roof from polypropylene yarn extrusion to the final finishing of each and every rug.</p>
                            <p class="mt-4">All of Rizzy Home's area rugs, fine linens, furniture and complementary accessories are created using the highest quality materials. From jacquard woven fabrics to silks and signature-dyed yarn, Rizzy's unique embellishments and custom ornamentation add richness and depth to all of the company's exclusive products. With a wide assortment of product choice and combinations, Rizzy Home is making it easier than ever for clients to create homes and interior spaces that are honest expressions of their true personalities.</p> -->
                            {!!$pages -> about_us -> sections -> about_us_items -> about_us_description_1!!}
                        </div>
                        <div class="col-md-12 col-sm-12 mt-4 aboutImg">
                            <!-- <img src="{{url('/')}}/images/about-us-bottom.jpg" alt="about rizzy homes" class="img-responsive" /> -->
                            <img src="{{$pages -> about_us -> sections -> about_us_items -> about_us_image_2}}" alt="about rizzy homes" class="img-responsive" />
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- <script src="assets/js/popper.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="{{url('/')}}/js/main.js"></script>
    <script>
        $( document ).ready(function() {
            $('#InputPassword, ConfirmInputPassword').on('keyup', function () {
                if ($('#password').val() == $('#ConfirmInputPassword').val()) {
                    $('#message').html('Matching').css('color', 'green');
                } else
                    $('#message').html('Not Matching').css('color', 'red');
            });
        });
    </script>
@endsection

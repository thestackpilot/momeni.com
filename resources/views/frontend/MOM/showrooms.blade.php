@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp
@extends('frontend.'.$active_theme -> theme_abrv.'.layouts.app')
@section('title','Show Rooms')
@section('content')
    <div class="wrapper">
        @include('frontend.'.$active_theme -> theme_abrv.'.components.header')
    <div class="site-wrapper-reveal">
        <div class="showroom-area mt-30 section-space--mb_60">
            <div class="container">
                <div class="row" id="Showrooms">
                    <div class="col-lg-12">
                        <div class="section-title text-center mb-20">
                        <h2 class="section-title--one section-title--center">Our Showrooms</h2>
                        </div>
                    </div>
                </div>
                <div class="column">
                    @if(isset($showrooms -> mom_showrooms))
                        @foreach($showrooms -> mom_showrooms -> metas as $showroom)

                        <div class="showrom-inner-div">
                            <div class="col-lg-6 col-md-6 col-sm-12 showroom-image">
                            @if(!empty($showroom->image))
                                <img class="img-full" src="{{asset($showroom->image)}}" alt="{{ $showroom -> title }}">
                            @endif
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 showroom-details">
                                <p class="showroom-title"> <strong>{{ $showroom -> title }}</strong></p>
                                <p class="showroom-address">{!! $showroom -> address !!}</p>
                            </div>
                        </div>

                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
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

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script> --}}
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

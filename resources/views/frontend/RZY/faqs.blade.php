@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp 

@extends('frontend.'.$active_theme -> theme_abrv.'.layouts.app')
@section('title','FAQs')
@section('content')
    <div class="wrapper">
        @include('frontend.'.$active_theme -> theme_abrv.'.components.header')
        <main class="main-content">

            <section class="about-rizzyhome pb-0">
                <div class="container">
                    <h1 class="section-title text-center mb-5">FAQs</h1>
                    <div class="col-md-12 flex-column flex-sm-column mt-5 mb-5 d-flex align-items-center justify-content-between flex-dir-col" style="min-height: 10vh;">
                        <h1 class="mb-4 font-ropa text-center"> Currently, There are no FAQs available</h1>
                        <h4 class="mb-4 font-ropa text-center"> Please visit us after some time. Thanks.. </h4>
                        <a href="{{URL::to('/')}}" class="btn btn-primary text-uppercase">Go To Home</a>
                    </div>
                </div>
            </section>

        </main>
        @include('frontend.'.$active_theme -> theme_abrv.'.components.footer')
    </div>
@endsection

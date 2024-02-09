@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp 

@extends('frontend.'.$active_theme -> theme_abrv.'.layouts.app')
@section('title','Showrooms')
@section('content')
    <div class="wrapper light-grey-bg p-0">
        @include('frontend.'.$active_theme -> theme_abrv.'.components.header')
        <main class="main-content">

            <section class="collection-section">
                <div class="container">
                    <div class="d-flex flex-row justify-content-center bg-white">
                        <div class="p-5 col-md-9 col-sm-12 m-2">
                            <h2 class="text-center">Showrooms</h2>
                            <div class="d-flex p-lg-5 p-2 justify-content-between flex-lg-row flex-sm-column flex-dir-col flex-wrap">

                                @if(isset($showrooms -> rzy_showrooms))
                                    @foreach($showrooms -> rzy_showrooms -> metas as $showroom)

                                    <div class="col-lg-5 col-sm-12 showroom col-12">
                                        @if(!empty($showroom->image))
                                            <p class="img-fluid"><img src="{{asset($showroom->image)}}" alt="{{ $showroom -> title }}"></p>
                                        @endif
                                        <h2 class="font-ropa">{{ $showroom -> title }}</h2>
                                        <h3 class="font-crimson mt-4">{{ $showroom -> area }}</h3>
                                        <p class="font-ropa">{{ $showroom -> address }}</p>
                                    </div>

                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </main>
        @include('frontend.'.$active_theme -> theme_abrv.'.components.footer')
    </div>
@endsection

@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp 

@extends('frontend.'.$active_theme -> theme_abrv.'.layouts.app')
@section('title',$main_collection['Description'])
@section('content')
    <div class="wrapper">
        @include('frontend.'.$active_theme -> theme_abrv.'.components.header')
        <main class="main-content">
            <section class="collection-section">
                <div class="container">
                    <h1 class="section-title text-center mb-5 font-ropa">{{$main_collection['Description']}}</h1>
                    <div class="d-flex flex-row justify-content-lg-between fav-pg">
                        <div class="col-md-3 d-flex flex-column sidebar-main">
                        @include('frontend.'.$active_theme -> theme_abrv.'.components.filters')
                        </div>
                        <div class="col-md-9 col-sm-12 col-12">
                            <!-- d-flex flex-wrap bottom-cats -->
                            <div class="row" data-masonry="{'percentPosition': true}">
                                @foreach($favourites['Favourities'] as $favs)
                                    <div class="card" style="width: 50%;">
                                        <figure class="collections position-relative m-0 main-collections">
                                            <a href="{{$favs['LinkUrl']}}" >
                                                <img src="{{CommonController::getApiFullImage($favs['Image'])}}" onerror="{{url('/').'/images/placeholder-full.jpg'}}" /> 
                                                <div class="overlay"></div>
                                                <figcaption>
                                                    {{$favs['Title']}}
                                                </figcaption>
                                            </a>
                                        </figure>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </section>
        </main>
        @include('frontend.'.$active_theme -> theme_abrv.'.components.footer')
    </div>
@endsection

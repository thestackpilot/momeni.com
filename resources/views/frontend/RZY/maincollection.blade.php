@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp 

@extends('frontend.'.$active_theme -> theme_abrv.'.layouts.app')
@section('title','Main Categories')
@section('content')
    <div class="wrapper">
    @include('frontend.'.$active_theme -> theme_abrv.'.components.header')
        <main class="main-content">
            <section class="collection-section">
                <div class="container">
                    <div class="container d-flex flex-row  flex-wrap all-cat-page">
                        @foreach($main_collections['MainCollections'] as $collection)
                        <div class="simple-grid-item col-sm-12 col-md-3">
                            <a href="{{ route('frontend.favourite' , $collection['MainCollectionID'] ) }}" class="d-flex flex-column text-decoration-none">
                                <figure class="overflow-hidden m-0">
                                    <img src="{{ CommonController::getApiFullImage($collection['ImageName'])}}" class="img-responsive" onerror="this.onerror=null; this.src='{{ url('/').ConstantsController::IMAGE_PLACEHOLDER }}'" />
                                </figure>
                                <span class="product-lable text-truncate" title="{{ $collection['Description'] }}">{{ $collection['Description'] }}</span>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    
                </div>
            </section>
        </main>
    @include('frontend.'.$active_theme -> theme_abrv.'.components.footer')
    </div>
@endsection


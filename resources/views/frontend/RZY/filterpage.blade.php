@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp 

@extends('frontend.'.$active_theme -> theme_abrv.'.layouts.app')
@section('title','Filter Pages')
@section('content')
    <div class="wrapper">
        @include('frontend.'.$active_theme -> theme_abrv.'.components.header')
        <main class="main-content">
            <section class="collection-section">
                <div class="container">
                    <h1 class="section-title text-center text-uppercase font-ropa text-center mt-0" style="letter-spacing: 1px;">{{$view_title}}</h1>
                    <hr class="minicart-seprator filter-page-separator">
                    @foreach($filterpages as $collections)
                        @if (isset($type) && $type != '' && $type != $collections['MainCollectionID']) @continue; @endif
                        <h1 class=" text-center text-uppercase mb-4 font-ropa text-center">{{$collections['Name']}}</h1>
                        <div class="container d-flex flex-row flex-wrap collection-sub-items all-cat-page">
                        @foreach($collections['Designs'] as $k => $design)
                            @if((isset($type) && ($type == '' || $type != $collections['MainCollectionID'])) && $k > 3) @break; @endif
                            <div class="simple-grid-item">
                                <a href="{{$design['LinkUrl']}}" class="d-flex flex-column text-decoration-none">
                                    <figure class="overflow-hidden m-0 p-3">
                                        <!-- <img src="{{CommonController::getApiFullImage($design['ImageName'])}}" class="img-responsive" onerror="this.onerror=null; this.src='{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'" /> -->
                                        <div style="background-image: url('{{CommonController::getApiFullImage($design['ImageName'])}}'), url('{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'); min-height: 270px; background-repeat: no-repeat; background-size: contain; background-position: center center;" class="img-responsive"></div>
                                        @php
                                        $badges = [
                                            [
                                                'condition'     => $design['SpecialBuy'],
                                                'background'    => 'special-buy',
                                                'label'         => 'Special Buy'
                                            ],
                                            [
                                                'condition'     => $design['Clearence'],
                                                'background'    => 'clearance',
                                                'label'         => 'Clearence'
                                            ],
                                            [
                                                'condition'     => $design['NewArrivalExpiry'],
                                                'background'    => 'new-arrival',
                                                'label'         => 'New Arrival'
                                            ],
                                            [
                                                'condition'     => $design['TopSeller'],
                                                'background'    => 'top-seller',
                                                'label'         => 'Top Seller'
                                            ],
                                        ];
                                        $count      = 0;
                                        foreach($badges as $badge)
                                            if(strtolower($badge['condition']) != 'false' && strtolower($badge['condition']) !== '') {
                                                echo '<div style="background: url(/RZY/images/labels/'.$badge['background'].'.png)" class="position-absolute handles-position"></div>';
                                            }
                                        @endphp
                                    </figure>
                                    <span class="product-lable text-truncate" title="{{$design['DesignID']}}">{{$design['DesignID']}}</span>
                                </a>
                            </div>
                        @endforeach
                        </div>
                        @if(( isset($type) && ($type == '' || $type != $collections['MainCollectionID'])) && $collections && count($collections['Designs']) > 4) 
                        <div class="d-flex justify-content-center mb-4">
                            @if (isset($filter_page) && $filter_page)
                            <a href="{{route('frontend.designs',[$collections['MainCollectionID'], base64_encode('{"Filters": [{"FilterID":"'.str_replace(' ','_',trim($view_title)).'","Values":["1"]}]}'), $search_string ?? 0])}}" class="btn btn-primary text-uppercase">Explore More</a>
                            @else
                            <a href="{{route('frontend.search',[base64_encode($search_string), $collections['MainCollectionID']])}}" class="btn btn-primary text-uppercase">Explore More</a>
                            @endif
                        </div>
                        @endif
                        @if(!$loop->last)
                            <hr class="minicart-seprator filter-page-separator">
                        @endif
                        @if (isset($type) && $type != '' && $type == $collections['MainCollectionID']) @break; @endif
                    @endforeach
                </div>
            </section>

        </main>
        @include('frontend.'.$active_theme -> theme_abrv.'.components.footer')
    </div>
@endsection

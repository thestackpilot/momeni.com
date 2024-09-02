@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp

@extends('frontend.'.$active_theme -> theme_abrv.'.layouts.app')
@section('title',$main_collection['Description'] .' '. array_key_first($collections) .' Collections')
@section('content')
<div class="wrapper">
    @include('frontend.'.$active_theme -> theme_abrv.'.components.header')
    <main class="main-content">
        <section class="collection-section">
            <div class="container">
                <div class="d-flex flex-md-row justify-content-lg-between justify-content-around flex-dir-col responsive-mb" id="collection_heading">
                    <h1 class="section-title text-left mb-md-5 font-ropa col-sm-12 col-12 text-center">{{$main_collection['Description'] .' - '. array_key_first($collections)}}</h1>
                </div>
                <div class="d-flex flex-row justify-content-lg-between coll-pg">
                    <div class="col-md-3 d-flex flex-column sidebar-main">
                        @include('frontend.'.$active_theme -> theme_abrv.'.components.filters')
                    </div>
                    <div class="col-md-9 col-sm-12 col-12 d-flex flex-row justify-content-left flex-wrap three-col product-listing collection-sub-items" id="sub_collections_wrapper">
                        @if(count($collections))
                        @foreach($collections[array_key_first($collections)] as $collection)
                        <div class="simple-grid-item {{(isset($design_page) && $design_page) ? 'design-page' : ''}}">
                            <a href="{{$collection['LinkUrl']}}" class="d-flex flex-column text-decoration-none">
                                <figure class="overflow-hidden m-0 p-3">
                                    <!-- <img src="{{$collection['ImageUrl']}}" data-orig-src="{{$collection['ImageUrl']}}" onerror="this.onerror=null; this.src='{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'" class="img-responsive" /> -->
                                    <div style="background-image: url('{{$collection['ImageUrl']}}'), url('{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'); min-height: 270px; background-repeat: no-repeat; background-size: contain; background-position: center center;" class="img-responsive"></div>
                                    @php
                                    $badges = [
                                        [
                                            'condition'     => $collection['SpecialBuy'],
                                            'background'    => 'special-buy',
                                            'label'         => 'Special Buy'
                                        ],
                                        [
                                            'condition'     => $collection['Clearence'],
                                            'background'    => 'clearance',
                                            'label'         => 'Clearence'
                                        ],
                                        [
                                            'condition'     => $collection['NewArrivalExpiry'],
                                            'background'    => 'new-arrival',
                                            'label'         => 'New Arrival'
                                        ],
                                        [
                                            'condition'     => $collection['TopSeller'],
                                            'background'    => 'top-seller',
                                            'label'         => 'Top Seller'
                                        ],
                                    ];
                                    foreach($badges as $badge)
                                        if(strtolower($badge['condition']) != 'false' && $badge['condition'] != '') {
                                            echo '<div style="background: url(/RZY/images/labels/'.$badge['background'].'.png)" class="position-absolute handles-position"></div>';
                                        }
                                    @endphp
                                </figure>
                                @if(isset($design_page) && $design_page)
                                <span class="product-lable text-truncate" title="{{$collection['DesignID']}}">{{$collection['DesignID']}}</span>
                                @else
                                <span class="product-lable text-truncate" title="{{$collection['Description']}}">{{$collection['Description']}}</span>
                                @endif
                            </a>
                        </div>
                        @endforeach
                        @endif
                        <div class="pageLoader d-none" id="pageLoader">
                            <div id="loading_msg" class="d-flex flex-column text-center">
                                <div class="spinner-border" role="status" style="margin: 0 auto;">
                                    <span class="sr-only" style="opacity:0;">Loading...</span>
                                </div>
                                <p class="loadinMsg mt-3">Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    @include('frontend.'.$active_theme -> theme_abrv.'.components.footer')
</div>
@endsection
@if(isset($design_page) && $design_page)
@section('scripts')
<script>
    $(document).ready(function() {
        var page = 2;
        var currentscrollHeight = 0;
        var currentURL = window.location.href;
        $(window).scroll(function() {
            if (currentURL != window.location.href) page = 2;
            if (page < 0 || !$('.product-listing.collection-sub-items .simple-grid-item').hasClass('design-page') || typeof $('.simple-grid-item').length === 'undefined' || $('.simple-grid-item').length < 1) return;
            try {
                const scrollHeight = $(document).height();
                const scrollPos = Math.floor($(window).height() + $(window).scrollTop());
                const isBottom = scrollHeight - $('footer').height() < scrollPos;
                if (isBottom && currentscrollHeight < scrollHeight && $('.simple-grid-item').length > 29) {
                    $('.pageLoader').removeClass('d-none');
                    currentURL = `${window.location.href}`;
                    $.post(`${window.location.href}/${page}`, {
                        _token: '{{csrf_token()}}'
                    }, function(response) {
                        if (response.success && response.data.Designs.length) {
                            response.data.Designs.forEach((design) => {
                                $(`<div class="simple-grid-item">
                                    <a href="${design.LinkUrl}" class="d-flex flex-column text-decoration-none">
                                        <figure class="overflow-hidden m-0 p-3">
                                            <img src="${design.ImageUrl}" onerror="this.onerror=null; this.src='{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'" class="img-responsive" />
                                            ${get_badges(design)}
                                        </figure>
                                        <span class="product-lable text-truncate" title="${design.DesignID}">${design.DesignID}</span>
                                    </a>
                                </div>`).appendTo($('.product-listing.collection-sub-items'));
                            });
                            page++;
                        } else {
                            page = -1;
                        }
                        $('.pageLoader').addClass('d-none');
                    });
                    currentscrollHeight = scrollHeight;
                }
            } catch (e) {
                $('.pageLoader').addClass('d-none');
            }
        });

        function get_badges(design) {
            var _return = '';
            const badges = [
                {
                    'condition'     : design['SpecialBuy'],
                    'background'    : 'special-buy',
                    'label'         : 'Special Buy'
                },
                {
                    'condition'     : design['Clearence'],
                    'background'    : 'clearance',
                    'label'         : 'Clearence'
                },
                {
                    'condition'     : design['NewArrivalExpiry'],
                    'background'    : 'new-arrival',
                    'label'         : 'New Arrival'
                },
                {
                    'condition'     : design['TopSeller'],
                    'background'    : 'top-seller',
                    'label'         : 'Top Seller'
                },
            ];
            var position    = -50;
            var count       = 0;
            badges.forEach(function(badge) {
                if((badge['condition']).toLowerCase() != 'false' && (badge['condition']).toLowerCase() != '') {
                    _return += `<div style="background: url('/RZY/images/labels/${badge['background']}.png')" class="position-absolute handles-position"></div>`;
                }
            });

            return _return;
        }
    });
</script>
@endsection
@endif

@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp

@extends('frontend.'.$active_theme -> theme_abrv.'.layouts.app')
@section('title','Home')
@section('content')
@include('frontend.'.$active_theme -> theme_abrv.'.components.header')

<div class="site-wrapper-reveal">
    <section class="home-main-slider mb-0">
        @include('frontend.'.$active_theme -> theme_abrv.'.components.slider')
    </section>
    @if($pages -> home -> sections -> new_arrivals->is_active)
    <section class="featuted-product see-whats-new">
        <div class="container">
            <div class="featuted-product-main">
                <div class="row align-items-center featuted-product-one">
                    <div class="col-lg-6 col-md-6 order-md-1 order-1">
                        <div class="featured-product-contect key-p">
                            <h2 class="section-title-one"><a href="{{$pages -> home -> sections -> new_arrivals -> image_1_url}}">{!!$pages -> home -> sections -> new_arrivals -> image_1_title!!}</a></h2>
                            <p class="mt-30 key-p">{{$pages -> home -> sections -> new_arrivals -> image_1_caption}}</p>
                            <div class="button-box section-space--mt_20"> <a href="{{$pages -> home -> sections -> new_arrivals -> image_1_url}}" class="btn btn--md btn--border_11 d-inline hm-cu-btn">View Now</a> </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 order-md-2 order-2" style="padding:0px">
                        <a href="{{$pages -> home -> sections -> new_arrivals -> image_1_url}}" class="fetaure-ancher ff-1">
                            <div class="side-images" style="background-image: url('{{$pages -> home -> sections -> new_arrivals -> image_1_image}}')" ;> </div>
                            <!-- <img src="{{$pages -> home -> sections -> new_arrivals -> image_1_image}}" class="img-fluid" alt="{{$pages -> home -> sections -> new_arrivals -> image_1_title}}"> -->
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="featuted-product nothing-best">
        <div class="container">
            <div class="featuted-product-main">
                <div class="row align-items-center featuted-product-one">
                    <div class="col-lg-6 col-md-6 order-md-1 order-2" style="padding:0px">
                        <a href="{{$pages -> home -> sections -> new_arrivals -> image_2_url}}" class="fetaure-ancher ff-2">
                            <div class="side-images" style="background-image: url('{{$pages -> home -> sections -> new_arrivals -> image_2_image}}')" class="img-fluid" alt="{{$pages -> home -> sections -> new_arrivals -> image_2_title}}" ;> </div>
                            <!-- <img src="{{$pages -> home -> sections -> new_arrivals -> image_2_image}}" class="img-fluid" alt="{{$pages -> home -> sections -> new_arrivals -> image_2_title}}"> -->
                        </a>
                    </div>
                    <div class="col-lg-6 col-md-6 order-md-2 order-1">
                        <div class="featured-product-contect key-p">
                            <h2 class="section-title-one"><a href="{{$pages -> home -> sections -> new_arrivals -> image_2_url}}">{!!$pages -> home -> sections -> new_arrivals -> image_2_title!!}</a></h2>
                            <p class="mt-30 key-p">{{$pages -> home -> sections -> new_arrivals -> image_2_caption}}</p>
                            <div class="button-box section-space--mt_20"> <a href="{{$pages -> home -> sections -> new_arrivals -> image_2_url}}" class="btn btn--md btn--border_11 d-inline hm-cu-btn">View Now</a> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="featuted-product our-best-seller">
        <div class="container">
            <div class="featuted-product-main">
                <div class="row align-items-center featuted-product-one">
                    <div class="col-lg-6 col-md-6 order-md-1 order-1">
                        <div class="featured-product-contect key-p">
                            <h2 class="section-title-one"><a href="{{$pages -> home -> sections -> new_arrivals -> image_3_url}}">{!!$pages -> home -> sections -> new_arrivals -> image_3_title!!}</a></h2>
                            <p class="mt-30 key-p">{{$pages -> home -> sections -> new_arrivals -> image_3_caption}}</p>
                            <div class="button-box section-space--mt_20"> <a href="{{$pages -> home -> sections -> new_arrivals -> image_3_url}}" class="btn btn--md btn--border_11 d-inline hm-cu-btn">View Now</a> </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 order-md-2 order-2" style="padding:0px">
                        <a href="{{$pages -> home -> sections -> new_arrivals -> image_3_url}}" class="fetaure-ancher ff-1">
                            <div class="side-images" style="background-image: url('{{$pages -> home -> sections -> new_arrivals -> image_3_image}}')" ;> </div>
                            <!-- <img src="{{$pages -> home -> sections -> new_arrivals -> image_1_image}}" class="img-fluid" alt="{{$pages -> home -> sections -> new_arrivals -> image_1_title}}"> -->
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    <!-- <section class="featuted-product our-best-seller">
            <div class="container">
                <div class="featuted-product-main double-img">
                    <div class="row align-items-center featuted-product-one">
                        <div class="col-lg-6 col-md-6 order-md-1 order-2" style="padding:0px">
                            <a href="{{$pages -> home -> sections -> new_arrivals -> image_3_url}}" class="fetaure-ancher">
                            <div class="side-images" style="background-image: url('{{$pages -> home -> sections -> new_arrivals -> image_3_image}}')" class="img-fluid" alt="{{$pages -> home -> sections -> new_arrivals -> image_3_title}}";> </div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-6  order-md-2 order-1" style="padding:0px">
                        <div class="best-seller-custom-img">
                            <div class="side-images" style="background-image: url('{{$pages -> home -> sections -> new_arrivals -> image_4_image}}');" class="img-fluid" alt=""> </div>
                        </div> 
                            <div class="featured-product-contect key-p" style="padding:0 15px">
                                <h2 class="section-title-one"><a href="{{$pages -> home -> sections -> new_arrivals -> image_3_url}}">{{$pages -> home -> sections -> new_arrivals -> image_3_title}}</a></h2>
                                <p class="mt-30 key-p">{{$pages -> home -> sections -> new_arrivals -> image_3_caption}}</p>
                                <div class="button-box section-space--mt_20"> <a href="{{$pages -> home -> sections -> new_arrivals -> image_3_url}}" class="btn btn--md btn--border_11 d-inline hm-cu-btn">View Now</a> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>     -->
	@php
		$collection_available = false;
		if(isset($sliders -> collection_slider) && $sliders -> collection_slider ) {
			foreach($sliders -> collection_slider -> metas as $slide) if ($slide -> is_active) $collection_available = true;
		}
	@endphp
        @if(isset($sliders -> collection_slider) && $collection_available )
    <section class="rugs-collections featuted-product mb-5 mt-5">
        <div class="container">
            <div class="featuted-product-main">
                {{-- <input type="hidden" value={{ $sliders -> collection_slider -> metas }} > --}}
                                @if(isset($sliders -> collection_slider))
                                @foreach($sliders -> collection_slider -> metas as $k => $slide)
                                @if($slide -> is_active)
                <div class="row align-items-center featuted-product-one">
                    <div class="col-lg-6 col-md-6 order-md-{{$k % 2 === 0 ? '2' : '1'}} order-2 rugs-margin">
                        <div class="featured-product-contect key-p">
                            <span> COLLECTIONS </span>
                            <!-- <h2 class="section-title-one"><a href="#0">Rugs</a></h2>
                            <p class="mt-30 key-p">A statement in understatement. The Amber Lewis × hand-knotted Collins Collection is in stock now.</p>-->
                            <h2 class="section-title-one"><a href="{{$slide -> link}}">{{$slide -> caption_1}}</a></h2>
                            <p class="mt-30 key-p">{{$slide -> caption_2}}</p>
                            <div class="button-box section-space--mt_20"> <a href="{{$slide -> link}}" class="btn btn--md btn--border_11 d-inline hm-cu-btn">View Now</a> </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 order-md-1 order-1 " style="padding:0px">
                        <!-- <a href="{{$pages -> home -> sections -> new_arrivals -> image_2_url}}" class="fetaure-ancher">
                                <img src="/MOM/images/home-rugs-mom.png" class="img-fluid" alt="">
                            </a> -->
                        <div class="rugs-banner-item ">
                            <div class="img-fluid rugs-eclips d-none" style="background-image: url('/MOM/images/rugs-ellipse.png');">
                                <!-- <img src="/MOM/images/rugs-ellipse.png" class="img-fluid" alt=""> -->
                            </div>
                            <div class="rugs-home-slider ">
                                <div class="single-rugs-item ">
                                    <div class="rugs-images img-fluid">
                                        <a href="{{asset($slide -> link)}}" class="rugs-thumbnail"> <img src="{{asset($slide -> image)}}" class="img-fluid" alt=""></a>
                                    </div>
                                </div>
                            </div>
                            <div class="view-all-styles">
                                <a href="{{$slide -> link}}"> <span>VIEW All COLORS</span> <img src="/MOM/images/right-arrow-mom.svg" class="img-fluid" alt=""></a>
                            </div>
                        </div>
                    </div>
                </div>
                                @endif
                                @endforeach
                                @endif

            </div>
        </div>
    </section>
    @endif
    @if($pages -> home -> sections -> catalog -> is_active)
    <section class="catalog-2022">
        <div class="offer-colection-area">
            <div class="text-center catalog" style="background-image: url({{$pages -> home -> sections -> catalog -> catalog_img}}) !important;">
                <div class="row m-0">
                    <div class="col-md-12">
                        <div class="catalog-txt">
                            <h3>{{$pages -> home -> sections -> catalog -> caption}}</h3>
                            <h2> {{$pages -> home -> sections -> catalog -> title}}</h2>
                            <a href="{{$pages -> home -> sections -> catalog -> button_url}}" target="_blank">{{$pages -> home -> sections -> catalog -> button_text}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    @if($pages -> home -> sections -> key_categories -> is_active)
    <section class="key-categories">
        <div class="product-wrapper">
            <div class="container">
                <div class="set-lr-pad">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section-title text-center mb-20">
                                <h2 class="section-title--one section-title--center">{!!$pages -> home -> sections -> key_categories -> title!!}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="product-main-content">
                        <div class="row product-slider-active">
                            <div class="col-lg-12">
                                <div class="single-product-item text-center">
                                    <div class="products-images">
                                        <a href="{{$pages -> home -> sections -> key_categories -> image_1_url}}" class="product-thumbnail img-fluid" style="background-image: url('{{$pages -> home -> sections -> key_categories -> image_1_image}}');">
                                            <!-- <img src="{{$pages -> home -> sections -> key_categories -> image_1_image}}" class="img-fluid" alt="{{$pages -> home -> sections -> key_categories -> image_1_title}}"> -->
                                        </a>
                                    </div>
                                    <div class="product-content">
                                        <h6 class="prodect-title"><a href="{{$pages -> home -> sections -> key_categories -> image_1_url}}">{{$pages -> home -> sections -> key_categories -> image_1_title}}</a></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="single-product-item text-center">
                                    <div class="products-images">
                                        <a href="{{$pages -> home -> sections -> key_categories -> image_2_url}}" class="product-thumbnail img-fluid" style="background-image: url('{{$pages -> home -> sections -> key_categories -> image_2_image}}');">
                                            <!-- <img src="{{$pages -> home -> sections -> key_categories -> image_2_image}}" class="img-fluid" alt="{{$pages -> home -> sections -> key_categories -> image_2_title}}">  -->
                                        </a>
                                    </div>
                                    <div class="product-content">
                                        <h6 class="prodect-title"><a href="{{$pages -> home -> sections -> key_categories -> image_2_url}}">{{$pages -> home -> sections -> key_categories -> image_2_title}}</a></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="single-product-item text-center">
                                    <div class="products-images">
                                        <a href="{{$pages -> home -> sections -> key_categories -> image_3_url}}" class="product-thumbnail img-fluid" style="background-image: url('{{$pages -> home -> sections -> key_categories -> image_3_image}}');">
                                            <!-- <img src="{{$pages -> home -> sections -> key_categories -> image_3_image}}" class="img-fluid" alt="{{$pages -> home -> sections -> key_categories -> image_3_title}}">  -->
                                        </a>
                                    </div>
                                    <div class="product-content">
                                        <h6 class="prodect-title"><a href="{{$pages -> home -> sections -> key_categories -> image_3_url}}">{{$pages -> home -> sections -> key_categories -> image_3_title}}</a></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="single-product-item text-center">
                                    <div class="products-images">
                                        <a href="{{$pages -> home -> sections -> key_categories -> image_4_url}}" class="product-thumbnail img-fluid" style="background-image: url('{{$pages -> home -> sections -> key_categories -> image_4_image}}');">
                                            <!-- <img src="{{$pages -> home -> sections -> key_categories -> image_4_image}}" class="img-fluid" alt="{{$pages -> home -> sections -> key_categories -> image_4_title}}"> -->
                                        </a>
                                    </div>
                                    <div class="product-content">
                                        <h6 class="prodect-title"><a href="{{$pages -> home -> sections -> key_categories -> image_4_url}}">{{$pages -> home -> sections -> key_categories -> image_4_title}}</a></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="single-product-item text-center">
                                    <div class="products-images">
                                        <a href="{{$pages -> home -> sections -> key_categories -> image_5_url}}" class="product-thumbnail img-fluid" style="background-image: url('{{$pages -> home -> sections -> key_categories -> image_5_image}}');">
                                            <!-- <img src="{{$pages -> home -> sections -> key_categories -> image_5_image}}" class="img-fluid" alt="{{$pages -> home -> sections -> key_categories -> image_5_title}}">  -->
                                        </a>
                                    </div>
                                    <div class="product-content">
                                        <h6 class="prodect-title"><a href="{{$pages -> home -> sections -> key_categories -> image_5_url}}">{{$pages -> home -> sections -> key_categories -> image_5_title}}</a></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="single-product-item text-center">
                                    <div class="products-images">
                                        <a href="{{$pages -> home -> sections -> key_categories -> image_6_url}}" class="product-thumbnail img-fluid" style="background-image: url('{{$pages -> home -> sections -> key_categories -> image_6_image}}');">
                                            <!-- <img src="{{$pages -> home -> sections -> key_categories -> image_6_image}}" class="img-fluid" alt="{{$pages -> home -> sections -> key_categories -> image_6_title}}">  -->
                                        </a>
                                    </div>
                                    <div class="product-content">
                                        <h6 class="prodect-title"><a href="{{$pages -> home -> sections -> key_categories -> image_6_url}}">{{$pages -> home -> sections -> key_categories -> image_6_title}}</a></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    @if($pages -> home -> sections -> partnership -> is_active)
    <section class="partnership">
        <div class="container-fluid">
            <div class="partner-apply" id="partner-apply">
                <div class="position-relative wrap">
                    <div class="pt-apply-title"><span>{{$pages -> home -> sections -> partnership -> title}}</span></div>
                    <div class="pt-apply-button">
                        <a href="{{$pages -> home -> sections -> partnership -> button_url}}" class="m-0">{{$pages -> home -> sections -> partnership -> button_text}}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
</div>
@include('frontend.'.$active_theme -> theme_abrv.'.components.footer')
<a href="#" class="scroll-top" id="scroll-top"> <i class="arrow-top icon-arrow-up"></i> <i class="arrow-bottom icon-arrow-up"></i> </a>

<style>
    .slick-slide {
        height: auto !important;
        margin: 0 !important;
    }
    /* .owl-nav {display: none;} */
    .owl-dots {display: none;}
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.owl-carousel').owlCarousel({
            loop: true
            , margin: 20
            , animateIn: 'fadeIn'
            , animateOut: 'fadeOut'
            , nav: true
            , dots: true
            , navText: [
                "<div class='left-arrow cu-arrow'><img src='/MOM/images/white-left-arrow.svg'></div>"
                , "<div class='right-arrow cu-arrow'><img src='/MOM/images/white-right-arrow.svg'></div>"
            ]
            , responsiveClass: true
            , responsive: {
                0: {
                    items: 1
                    , nav: true
                    , dots: true
                }
                , 600: {
                    items: 1
                    , dots: true
                    , nav: false
                }
                , 1000: {
                    items: 1
                    , autoplay: true
                    , autoplaySpeed: 1000
                    , loop: true
                }
            }
        });
    });
    $('.product-slider-active').slick({
        dots: false
        , infinite: true
        , slidesToShow: 3
        , slidesToScroll: 1
        , autoplay: false
        , prevArrow: '<i class="icon-arrow-left arrow-prv"></i>'
        , nextArrow: '<i class="icon-arrow-right arrow-next"></i>'
        , responsive: [{
            breakpoint: 992
            , settings: {
                slidesToShow: 3
            , }
        }, {
            breakpoint: 762
            , settings: {
                slidesToShow: 2
            , }
        }, {
            breakpoint: 576
            , settings: {
                slidesToShow: 1
            , }
        }]
    });
    $('.rugs-home-slider').slick({
        dots: false
        , infinite: true
        , speed: 500
        , fade: !0
        , cssEase: 'linear'
        , slidesToShow: 1
        , slidesToScroll: 1
        , autoplay: true
        , prevArrow: '<div class="left-arrow cu-arrow"><img src="/MOM/images/left-arrow-mom.svg"></div>'
        , nextArrow: '<div class="right-arrow cu-arrow"><img src="/MOM/images/right-arrow-mom.svg"></div>'
        , responsive: [{
            breakpoint: 992
            , settings: {
                slidesToShow: 1
            , }
        }, {
            breakpoint: 762
            , settings: {
                slidesToShow: 1
            , }
        }, {
            breakpoint: 576
            , settings: {
                slidesToShow: 1
            , }
        }]
    });

</script>
@endsection

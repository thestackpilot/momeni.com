@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp

@extends('frontend.'.$active_theme -> theme_abrv.'.layouts.app')
@section('title','Home')
@section('content')
<div class="wrapper homepage">
    @include('frontend.'.$active_theme -> theme_abrv.'.components.header-home')
    <main class="main-content">
        <section class="section-top-most">
            <div class="container">
                <h1 class="section-title text-center my-5">{{$pages -> home -> sections -> our_products -> title}}</h1>
                <div class="col-md-12">
                    <ul class="owl-carousel owl-products">
                        <li class="slider-item">
                            <a href="{{$pages -> home -> sections -> our_products -> prod_1_url}}" class="d-flex flex-column text-decoration-none">
                                <figure class="overflow-hidden m-0">
                                    <img src="{{$pages -> home -> sections -> our_products -> prod_1_image}}" />
                                </figure>
                                <span class="product-lable">{{$pages -> home -> sections -> our_products -> prod_1_title}}</span>
                            </a>
                        </li>
                        <li class="slider-item">
                            <a href="{{$pages -> home -> sections -> our_products -> prod_2_url}}" class="d-flex flex-column text-decoration-none">
                                <figure class="overflow-hidden m-0">
                                    <img src="{{$pages -> home -> sections -> our_products -> prod_2_image}}" />
                                </figure>
                                <span class="product-lable">{{$pages -> home -> sections -> our_products -> prod_2_title}}</span>
                            </a>
                        </li>
                        <li class="slider-item">
                            <a href="{{$pages -> home -> sections -> our_products -> prod_3_url}}" class="d-flex flex-column text-decoration-none">
                                <figure class="overflow-hidden m-0">
                                    <img src="{{$pages -> home -> sections -> our_products -> prod_3_image}}" />
                                </figure>
                                <span class="product-lable">{{$pages -> home -> sections -> our_products -> prod_3_title}}</span>
                            </a>
                        </li>
                        <li class="slider-item">
                            <a href="{{$pages -> home -> sections -> our_products -> prod_4_url}}" class="d-flex flex-column text-decoration-none">
                                <figure class="overflow-hidden m-0">
                                    <img src="{{$pages -> home -> sections -> our_products -> prod_4_image}}" />
                                </figure>
                                <span class="product-lable">{{$pages -> home -> sections -> our_products -> prod_4_title}}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section>
            <div class="container-fluid p-0">
                <div class="d-flex align-content-center flex-md-row flex-sm-column flex-dir-col">
                    <div class="col-md-6 col-sm-12 new-additions d-flex justify-content-center py-lg-0 py-sm-5">
                        <div class="d-flex flex-column text-center addition-content justify-content-center">
                            <!-- TODO : NEEDS TO CHANGE THIS -->
                            <!-- <h2 class="section-title color-white">{{$pages -> home -> sections -> new_additions -> title}}</h2> -->
                            <h2 class="section-title color-white">Let's Get Rizzy</h2>
                            <p class="color-white mt-4 mb-4 ">{{$pages -> home -> sections -> new_additions -> caption}}</p>
                            <a href="{{$pages -> home -> sections -> new_additions -> button_url}}" class="btn btn-primary mt-0 m-auto mb-0"> {{$pages -> home -> sections -> new_additions -> button_title}} </a>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 new-additions">
                        <figure class="m-0">
                            <img src="{{$pages -> home -> sections -> new_additions -> image}}" class="img-responsive" alt="{{$pages -> home -> sections -> new_additions -> title}}" />
                        </figure>
                    </div>
                </div>
            </div>
        </section>

        <section class="collection-section">
            <div class="container">
                <div class="d-flex flex-lg-row flex-sm-column flex-dir-col">
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="card">
                            <figure class="collections position-relative m-0">
                                <a href="{{$pages -> home -> sections -> new_arrival -> image_1_url}}">
                                    <img src="{{$pages -> home -> sections -> new_arrival -> image_1_image}}" class="img-responsive" alt="{{$pages -> home -> sections -> new_arrival -> image_1_title}}" />
                                    <div class="overlay"></div>
                                    <figcaption>
                                        {{$pages -> home -> sections -> new_arrival -> image_1_title}} <span>{{$pages -> home -> sections -> new_arrival -> image_1_caption}}</span>
                                    </figcaption>
                                </a>
                            </figure>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 12 col-12 d-flex flex-wrap">
                        <div class="col-lg-6 col-sm-12 12 col-12">
                            <div class="card">
                                <figure class="collections position-relative m-0 bestsellers">
                                    <a href="{{$pages -> home -> sections -> new_arrival -> image_2_url}}">
                                        <img src="{{$pages -> home -> sections -> new_arrival -> image_2_image}}" class="img-responsive" alt="{{$pages -> home -> sections -> new_arrival -> image_2_title}}" />
                                        <div class="overlay"></div>
                                        <figcaption>
                                            <span>{{$pages -> home -> sections -> new_arrival -> image_2_caption}}
                                            </span>{{$pages -> home -> sections -> new_arrival -> image_2_title}}
                                        </figcaption>
                                    </a>
                                </figure>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 12 col-12">
                            <div class="card">
                                <figure class="collections position-relative m-0">
                                    <a href="{{$pages -> home -> sections -> new_arrival -> image_3_url}}">
                                        <img src="{{$pages -> home -> sections -> new_arrival -> image_3_image}}" class="img-responsive" alt="{{$pages -> home -> sections -> new_arrival -> image_3_title}}" />
                                        <div class="overlay"></div>
                                        <figcaption class="fig_image_top_right">
                                            {{$pages -> home -> sections -> new_arrival -> image_3_title}}
                                        </figcaption>
                                    </a>
                                </figure>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12 12 col-12">
                            <div class="card">
                                <figure class="collections position-relative m-0">
                                    <a href="{{$pages -> home -> sections -> new_arrival -> image_4_url}}">
                                        <img src="{{$pages -> home -> sections -> new_arrival -> image_4_image}}" class="img-responsive" alt="{{$pages -> home -> sections -> new_arrival -> image_4_title}}" />
                                        <div class="overlay"></div>
                                        <figcaption>
                                            {{$pages -> home -> sections -> new_arrival -> image_4_title}}
                                        </figcaption>
                                    </a>
                                </figure>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="light-grey-bg">
            <div class="container">
                <div class="d-flex flex-column text-center align-items-center">
                    <p class="mt-4 mb-4 font-crimson col-md-6 bragging-about">{{$pages -> home -> sections -> learn_more -> caption}}</p>
                    <a href="{{$pages -> home -> sections -> learn_more -> button_url}}" class="btn btn-primary mt-0 m-auto mb-0"> {{$pages -> home -> sections -> learn_more -> button_text}} </a>
                </div>
            </div>
        </section>
        <!--
            <section class="about-rizzy overflow-hidden">
                <div class="container">
                    <div class="d-flex align-items-center flex-lg-row flex-sm-column flex-dir-col">
                        <div class="col-lg-6 col-sm-12 col-sm-12">
                            <h1 class="section-heading"> {{$pages -> home -> sections -> about_rizzy -> title_top}} </h1>
                            <p class="mt-4 col-md-10 col-sm-12">{{$pages -> home -> sections -> about_rizzy -> description}}</p>
                        </div>
                        <div class="col-lg-6 col-sm-12 justify-content-center d-flex mt-lg-0 mt-sm-5">
                            <figure class="position-relative video-popover overflow-visible">

                                <iframe width="490" height="450" src="{{$pages -> home -> sections -> about_rizzy -> video}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                <span class="position-absolute blackBox-shadow"></span>
                            </figure>
                        </div>
                    </div>
                </div>
            </section>
            -->

        <section class="insta-posts-main">
            <div class="container insta-posts">
                <h1 class="section-title text-center mb-5">Follow Us</h1>
                <div class="d-flex flex-column text-center align-items-center mb-5">
                    <div class="social-icons d-flex flex-row justify-content-lg-between">
                        <a href="{{$pages -> all_pages -> sections -> footer_social_media -> facebook_url}}" class="fs-4" target="_blank"> 
                            <img src="{{asset('/RZY/images/social/facebook.svg')}}" alt="facebook" />
                        </a>
                        <a href="{{$pages -> all_pages -> sections -> footer_social_media -> insta_url}}" class="fs-4" target="_blank"> 
                            <img src="{{asset('/RZY/images/social/instagram.svg')}}" alt="instagram" />    
                        </a>
                        <a href="{{$pages -> all_pages -> sections -> footer_social_media -> twitter_url}}" class="fs-4" target="_blank"> 
                            <img src="{{asset('/RZY/images/social/twitter.svg')}}" alt="twitter" />    
                        </a>
                        <a href="{{$pages -> all_pages -> sections -> footer_social_media -> youtube_url}}" class="fs-4" target="_blank">
                            <img src="{{asset('/RZY/images/social/youtube.svg')}}" alt="youtube" />
                        </a>
                        <a href="{{$pages -> all_pages -> sections -> footer_social_media -> pinterest_url}}" class="fs-4" target="_blank">
                            <img src="{{asset('/RZY/images/social/pinterest.svg')}}" alt="pinterest" />
                        </a>
                        <a href="{{$pages -> all_pages -> sections -> footer_social_media -> linkedin_url}}" class="fs-4" target="_blank">
                            <img src="{{asset('/RZY/images/social/linkedin.svg')}}" alt="linkedin" />
                        </a>
                    </div>
                </div>
                <div class="d-flex flex-lg-row flex-sm-column flex-dir-col">

                    <div class="col-lg-5 col-sm-12 col-12 innersecleft">
                        <a href="javascript:void(0)" class="social-images">
                            <img src="{{asset('/RZY/images/social/social1.jpg')}}">
                        </a>
                    </div>
                    <div class="col-lg-7 col-sm-12 col-12 d-flex flex-wrap innersecright">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 12 col-12">
                                <a href="javascript:void(0)" class="social-images">
                                    <img src="{{asset('/RZY/images/social/social2.jpg')}}">
                                </a>
                            </div>
                            <div class="col-lg-6 col-sm-12 12 col-12">
                                <a href="javascript:void(0)" class="social-images">
                                    <img src="{{asset('/RZY/images/social/social3.png')}}">
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 12 col-12">
                                <a href="javascript:void(0)" class="social-images">
                                    <img src="{{asset('/RZY/images/social/social4.png')}}">
                                </a>
                            </div>
                            <div class="col-lg-6 col-sm-12 12 col-12">
                                <a href="javascript:void(0)" class="social-images">
                                    <img src="{{asset('/RZY/images/social/social5.png')}}">
                                </a>
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
@section('scripts')

<script>
    $(document).ready(function() {
        $('.owl-carousel').owlCarousel({
            loop: true,
            margin: 20,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 2,
                    nav: true,
                    dots: true
                },
                600: {
                    items: 3,
                    dots: true,
                    nav: false
                },
                1000: {
                    items: 4,
                    autoplay: false,
                    autoplaySpeed: 1000,
                    nav: true,
                    dots: true,
                    loop: true
                }
            }
        });

        $('.hover').hover(function() {
            $(this).addClass('flip');
        }, function() {
            $(this).removeClass('flip');
        });
    });
</script>
@endsection
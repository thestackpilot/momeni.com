@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp 

<footer>
    <div class="container">
        <div class="col-md-12 d-flex flex-wrap">
            <div class="col-md-3 col-sm-6 d-flex flex-column footer-site-logo">
                <a href="{{route('frontend.home')}}"> <img src="{{ asset($basicSettings -> logo_dark)}}" alt="Rizzy Home" /> </a>
                <div class="footer-info d-flex flex-column mt-4">
                    <address>{{ $basicSettings -> address }}</address>
                    <a href="mailto:{{ $basicSettings -> email }}">{{ $basicSettings -> email }}</a>
                    <a href="tel:{{ $basicSettings -> contact }}">{{ $basicSettings -> contact }}</a>
                    <a href="//{{ $basicSettings -> website }}" class="link">{{ $basicSettings -> website }}</a>
                </div>
            </div>
            @if ( isset($menus -> first_footer) )
            <div class="col-md-3 col-sm-6 d-flex flex-column footer-site-map">
                <h3 class="font-ropa footer-title">{{ $menus -> first_footer -> name }}</h3>
                <div class="footer-info d-flex flex-column mt-2">
                    <ul class="d-flex flex-column p-0">
                    @php
                        echo CommonController::print_menu
                        (
                            $menus -> first_footer -> metas, //meta
                            '',                             //prefix_li
                            '',                             //li
                            '',                             //postfix_li
                            '',                             //anchor_parent
                            '',                             //prefix_ul
                            '',                             //ul
                            '',                             //postfix_ul
                            4                               // 0 - Normal, 1 - smallcase, 2 - capitalcase, 3 - ucfirst, 4 - ucword                                                                
                        ); 
                    @endphp
                    </ul>
                </div>
            </div>
            @endif
            @if ( isset($menus -> second_footer) )
            <div class="col-md-3 col-sm-6 d-flex flex-column footer-site-map">
                <h3 class="font-ropa footer-title">{{ $menus -> second_footer -> name }}</h3>
                <div class="footer-info d-flex flex-column mt-2">
                    <ul class="d-flex flex-column p-0">
                    @php
                        echo CommonController::print_menu
                        (
                            $menus -> second_footer -> metas, //meta
                            '',                             //prefix_li
                            '',                             //li
                            '',                             //postfix_li
                            '',                             //anchor_parent
                            '',                             //prefix_ul
                            '',                             //ul
                            '',                             //postfix_ul
                            4                               // 0 - Normal, 1 - smallcase, 2 - capitalcase, 3 - ucfirst, 4 - ucword                                                                
                        ); 
                    @endphp
                    </ul>
                </div>
            </div>
            @endif
            @if ( isset($menus -> third_footer) )
            <div class="col-md-3 col-sm-6 d-flex flex-column footer-site-map">
                <h3 class="font-ropa footer-title">{{ $menus -> third_footer -> name }}</h3>
                <div class="footer-info d-flex flex-column mt-2">
                    <ul class="d-flex flex-column p-0">
                    @php
                        echo CommonController::print_menu
                        (
                            $menus -> third_footer -> metas, //meta
                            '',                             //prefix_li
                            '',                             //li
                            '',                             //postfix_li
                            '',                             //anchor_parent
                            '',                             //prefix_ul
                            '',                             //ul
                            '',                             //postfix_ul
                            4                               // 0 - Normal, 1 - smallcase, 2 - capitalcase, 3 - ucfirst, 4 - ucword                                                                
                        ); 
                    @endphp
                    </ul>
                </div>
            </div>
            @endif
        </div>
        <div class="footer-bottom d-flex flex-row justify-content-between align-items-center mt-2 flex-md-row flex-sm-column flex-dir-col">
            <div class="col-md-4">
                <small class="font-ropa">© 2021 {{ $basicSettings -> name }}.  All Rights Reserved.</small>
            </div>
            <div class="col-lg-4 col-md-5">
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
        </div>
    </div>
</footer>
<div class="overlay" style="display:none;"></div>

<script src="{{asset('/RZY/js/owl.carousel.min.js')}}"></script>
<script src="{{asset('/RZY/js/toastr.js')}}"></script>
<script src="{{asset('/RZY/js/js-image-zoom.js')}}"></script>
<script src="{{asset('/RZY/js/script.js')}}"></script>
<script src="{{asset('/RZY/js/main.js')}}"></script>

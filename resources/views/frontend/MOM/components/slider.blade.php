@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;
@endphp 

<div class="hero-home-slider owl-carousel js-fullheight mb-0" >
    @if(isset($sliders -> main_slider)) 
        @foreach($sliders -> main_slider -> metas as $slide)
        @if($slide -> is_active)
            <div class="slider-item home-row hp-video" style="background-image:url('/MOM/images/landing-img/furniture/1.png');">
                <div class="overlay"></div>
                <div class="container">
                    <div class="row no-gutters slider-text js-fullheight hpmt-video-text-content justify-content-center">
                        <div class="text-center">
                        <h2 class="title">{{$slide -> caption_1}}</h2>
                            <p>{{$slide -> caption_2}}</p>
                            <a href="{{$slide -> link}}" class="cta-btn">{{$slide -> link_title}}</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif  
        @endforeach
    @endif
</div>

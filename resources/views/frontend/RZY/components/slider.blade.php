@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp 

<div class="home-slider owl-carousel js-fullheight">
    @if(isset($sliders -> home_slider))
        @foreach($sliders -> home_slider -> metas as $slide)
            <div class="slider-item js-fullheight" style="background-image:url({{asset($slide -> image)}});">
                <div class="overlay"></div>
                <div class="container">
                    <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center">
                        <div class="text-center">
                            <h2 class="font-italic"> {{ $slide -> caption_1 }} </h2>
                            <h2 class="font-regular"> {{ $slide -> caption_2 }} </h2>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
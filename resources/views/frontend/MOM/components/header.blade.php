@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp
<div class="header-area header-area--default">
    <header class="header-area header_absolute header_height-90 header-sticky shadow-sm">
        <div class="container menu-container">
            <div class="row align-items-center header-inner-main">
                <!-- <div class="col-lg-2 col-md-2 col-sm-12 d-none d-md-block">-->
                <div class="col-lg-2 col-md-2 col-sm-2 d-md-block">
                    <div class="logo text-md-left">
                        <a href="/"><img src="{{asset($basicSettings -> logo_dark)}}" width="60" alt=""></a>
                    </div>
                </div>
                @include('frontend.'.$active_theme -> theme_abrv.'.components.menu')
                <div class="mobile-menu">
                    <a href="#" class="mobile-navigation-icon" id="mobile-menu-trigger"> <i class="icon-menu"></i> </a>
                </div>
            </div>
        </div>
    </header>
</div>

@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp 

<header class="d-flex flex-row flex-wrap innerPageHeader">
    <div class="container-fluid extra-padding-lr top-header-wrap d-flex page-header">
        <nav class="col-2 col-xs-5 col-sm-5 col-md-5">
	        <a href="javascript:void(0)" class="nav-left mobile-nav-icon"> <i class="bi bi-caret-down"></i> </a>
            @include('frontend.'.$active_theme -> theme_abrv.'.components.menu')
        </nav>
        <div class="col-6 col-xs-2 col-sm-2 col-md-2 text-center">
            <a href="{{route('frontend.home')}}" class="logo">
                <img src="{{ asset($basicSettings -> logo_dark) }}" alt="{{$basicSettings -> name}}" />
            </a>
        </div>
        <nav class="col-4 col-xs-5 col-sm-5 col-md-5">
            <div id="profile-parent">
                @include('frontend.'.$active_theme -> theme_abrv.'.components.profile')
            </div>
        </nav>
    </div>
    @include('frontend.'.$active_theme -> theme_abrv.'.components.breadcrumbs')
</header>

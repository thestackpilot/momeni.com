@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp


<div class="side-icons-main">
    <div class="side-icons-fixed mobile-hide">
        <div class="right-side-icons">
            <div class="searchh">
                <img src="/MOM/images/search-icon-mom.svg" id="click-search">
            </div>
            <div class="header-right-items mobile-hide">
                <span class="container-checker" id="cart-parent">
                    @include('frontend.'.$active_theme -> theme_abrv.'.components.quick-cart')
                </span>
                <span class="container-checker" id="profile-parent">
                    @include('frontend.'.$active_theme -> theme_abrv.'.components.profile')
                </span>
            </div>
        </div>
    </div>
    <div id="show-on-search" style="display: none;">
        <div id="search-custom-overlay"></div>
        <div id="custom-cross-btn">X</div>
        <div class="full-screen-serach-box_inner_wrapper d-flex align-items-center">
            <div class="header-left-search" id="search_text_container">
                <div class="header-search-box">
                    <input type="text" name="searchText" id="popup-search" class="search-field search-input" id="formGroupExampleInput" placeholder="Search" value="">
                    <button id="serach-popup-btn-box" class="search-button submit-btn search_text_button search-icon">
                        <img src="/MOM/images/white-search-icon-mom.svg">
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="cartoverlay"> </div>
</div>

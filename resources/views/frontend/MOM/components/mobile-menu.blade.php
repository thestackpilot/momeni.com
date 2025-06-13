@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp
<style>
#search-custom-overlay{

    position: fixed;
    top:100px;
    right:0;
   
    
  
}
</style>
<div class="mobile-menu-overlay" id="mobile-menu-overlay">
        <div class="mobile-menu-overlay__inner">
            <div class="mobile-menu-close-box text-right"> <span class="mobile-navigation-close-icon" id="mobile-menu-close-trigger"> <i class="icon-cross2"></i></span> </div>
            <div class="mobile-menu-overlay__body">
                <nav class="offcanvas-navigation">
                    <ul>
                    <li class="has-children mb-icons ">
                             <span class="container-checker" id="searchh">
                             <div class="searchh">
                                <img src="/MOM/images/search-icon-mom.svg" id="cli">
                                <div id="show_search" style="display: none; z-index:999;">
                                <div id="search-custom-overlay" style="z-index:999; height:90px">
                                <div id="closeBtn" style="margin-left:95%">X</div>
                                    <div class="full-screen-serach-box_inner_wrapper d-flex align-items-center justify-content-center">
                                        
                                            <div class="form-group" style=";" >
                                                <input type="text" id="inputText"  name="s" placeholder="Search.....">
                                                <button type="button" id="searchBtn" class="search-button submit-btn"><img src="/MOM/images/white-search-icon-mom.svg"></button>
                                            </div>
                                        
                                    </div>
                                </div>
                                </div>
                            </div>
                            </span>
                            <span class="container-checker" id="cart-parent">
                                <a href="{{route('frontend.checkout')}}">
                                    <!-- <i class="headericons icon-cart quickCart-opener position-relative">
                                        @auth()
                                        <span class="badge badge-pill badge-primary position-absolute cartCount">{{$cart -> cart_count}}</span>
                                        @endauth
                                    </i> -->
                                    <div class="headericons quickCart-opener position-relative">
                                        <img src="/MOM/images/cart-icon-mom.svg">
                                        @auth()
                                        <span class="badge badge-pill badge-primary position-absolute cartCount">{{$cart -> cart_count}}</span>
                                        @endauth
                                    </div>
                                </a>
                            </span>
                            <span class="container-checker" id="profile-parent">
                                <a href="{{route('dashboard')}}">
                                    <!-- <i class="headericons icon-user"></i> -->
                                    <div class="headericons quickProfile-opener">
                                        <img src="/MOM/images/myaccount-icon-mom.svg">
                                    </div>
                                </a>
                            </span>
                        </li>
                        <li class="has-children">
                            <a class="nav-link" href="#">
                                {{$pages -> all_pages -> sections -> main_top_menu -> menu_1_caption}}
                            </a>
                            <ul class="sub-menu">
                                {{-- <li> <a href="{{$pages -> all_pages -> sections -> main_top_menu -> menu_1_url}}">VIEW ALL</a></li>
                                <li>
                                    <a href="">Collection</a>
                                </li> --}}
                                @if(isset($menus -> rug_header))
                                @foreach($menus -> rug_header -> metas as $meta)
                                    <li>
                                        <a href="{{ $meta -> meta_url }}">{{ $meta -> meta_title }}</a>
                                    </li>
                                @endforeach
                                @endif
                            </ul>
                        </li>
                        <li class="has-children">
                            <a class="nav-link" href="{{$pages -> all_pages -> sections -> main_top_menu -> menu_2_url}}">
                                {{$pages -> all_pages -> sections -> main_top_menu -> menu_2_caption}}
                            </a>

                        </li>
                        <li class="has-children"> <a class="nav-link" href="{{$pages -> all_pages -> sections -> main_top_menu -> menu_3_url}}">{{$pages -> all_pages -> sections -> main_top_menu -> menu_3_caption}}</a>

                        </li>

                        @if(Auth::user() && (Auth::user()->is_sale_rep == 1 || Auth::user()->broadloom_user == 1))
                        <li class="has-children"> <a class="nav-link" href="{{$pages -> all_pages -> sections -> main_top_menu -> menu_6_url}}">{{$pages -> all_pages -> sections -> main_top_menu -> menu_6_caption}}</a>

                        </li>
                        @endif

                        <li class="has-children"> <a class="nav-link" href="{{$pages -> all_pages -> sections -> main_top_menu -> menu_4_url}}">{{$pages -> all_pages -> sections -> main_top_menu -> menu_4_caption}}</a>

                        </li>
                        <li class="has-children"> <a class="nav-link" href="{{$pages -> all_pages -> sections -> main_top_menu -> menu_5_url}}">{{$pages -> all_pages -> sections -> main_top_menu -> menu_5_caption}}</a>

                        </li>
                        <li class="has-children">
                            @if(!Auth::user())
                            <a class="nav-link" href="{{route('auth.register')}}"> Become a Partner</a>
                            @endif
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>


<script>
$("#cli").click(function(){
     $("#show_search").attr("style", "display: block");
    console.log("show something");
    
});
$("#closeBtn").click(function () {
    $("#show_search").attr("style", "display: none");
    console.log("Search hidden");
});

    document.getElementById("searchBtn").addEventListener("click", function () {
       const inputValue = document.getElementById("inputText").value;
console.log(inputValue);
        if (!inputValue) {
            alert("Please enter a search term.");
            return;
        }
        const encoded = btoa(inputValue); // base64 encode
        const url = `/search/${encoded}`; // match your Laravel route
        window.location.href = url; // redirect
    });
const innerElement = document.querySelector('.mobile-menu-overlay__inner');
const overlayElement = document.getElementById('search-custom-overlay');

if (innerElement && overlayElement) {
  const width = innerElement.offsetWidth;  // get width in px
  overlayElement.style.width = width + 'px !importent';  // set width with unit
 
}
</script>



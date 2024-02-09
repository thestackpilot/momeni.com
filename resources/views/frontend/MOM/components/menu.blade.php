@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp 

<div class="col-lg-8 col-md-5 header-menu text-left">
    <ul class="menu">
        <li class="parant"> 
            @if(isset($menus -> rug_header))
            <a href="{{$pages -> all_pages -> sections -> main_top_menu -> menu_1_url}}" class="main-item">{{$pages -> all_pages -> sections -> main_top_menu -> menu_1_caption}} <i class="icon-chevron-down"></i></a>
            <div class="drop-down-item">
                <div class="container">
                        <div class="sub-menu-item">
                           
                            @foreach($menus -> rug_header -> metas as $meta)
                                <a href="{{ $meta -> meta_url }}">{{ $meta -> meta_title }}</a>
                            @endforeach
                           
                        </div>
                </div>
            </div>
            @else
            <a href="{{$pages -> all_pages -> sections -> main_top_menu -> menu_1_url}}" class="main-item">{{$pages -> all_pages -> sections -> main_top_menu -> menu_1_caption}}</a>
            @endif
        </li>
        @if($pages -> all_pages -> sections -> main_top_menu -> menu_2_caption !== null)
        <li class="parant"> <a href="{{$pages -> all_pages -> sections -> main_top_menu -> menu_2_url}}" class="main-item">{{$pages -> all_pages -> sections -> main_top_menu -> menu_2_caption}}</a>
        </li>
        @endif
      
        @if($pages -> all_pages -> sections -> main_top_menu -> menu_3_caption !== null)
        <li class="parant"> <a href="{{$pages -> all_pages -> sections -> main_top_menu -> menu_3_url}}" class="main-item">{{$pages -> all_pages -> sections -> main_top_menu -> menu_3_caption}}</a>

        </li>
        @endif

        @if($pages -> all_pages -> sections -> main_top_menu -> menu_4_caption !== null)
        <li class="parant"> <a href="{{$pages -> all_pages -> sections -> main_top_menu -> menu_4_url}}" class="main-item">{{$pages -> all_pages -> sections -> main_top_menu -> menu_4_caption}}</a>

        </li>
        @endif
        @if($pages -> all_pages -> sections -> main_top_menu -> menu_5_caption !== null)
        <li class="parant"> <a href="{{$pages -> all_pages -> sections -> main_top_menu -> menu_5_url}}" class="main-item">{{$pages -> all_pages -> sections -> main_top_menu -> menu_5_caption}}</a>

        </li>
        @endif
        <li class="parant d-none"> 
            @if(!Auth::user())
            <span> <a href="{{route('auth.register')}}" class="main-item"> Become a Partner</a></span>
            @endif
        </li>
        <li class="parant"> 
            @if(!Auth::user())
            <span> <a href="{{route('auth.login')}}" class="main-item"> Login</a></span>
            @endif
        </li>
    </ul>
</div>

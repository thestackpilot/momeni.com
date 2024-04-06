@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp 

<ul class="navbar-nav flex-row">
    @if ( isset($menus -> mega_header) )
    <li class="nav-item position-static">
        <a href="javascript:void(0)" class="show-megamenu">{{$menus -> mega_header -> name}}</a>
        <div class="mega-menu position-absolute balanced-megamenu">
            <ul class="d-flex right-nav flex-row justify-content-xl-between justify-content-lg-between justify-content-md-between flex-wrap p-0">
                @foreach($menus -> mega_header -> metas as $meta)
                <li class="nav-item">
                    <a href="{{$meta -> meta_url}}" class="mega_menu_image">
                        <figure class="overflow-hidden m-0">
                            <img src="{{$meta -> meta_image}}" onerror="this.onerror=null; this.src='/images/placeholder.jpg'"/>
                        </figure>
                    </a>
                    <a href="{{$meta -> meta_url}}" class="mega_menu_title">
                        <span class="product-lable">{{ $meta -> meta_title }}</span>
                    </a>
                </li>
                @endforeach
                @if ($active_theme_json -> menus -> mega_header -> view_all)
                <li class="nav-item view-all">
                    <a href="{{route('frontend.'.$active_theme_json -> menus -> mega_header -> view_all_type)}}" class="d-flex flex-row">
                        <span class="product-lable font-weight-bold font-ropa">View All</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </li>
    @endif
    @php
        if ( isset($menus -> main_header_left) )
        {
            echo CommonController::print_menu
            (
                $menus -> main_header_left -> metas,                                     //meta
                '',                                                                 //prefix_li
                'class="nav-item position-static catalog-nav-menu"',                                                 //li
                '',                                                                 //postfix_li
                'dropdown-toggle',                                                  //anchor_parent
                '',                                                                 //prefix_ul
                'class="dropdown-submenuu-home nav-item" style="display: none"',    //ul
                '',                                                                 //postfix_ul
                2                                                                   // 0 - Normal, 1 - smallcase, 2 - capitalcase, 3 - ucfirst, 3 - ucword                                                                
            ); 
        }
    @endphp
    <div class="mobile-nav-links-btm">
        @php
            if ( isset($menus -> main_header_right) )
            {
                echo CommonController::print_menu
                (
                    $menus -> main_header_right -> metas,                                     //meta
                    '',                                                                 //prefix_li
                    'class="nav-item position-static catalog-nav-menu"',                                                 //li
                    '',                                                                 //postfix_li
                    'dropdown-toggle',                                                  //anchor_parent
                    '',                                                                 //prefix_ul
                    'class="dropdown-submenuu-home nav-item" style="display: none"',    //ul
                    '',                                                                 //postfix_ul
                    2                                                                   // 0 - Normal, 1 - smallcase, 2 - capitalcase, 3 - ucfirst, 3 - ucword                                                                
                ); 
            }
        @endphp
    </div>
    
</ul>

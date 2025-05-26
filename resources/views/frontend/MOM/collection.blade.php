@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;


@endphp
<style>
    #fixedimg {
    min-width:25px !important;
    height: 200px !important;
    max-width: none !important;
    
}

</style>
@extends('frontend.'.$active_theme -> theme_abrv.'.layouts.app')
@section('title',$main_collection['Description'] .' '. array_key_first($collections) .' Collections')
@section('content')
<div class="wrapper with-banner">
    @include('frontend.'.$active_theme -> theme_abrv.'.components.header')
    <main class="main-content">
        {{-- Header div --}}
        {{-- <div class="breadcrumb-area">
            <div class="container">
                <div class="row breadcrumb_box  align-items-center">
                    <div class="col-lg-3 col-md-0 col-sm-0"></div>
                    <div class="col-lg-9 col-md-12 col-sm-12 text-center text-sm-left">
                       
                        @if(isset($pageData['image']) && $pageData['image']!=="" )
                        
                            <img id="fixedimg" class="d-block mx-auto mb-2"  src="{{ asset('images/'. $pageData['image']) }}" alt="Page Logo" >

                        @endif
                     
                    </div>
                                        <div class="col-lg-3 col-md-0 col-sm-0"></div>

                    <div class="col-lg-9 col-md-12 col-sm-12 text-center text-sm-left" id="collection_heading">
                        <!-- <h2 class="breadcrumb-title text-center ">{{$main_collection['Description'] .' - '. array_key_first($collections)}}</h2> -->
                        @if (request()->segment(2) == "BroadLoom")
                            <h2 class="breadcrumb-title text-center section-title--center ">Broadloom</h2>
                        @else
                        @if(isset($pageData['title']) && $pageData['title']!=="" )
                        
                            <h2 class="breadcrumb-title text-center">{{$pageData['title']}}</h2>
                        
                        @else

                            <h2 class="breadcrumb-title text-center section-title--center ">{{isset($sub_category) ? $sub_category : ((strcmp('RUGS & CARPETS', strtoupper($main_collection['Description'])) === 0) ? 'RUGS' : $main_collection['Description'])}}</h2>
                        
                         @endif
                            @endif
                    </div>
                    <div class="col-lg-3 col-md-0 col-sm-0"></div>

                    <div class="col-lg-9 col-md-12 col-sm-12 text-center text-sm-left">
                       
                        @if(isset($pageData['description']) && $pageData['description']!=="" )
                        
                            <p class="text-center " style="font-size:20px">{{$pageData['description']}}</p>
                        
                       
                        @endif
                     
                    </div>
                </div>
            </div>
        </div> --}}
        <section class="collection-section">
                  
            <div class="" style="margin-top: 0px;">
                <div class="container">
                        <div class="d-flex justify-content-lg-between coll-pg product-wrapper collection-prdt-wrapper" id="sub_collections_wrapper">
                            <div class="col-md-3 d-flex flex-column sidebar-main" style="margin-top: 130px;">
                                @include('frontend.'.$active_theme -> theme_abrv.'.components.filters')
                            </div>

                            <div class="col-md-9 h-100 col-sm-12 col-12 d-flex flex-row justify-content-left three-col product-listing d-flex flex-wrap my-5"  id="sub_collections_wrapper">
<div class="container" style="display: inline-block;margin:auto;" >
                    <div>
                         @if(isset($pageData['image']) && $pageData['image']!=="" )
                            
                                <img id="fixedimg" class="d-block mx-auto mb-2"  src="{{ asset('images/'. $pageData['image']) }}" >

                            @endif
                    </div>
                      
                        <div>
                            @if (request()->segment(2) == "BroadLoom")
                            <h2 class="breadcrumb-title text-center section-title--center ">Broadloom</h2>
                        @else
                        @if(isset($pageData['title']) && $pageData['title']!=="" )
                        
                            <h2 class="breadcrumb-title text-center">{{$pageData['title']}}</h2>
                        
                        @else

                            <h2 class="breadcrumb-title text-center section-title--center ">{{isset($sub_category) ? $sub_category : ((strcmp('RUGS & CARPETS', strtoupper($main_collection['Description'])) === 0) ? 'RUGS' : $main_collection['Description'])}}</h2>
                        
                         @endif
                            @endif
                        </div>
                         <div>
                        @if(isset($pageData['description']) && $pageData['description']!=="" )
                        
                            <p class="text-center " style="font-size:20px">{{$pageData['description']}}</p>
                        
                       
                        @endif
                        </div>
                     
</div>
                                <div class="cu-top-filters col-12 d-flex justify-content-between align-items-center">
                                    <div>
                                        <ul>
                                        @if($main_collection['MainCollectionID'] == 'Rugs')
                                        <li>
                                                <input class="form-check-input"
                                                type="checkbox"
                                                name="discontinued"
                                                id="flexCheckChecked-discontinued" >

                                            <label class="form-check-label" for="flexCheckChecked-discontinued"> Show Discontinued {{ $main_collection['Description'] }}</label>
                                           
                                           </li>
                                        @endif

                                        </ul>

                                    </div>
                                    <div class="d-flex align-items-center justify-content-start">

                                        @if(($filters['Filters_Count']) > 0)
                                            @foreach($filters['Filters'] as $filter)
                                            @if(strtolower($filter['FilterID']) == 'sort')
                                            <div id="filter-id-{{ strtolower($filter['FilterID'])}}">
                                                <select class="sort-filter pull-left mb-3" name={{ strtolower($filter['FilterID'])}}>
                                                    <option value="0"> Sort by</option>
                                                    @foreach($filter['Values'] as $value)
                                                    <option value="{{$value['value']}}" {{$value['checked'] ? 'selected' : ''}} >{{$value['value']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            @endif
                                            @endforeach
                                        @endif
                                        {{-- <div class="clear-all">
                                            <button class="btn btn-sm">Reset Filters</button>
                                        </div> --}}
                                    </div>
                                </div>
                                @if(count($collections))
                                @foreach($collections[array_key_first($collections)] as $k => $collection)
                                <div class="col-md-4">
                                    <div id="myCarousel-{{$k}}" class="collection-carousel carousel slide">
                                        <div class="carousel-inner slider-for">
                                            <div class="active item slide--1" data-slide="-1">
                                                <a href="{{$collection['LinkUrl']}}">
                                                    @php
                                                    $img_check = strpos($collection['ImageName'] , 'storage') === 0 ? true : false;
                                                    if($img_check){
                                                        $url = url('/') . "/" . $collection['ImageName'];
                                                    }else{
                                                        $url = CommonController::getApiFullImage($collection['ImageName']);
                                                    }
                                                    @endphp
                                                    <div class="single-img" style="background-image: url('{{$url}}'), url('{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}')" class="img-responsive"></div>
                                                </a>
                                            </div>
                                            {{-- @foreach($collection['DesignsList'] as $k => $thumb_design)
                                            <div class="item slide-{{$k}}" data-slide="{{$k}}">
                                                <a href="{{$collection['LinkUrl']}}">
                                                    <div class="single-img" style="background-image: url('{{CommonController::getApiFullImage($thumb_design['ImageName'])}}'), url('{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}')" class="img-responsive"></div>
                                                </a>
                                            </div>
                                            @endforeach --}}
                                        </div>
                                        {{-- <ul class="list-inline slider-nav owl-carousel owl-theme owl-1">
                                            @php
                                            $i = 0;
                                            @endphp
                                            @foreach($collection['DesignsList'] as $thumb_design)
                                            <li class="item">
                                                <a data-href="{{$collection['LinkUrl']}}" data-slide="{{$i}}" id="carousel-selector-{{$k}}-{{$i++}}">
                                                    <div class="tab-img" style="background-image:url('{{CommonController::getApiFullImage($thumb_design['ImageName'])}}'); background-size: auto 100%;"></div>
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul> --}}
                                        {{-- <a href="javascript:void(0);" class="remaining_photos"> +8 </a> --}}
                                    </div>
                                    <div class="product-content">
                                        <h6 class="prodect-title">
                                            <a href="{{$collection['LinkUrl']}}" title="{{$collection['Description']}}">{!! $collection['Description'] !!}</a>
                                        </h6>
                                        {{-- <a class="collection-card" href="{{$collection['LinkUrl']}}" title="{{$collection['Description']}}"> {{count($collection['DesignsList'])}} Designs </a> --}}
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <h1 class="section-title text-left mb-md-5 font-ropa col-sm-6 col-12">There is no data to display</h1>
                                @endif
                            </div>
                        </div>
                </div>
            </div>
        </section> 
        <div class="pageLoader d-none" id="pageLoader">
            <div id="loading_msg" class="d-flex flex-column text-center">
                <div class="spinner-border" role="status" style="margin: 0 auto;">
                    <span class="sr-only" style="opacity:0;">Loading...</span>
                </div>
                <p class="loadinMsg">Loading...</p>
            </div>
        </div>
    </main>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.carousel').carousel({interval: false,});
            $("[id^=carousel-selector-]").hover(function()
            {
                /*
                var id_selector = $(this).attr("id");
                console.log(`id_selector: ${id_selector}`);
                var id = id_selector.substr(id_selector.length - 1);
                console.log(`id [BEFORE] : ${id}`);
                id = parseInt(id);
                console.log(`id [AFTER] : ${(id - 1)}`);
                console.log(`$(this).closest('.collection-carousel').length : ${$(this).closest('.collection-carousel').length}`);
                $(this).closest('.collection-carousel').carousel(id - 1);
                $("[id^=carousel-selector-]").removeClass("selected");
                $(this).addClass("selected");
                */

                var _parent = $(this).closest('.collection-carousel');
                var slide_to_show = parseInt($(this).attr('data-slide'));
                var current_slide = parseInt($('.item.active', _parent).attr('data-slide'));
                if (slide_to_show != current_slide ) {
                    // console.log(`${current_slide} > ${slide_to_show} : ${current_slide > slide_to_show}`);
                    // console.log(`CURRENT: .item.slide-${current_slide}`);
                    // console.log(`TO SHOW: .item.slide-${slide_to_show}`);
                    if ( current_slide > slide_to_show ) {
                        $(`.item.slide-${current_slide}`, _parent).addClass( 'right' );
                        setTimeout(function() {
                            $(`.item.slide-${slide_to_show}`, _parent).addClass('active').addClass( 'left' );
                            setTimeout(function() {
                            $(`.item.slide-${slide_to_show}`, _parent).removeClass('left').removeClass('right');
                                setTimeout(function() {
                                    $(`.item.slide-${current_slide}`, _parent).removeClass('left').removeClass('right').removeClass('active');
                                }, 200);
                            }, 200);
                        }, 300);
                    } else {
                        $(`.item.slide-${slide_to_show}`, _parent).addClass('active').addClass( 'right' );
                        $(`.item.slide-${current_slide}`, _parent).addClass( 'left' );
                        setTimeout(function() {
                            $(`.item.slide-${slide_to_show}`, _parent).removeClass('left').removeClass('right');
                            setTimeout(function() {
                                $(`.item.slide-${current_slide}`, _parent).removeClass('left').removeClass('right').removeClass('active');
                            }, 200);
                        }, 200);
                    }
                }
            });
            $(".owl-1").owlCarousel({
                loop: false,
                margin: 10,
                nav: true,
                pagination: false,
                dots: false,
                responsive: {
                    0: {
                        items: 4
                    },
                    600: {
                        items: 4
                    },
                    1000: {
                        items: 4
                    }
                }
            });
        });
    </script>
    <style>
        .owl-prev {
            width: 15px;
            height: 100px;
            position: absolute;
            top: 28%;
            margin-left: -20px;
            display: block !important;
            border: 0px solid black;
        }

        .owl-next {
            width: 15px;
            height: 100px;
            position: absolute;
            top: 28%;
            right: -3px;
            display: block !important;
            border: 0px solid black;
        }

        .owl-nav button {
            background: #fff !important;
            height: 25px;
            width: 25px;
            border-radius: 50% !important;
        }

        /* .owl-nav button span {
            font-size: 33px;
            position: relative;
            top: -21px;
        } */
        .owl-nav button span {
            font-size: 33px;
            position: absolute;
            transform: translateY(-50%);
            top: 50%;
            line-height: 20px;
            height: 100%;
            margin: 0 auto;
            display: block;
            float: none;
            width: 100%;
        }

        .owl-theme .owl-nav [class*=owl-]:hover {
            color: #000 !important;
        }
        .owl-carousel .owl-nav button.owl-next, .owl-carousel .owl-nav button.owl-prev, .owl-carousel button.owl-dot {
            width: 30px;
            height: 30px;
            text-align: center;
        }
    </style>
    @include('frontend.'.$active_theme -> theme_abrv.'.components.footer')
</div>
@endsection

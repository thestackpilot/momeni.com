@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Request;

$is_discontinued = false;
if(isset($default_filter) && $default_filter && isset(json_decode($default_filter, 1)['Filters'])) {
foreach(json_decode($default_filter, 1)['Filters'] as $filter) {
	foreach($filter['Values'] as $value) {
		if ($filter['FilterID'] === 'Discontinued') $is_discontinued = $value;

	}
}
}
@endphp
@extends('frontend.'.$active_theme -> theme_abrv.'.layouts.app')
@section('title',$main_collection['Description'] .' '. array_key_first($collections) .' Collections')
@section('content')

<div class="wrapper with-banner">
    @include('frontend.'.$active_theme -> theme_abrv.'.components.header')
    <main class="main-content">
        <div class="breadcrumb-area">
            <div class="container">
                <div class="row breadcrumb_box  align-items-center">
                    @if($with_title)
                    <div class="col-lg-12 col-md-12 col-sm-12 text-center text-sm-left" id="collection_heading">
                        <h2 class="breadcrumb-title text-center ">
                            @if ( isset($custom_title_descripton) && isset($custom_title_descripton['title']) && $custom_title_descripton['title'] )
                            {!! $custom_title_descripton['title'] !!}
                            @else
                            {!! isset($collections[array_key_first($collections)][0]) ? $collections[array_key_first($collections)][0]['Title'] : $main_collection['Description'] !!}
                           {!! isset($collections['Designs'][0]['CollectionID'])? $collections['Designs'][0]['CollectionID']:$main_collection['Description']!!}
                            @endif
                        </h2>
                    </div>
                    <div class="col-md-12">
                        <p class="collection-normal__description">
                            @if ( isset($custom_title_descripton) && isset($custom_title_descripton['description']) && $custom_title_descripton['description'] )
                            {!! $custom_title_descripton['description'] !!}
                            @else
                            {!! isset($collections[array_key_first($collections)][0]) ? $collections[array_key_first($collections)][0]['TitleDescription'] : ''!!}
                            @endif
                        </p>
                        <hr />
                    </div>
                    @else
                    <div class="col-lg-12 col-md-12 col-sm-12 text-center text-sm-left" id="collection_heading">
                        <h2 class="breadcrumb-title text-center ">{{ isset($sub_category) && $sub_category ? $sub_category : (isset($collections[array_key_first($collections)][0]) ? $collections[array_key_first($collections)][0]['Title'] : $main_collection['Description']) }}</h2>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <section class="collection-section pt-4">
            <div class="container">
                <div class="d-flex justify-content-lg-between coll-pg product-wrapper collection-prdt-wrapper align-items-start" id="sub_collections_wrapper">
                    <div class="col-md-4 d-flex flex-column sidebar-main">
                        @include('frontend.'.$active_theme -> theme_abrv.'.components.filters')
                    </div>
                    <div class="col-md-8 col-sm-12 col-12 d-flex flex-row justify-content-left three-col product-listing d-flex flex-wrap mb-5">
                        <div class="cu-top-filters col-12 d-flex justify-content-between align-items-center">
                            <div>

                                <ul>
                                    @if($main_collection['MainCollectionID'] == 'Rugs')
                                   <li>
                                            <input class="form-check-input"
                                            type="checkbox"
                                            name="discontinued"
                                            id="flexCheckChecked-discontinued"
                                            {{ isset($is_discontinued) && $is_discontinued ? 'checked' : '' }}
                                            >

                                        <label class="form-check-label" for="flexCheckChecked-discontinued"> Show Discontinued {{ $main_collection['Description'] }}</label>
                                       </li>
                                    </ul>
                                @endif

                            </div>
                            <div class="d-flex align-items-center justify-content-start">
                                <div>
                                    {{-- <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input" type="checkbox" name="ShowRugWithImage" value="Show Rugs With Images" id="ShowRugWithImage">
                                        <label class="form-check-label" for="ShowRugWithImage">Show Rugs With Images</label>
                                    </div> --}}
                                </div>

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
                        @foreach($collections[array_key_first($collections)] as $collection)
                        <div class="col-md-4 grid-item" {{$with_title ? 'style="padding-right: 10px; padding-left: 10px;"' : ''}}>
                            @if(!$with_title)
                            <div class="carousel slide">
                                @endif
                                <div class="slider-for justify-content-center {{ $with_title ? 'rug-callection' : '' }}">
                                    <a href="{{$collection['LinkUrl']}}?colorId={{$collection['ColorDescription']}}">
                                        @if($with_title)
                                        <img class="single-img" src="{{CommonController::getApiFullImage($collection['ImageName'])}}" class="img-responsive" onerror="this.onerror=null; this.src='{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'" />
                                        @else
                                        {{-- <div style="background-image: url('{{CommonController::getApiFullImage($collection['ImageName'])}}'), url({{url('/').ConstantsController::IMAGE_PLACEHOLDER}});" class="single-img"> </div> --}}
                                        <div class="position-relative overflow-hidden">
                                            <!-- Image Section -->
                                            <div
                                              style="background-image: url('{{CommonController::getApiFullImage($collection['ImageName'])}}'), url({{url('/').ConstantsController::IMAGE_PLACEHOLDER}}); height: 250px; background-size: cover; background-position: center;"
                                              class="w-100">
                                            </div>
                                            @php
                                                $fullUrl = Request::url();
                                                $segments = Request::segments(); // Get all URL segments
                                                $designValue = $segments[array_search('designs', $segments) + 1]; // Get the value after 'designs'
                                            @endphp
                                            <!-- Overlay Section -->
                                            @if ($designValue == "BroadLoom")
                                            <div class="position-absolute bottom-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center overlay">
                                                <span class="text-white fs-4 fw-bold">{{$collection['CollectionID']}}</span>
                                            </div>
                                            @endif
                                          </div>


                                        @endif
                                    </a>
                                </div>
                                <div class="product-content {{ !$with_title ? 'mt-0' : '' }}">
                                    @if($with_title && isset($collection['ColorDescription']))
                                    <h6 class="prodect-title test">
                                        <a href="{{$collection['LinkUrl']}}" title="{{$collection['ColorDescription']}}"><b style="letter-spacing: 4px">{{$collection['ColorDescription']}}</b></a>
                                    </h6>
                                    @endif

                                    <h6 class="prodect-title">
                                        <a href="{{$collection['LinkUrl']}}" title="{{$collection['Description']}}">{{$collection['Description']}} </br> {{$collection['SizeID']}} </a>
                                    </h6>
                                </div>
                                @if(!$with_title)
                            </div>
                            @endif
                        </div>
                        @endforeach
                        @else
                        <h1 class="section-title text-left mb-md-5 font-ropa col-sm-6 col-12">There is no data to display</h1>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>
    @include('frontend.'.$active_theme -> theme_abrv.'.components.footer')
</div>
<div class="pageLoader d-none" id="pageLoader">
    <div id="loading_msg" class="d-flex flex-column text-center">
        <div class="spinner-border" role="status" style="margin: 0 auto;">
            <span class="sr-only" style="opacity:0;">Loading...</span>
        </div>
        <p class="loadinMsg">Loading...</p>
    </div>
</div>
@endsection
@section('scripts')
@if(!$with_title)
<style>
    .slider-for .single-img {
        max-height: 320px;
    }

    .overlay {
  background: rgba(0, 0, 0, 0.5); /* Light black background */
  transform: translateY(100%); /* Initially positioned outside the image */
  transition: transform 0.5s ease-in-out; /* Smooth sliding animation */
}

.position-relative:hover .overlay {
  transform: translateY(0); /* Slide the overlay into view */
}


</style>
@endif
<script>
    $(document).ready(function() {
        var page = 2;
        var currentscrollHeight = 0;
        var currentURL = window.location.href;
        $(window).scroll(function() {
            if (currentURL != window.location.href) {
                currentURL = window.location.href;
                page = 2;
            }

            if (page < 0 || typeof $('#sub_collections_wrapper .product-listing .grid-item').length === 'undefined' || $('#sub_collections_wrapper .product-listing .grid-item').length < 1) return;
            try {
                const scrollHeight = $(document).height();
                const scrollPos = Math.floor($(window).height() + $(window).scrollTop());
                const isBottom = (scrollHeight - $('.footer-area-wrapper').height()) < scrollPos;
                if (isBottom && currentscrollHeight < scrollHeight && $('.product-listing .grid-item').length > 29) {
                    $('.pageLoader').removeClass('d-none');
                    var pagingURL = window.location.href;
                    if (`{{$with_title}}` == 1)
                        pagingURL = `${(window.location.href).slice(0, window.location.href.lastIndexOf('/'))}`;
                    else
                        pagingURL = window.location.href;

                    $.post(`${pagingURL}/${page}`, {
                        _token: '{{csrf_token()}}'
                    }, function(response) {
                        console.log('response: ', response);
                        if (response.success && response.data.Designs.length) {
                            response.data.Designs.forEach((design) => {
                                $(`
                                    <div class="col-md-4 grid-item">
                                        @if(!$with_title)
                                        <div class="carousel slide">
                                        @endif
                                        <div class="slider-for">
                                            <a href="${design.LinkUrl}?colorId=${design.ColorDescription}">
                                                @if($with_title)
                                                <img class="single-img" src="${design.ImageUrl}" class="img-responsive" onerror="this.onerror=null; this.src='{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'" />
                                                @else
                                                <div style="background-image: url('${design.ImageUrl}')" class="single-img"> </div>
                                                @endif
                                            </a>
                                        </div>
                                        <div class="product-content">
                                            <h6 class="prodect-title">
                                                <a href="${design.LinkUrl}" title="${design.Description}">${design.Description} </br> ${design.SizeID}</a>
                                            </h6>
                                        </div>
                                        @if(!$with_title)
                                        </div>
                                        @endif
                                    </div>
                                `).appendTo($('.product-listing'));
                            });
                            page++;
                        } else {
                            page = -1;
                        }
                        $('.pageLoader').addClass('d-none');
                    });
                    currentscrollHeight = scrollHeight;
                }
            } catch (e) {
                $('.pageLoader').addClass('d-none');
            }
        });
    });
</script>
@endsection

@php
    // $active_theme object is available containing the theme developer json loaded.
    // This is for the theme developers who want to load further view assets

    use App\Http\Controllers\ConstantsController;
    use App\Http\Controllers\CommonController;
use App\Models\Cart;

    //$count = (new Cart())->get_cart_count();
    $count = (new Cart())->get_cart_count_new_item_page();
    $collectionCount = $count['total'];
    $dont_show = [];
    if (Auth::check() && strcmp(ConstantsController::USER_ROLES['staff'], Auth::user()->role) === 0) {
        if (!in_array('show-price', $permission)) {
            $dont_show[] = '.base_price';
            $dont_show[] = '.PAChart-Price';
        }
        if (!in_array('allow-checkout', $permission)) {
            $dont_show[] = '#qty-main';
            $dont_show[] = '.PAChart-Quantity';
            $dont_show[] = '#add_to_cart';
            $dont_show[] = '#get_quote_btn';
            $dont_show[] = '#chart_add_to_cart';
        }
    }
    $items_images = [];
    foreach ($items['Items'] as $item) {
        if (isset($item['ImageNameArray']) && $item['ImageNameArray']) {
            foreach ($item['ImageNameArray'] as $image) {
                if (!in_array($image, $items_images)) {
                    $items_images[] =  $image;
                }
            }
        }
    }

    $size_heading = isset($main_collection['Description']) && strtolower($main_collection['Description']) == 'rugs' ? 'Approximate Size' : 'Size';

    // backorder and qty when stock will be availe to separate the data from array of date and qty
    // by asad
    function formatETA($eta)
    {
        preg_match('/(\d{2}-\d{2}-\d{4})\((\d+)\)/', $eta, $matches);
        return ['date' => $matches[1], 'quantity' => $matches[2]];
    }
        // print_r("<pre>");
        // print_r($items['ItemsETA']);
@endphp

{{-- @dump($item) --}}
@section('title', 'Item Detail Page')
@extends('frontend.' . $active_theme->theme_abrv . '.layouts.app')

<style>
    .carousel-item {
        transition: transform 0.6s ease;
        width: 100%;
        height: 400px;
        background-position: center center !important;
        background-repeat: no-repeat;
        background-size: cover;
    }

    .carousel-inner {
        position: relative;
        overflow: hidden;
    }

    .carousel-item {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        transition: left 0.6s ease;
    }

    .carousel-animate-next {
        left: 0 !important;
    }

    .carousel-inner .carousel-item.active,
    .carousel-inner .carousel-item-next,
    .carousel-inner .carousel-item-prev {
        display: block;
    }

    /*.carousel-item-next:not(.carousel-animate-next),*/
    /*.active.carousel-item {*/
    /*    left: -100%;*/
    /*}*/

</style>

@section('content')
    <div class="wrapper">
        @include('frontend.' . $active_theme->theme_abrv . '.components.header')
        <main class="main-content">
            <input type="hidden" id="dont_show" value="{{ json_encode($dont_show) }}">
            <input type="hidden" id="item_json" value="{{ $items_json }}">
            <input type="hidden" id="recommended_rugs_json" value="{{ $recommended_rugs_json }}">
            @if(isset($custom_sizes_items['Length']))
            <input type="hidden" id="item_lenght" value="{{ $custom_sizes_items['Length'] }}">
            @endif
            @if(isset($custom_sizes_items['Width']))
            <input type="hidden" id="item_width" value="{{ $custom_sizes_items['Width'] }}">
            @endif
            {{-- @dump($items['Items'][0]) --}}
            {{-- product image and DETAILS --}}
            <section class="collection-section about-rizzyhome">
                <div class="container-fluid">
                    <div class="d-flex flex-sm-column justify-content-lg-center flex-dir-col singleProduct">
                        {{-- product images for web--}}
                        <div class="col-lg-6 col-sm-12 col-12 d-flex responsive-mb product-img-div-for-web">
                            <div class="col-md-2 products-thumbnails2 d-flex flex-column ">
                                <div class="overflow-pipe" style="height: 100%">
                                    <div class="owl-carousel-imgaes owl-theme">
                                        @if(isset($items_images) && !empty($items_images))
                                        @foreach ($items_images as $key => $image)
                                            <div class="item-video" data-merge="{{ $key }}">
                                                <a href="javascript:void(0);" onclick="imagePreview('{{ $image }}')">
                                                    <img class="xzoom-gallery" id="thumbnail_{{ $key+1 }}"
                                                        src="{{ $image }}"
                                                        xoriginal="{{ $image }}"
                                                        onerror="this.onerror=null; this.src='{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}'"
                                                        alt="{{ $item['DesignID'] }}"/>
                                                </a>
                                            </div>
                                        @endforeach
                                        <div class="item-video" data-merge="{{ $key + 1 }}">
                                            @if (isset($item['VideoURL']) && $item['VideoURL'])
                                                <div class="video-thumbnail" onclick="videoPlay()">
                                                    <div class="video-play-icon" style="background-image: url({{ asset('images/video-play.png') }})">
                                                        <img src="{{ asset('images/video-play.png') }}" alt="">
                                                    </div>
                                                    <img src="{{ $items_images[0] }}" alt="video thumbnail">
                                                </div>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if (isset($items['Items'][0]['ImageNameArray'][0]))
                                <div class="col-md-9 main-img-slide" >
                                    {{-- <div id="myCarousel" class="carousel slide" data-bs-ride="carousel"
                                         style="width: 100%;  height: 100%;" onclick="bigImgModalOpen('dfhfshjf')">
                                        <div class="carousel-inner" style="height: 100%;" onclick="bigImgModalOpen('ffhg')">
                                            @foreach ($items_images as $key => $image)
                                                <div class="carousel-item @if($key === 0) active @endif"
                                                     style="background-image: url({{$image}}); height: 100%;">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div> --}}
                                    {{-- data-autoplay="9000"  --}}
                                    {{-- <div class="container-fluid" style="border: 1px solid red"> --}}
                                        {{-- <div class="fotorama" id="myFotorama" data-nav="dots" data-arrows="false" data-click="false" data-swipe="true"
                                        data-width="100%" data-height="50%" data-maxheight="100%" style="border: 1px solid red;">
                                        @foreach ($items_images as $key => $image) --}}
                                        {{-- <div class="carousel-item @if($key === 0) active @endif"
                                             style="background-image: url({{$image}}); height: 100%; z-index:9999999999">
                                        </div> --}}
                                        {{-- <img src="{{$image}}" alt="product image"   >
                                        @endforeach
                                        @if (isset($item['VideoURL']) && $item['VideoURL'])
                                        @php
                                            $item['VideoURL'] = str_replace('dl=0', 'raw=1', $item['VideoURL']);
                                        @endphp
                                        <a href="{{ $item['VideoURL'] }}" data-video="true">
                                            <img src="{{$items_images[0]}}" alt="video thumbnail">
                                        </a>
                                        @endif --}}
                                    {{-- </div> --}}
                                <div class="container-fluid">
                                    <div class="owl-carousel item-product-slide-carousel">
                                        @foreach ($items_images as $key => $image)
                                        <div class="item"><img src="{{$image}}" alt="product image" alt="{{$key}}" class="img-responsive"></div>
                                        @endforeach
                                    </div>
                                </div>
                                </div>
                                {{--                                <div class="col-md-9 products-picture easyzoom-style" id="product-main-image">--}}
                                {{--                                    <div class="easyzoom easyzoom--overlay">--}}
                                {{--                                        <a--}}
                                {{--                                            href="{{ isset($items['Items'][0]['ImageNameArray']) && $items['Items'][0]['ImageNameArray'] ? $items['Items'][0]['ImageNameArray'][0] : url('/') . ConstantsController::IMAGE_PLACEHOLDER }}">--}}
                                {{--                                            <img class="xzoom" id="image_0"--}}
                                {{--                                                src="{{ isset($items['Items'][0]['ImageNameArray']) && $items['Items'][0]['ImageNameArray'] ? $items['Items'][0]['ImageNameArray'][0] : url('/') . ConstantsController::IMAGE_PLACEHOLDER }}"--}}
                                {{--                                                xoriginal="{{ isset($items['Items'][0]['ImageNameArray']) && $items['Items'][0]['ImageNameArray'] ? $items['Items'][0]['ImageNameArray'][0] : url('/') . ConstantsController::IMAGE_PLACEHOLDER }}"--}}
                                {{--                                                alt="{{ $items['Items'][0]['DesignID'] }}"--}}
                                {{--                                                onerror="this.onerror=null; this.src='{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}'" />--}}
                                {{--                                        </a>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                            @else
                                <div class="col-md-9 products-picture easyzoom-style" id="product-main-image">
                                    <div class="easyzoom easyzoom--overlay">
                                        <a href="{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}">
                                            <img class="xzoom" id="image_0"
                                                 src="{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}"
                                                 xoriginal="{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}"
                                                 alt="{{ $items['Items'][0]['DesignID'] }}"
                                                 onerror="this.onerror=null; this.src='{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}'"/>
                                        </a>
                                    </div>
                                </div>
                            @endif

                        </div>
                         {{-- product images for mobile--}}
                        <div class="col-lg-6 col-sm-12 col-12 d-flex responsive-mb product-img-div-for-mobile">
                            <div class="col-md-2 products-thumbnails d-flex flex-column ">
                                <div class="overflow-pipe" style="height:350px; overflow-y:scroll; overflow-x:hidden;">
                                    @php $i = 0 @endphp
                                    @foreach ($items_images as $image)
                                        <a href="javascript:void(0);" onclick="imagePreview('{{ $image }}')">
                                            <img class="xzoom-gallery" id="thumbnail_{{ $i++ }}"
                                                onclick="setMainImage(this)" src="{{ $image }}"
                                                xoriginal="{{ $image }}"
                                                onerror="this.onerror=null; this.src='{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}'"
                                                alt="{{ $item['DesignID'] }}" />
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            @if (isset($items['Items'][0]['ImageNameArray'][0]))
                                {{-- <div class="col-md-9 products-picture easyzoom-style" id="product-main-image"  style="height:60vh;">
                                    <div class="easyzoom easyzoom--overlay" style="height:60vh;">
                                        <a
                                            href="{{ isset($items['Items'][0]['ImageNameArray']) && $items['Items'][0]['ImageNameArray'] ? $items['Items'][0]['ImageNameArray'][0] : url('/') . ConstantsController::IMAGE_PLACEHOLDER }}">
                                            <img class="xzoom mobile-xzooom" id="image_0"
                                                src="{{ isset($items['Items'][0]['ImageNameArray']) && $items['Items'][0]['ImageNameArray'] ? $items['Items'][0]['ImageNameArray'][0] : url('/') . ConstantsController::IMAGE_PLACEHOLDER }}"
                                                xoriginal="{{ isset($items['Items'][0]['ImageNameArray']) && $items['Items'][0]['ImageNameArray'] ? $items['Items'][0]['ImageNameArray'][0] : url('/') . ConstantsController::IMAGE_PLACEHOLDER }}"
                                                alt="{{ $items['Items'][0]['DesignID'] }}"
                                                onerror="this.onerror=null; this.src='{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}'" />
                                        </a>
                                    </div>
                                </div> --}}
                                <div class="col-md-9 products-picture" id="product-main-image-mobile">
                                    <img class="" id="image_0"
                                        src="{{ isset($items['Items'][0]['ImageNameArray']) && $items['Items'][0]['ImageNameArray'] ? $items['Items'][0]['ImageNameArray'][0] : url('/') . ConstantsController::IMAGE_PLACEHOLDER }}"
                                        alt="{{ $items['Items'][0]['DesignID'] }}"
                                        onerror="this.onerror=null; this.src='{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}'" />
                                </div>
                            @else
                                {{-- <div class="col-md-9 products-picture easyzoom-style" id="product-main-image" style="height:60vh;">
                                    <div class="easyzoom easyzoom--overlay">
                                        <a href="{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}">
                                            <img class="xzoom" id="image_0"
                                                src="{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}"
                                                xoriginal="{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}"
                                                alt="{{ $items['Items'][0]['DesignID'] }}"
                                                onerror="this.onerror=null; this.src='{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}'" />
                                            <img class="" id="image_0"
                                                src="{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}"
                                                alt="{{ $items['Items'][0]['DesignID'] }}"
                                                onerror="this.onerror=null; this.src='{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}'" />
                                        </a>
                                    </div>
                                </div> --}}
                                <div class="col-md-9 products-picture easyzoom-style" id="product-main-image-mobile">
                                    <img class="" id="image_0"
                                        src="{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}"
                                        alt="{{ $items['Items'][0]['DesignID'] }}"
                                        onerror="this.onerror=null; this.src='{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}'" />
                                </div>
                            @endif
                        </div>
                        {{-- product DETAILS --}}
                        <div
                            class="col-12 col-lg-5 col-sm-12 d-flex flex-column flex-wrap  product-desc">
                            <div class="d-flex flex-column flex-wrap">
                                {{-- title and add to wishlist and item badges --}}
                                <div class="mb-2 titlt-nd-badge d-flex justify-content-between align-items-start align-items-md-center align-items-lg-center flex-wrap">
                                    <div
                                        class="d-flex flex-md-row flex-lg-row flex-column align-items-baseline align-items-md-center align-items-lg-center col-12">
                                        <p class="font-jaipur product-heading m-0" id="product-heading" style="text-transform: capitalize !important;">
                                            {{$items['Items'][0]['QualityDescription']}}</p>
                                        <span id="product-heading-design" class="font-jaipur">{{ $items['Items'][0]['DesignID'] }}</span>
                                        {{-- @dump($items['Items'][0]) --}}
                                        {{-- <i
                                            class="bi {{ $items['Items'][0]['InWishlist'] ? 'bi-suit-heart-fill' : 'bi-suit-heart' }} design-add-wishlist"><span>Add
                                                to Wishlist</span></i> --}}
                                        <!-- <div class="position-relative">
                                            <div class="position-absolute item-wishlist-popup-main" style="display: none;">

                                            </div>
                                        </div> -->
                                        <div class="position-fixed design-wishlist-main" style="display: none;">
                                            <div class="overlay-wishlist"></div>
                                            <div class="position-absolute design-wishlist-popup-main">
                                                @include(
                                                    'frontend.' .
                                                        $active_theme->theme_abrv .
                                                        '.components.wishlist')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row jsutify-content-start mt-3" style="width: 100%;">
                                        @php $count = 0; @endphp
                                        @foreach ($items['Items'][0]['UDFFields'] as $index => $field)
                                            @if ($count >= 4)
                                                @break
                                            @endif

                                            @if($field['FieldName'] == "Feature(s)" && isset($field['Value']))
                                                <div class="col-md-4 col-sm-6">
                                                    <p class="font-jaipur FieldName" style="font-size: 0.9rem; color:#2A312E;">{{ $field['FieldName'] }}</p>
                                                </div>
                                                <div class="col-md-5 col-sm-6">
                                                    <ul class="font-jaipur FieldValue feature_bullets" style="font-size: 0.9rem; color:#656565; margin-left: -60px;">
                                                        @foreach (explode(',', $field['Value']) as $feature)
                                                            <li>{{ trim($feature) }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @else
                                                @if(isset($field['Value']) && !empty($field['Value']))
                                                    <div class="col-md-4 col-sm-6">
                                                        <p class="font-jaipur FieldName" style="font-size: 0.9rem; color:#2A312E;">{{ $field['FieldName'] }}</p>
                                                    </div>
                                                    <div class="col-md-5 col-sm-6">
                                                        <p class="font-jaipur FieldValue top-detail" style="font-size: 0.9rem; color:#656565; margin-left: -45px;">{{ $field['Value'] }}</p>
                                                    </div>
                                                @endif
                                            @endif

                                            @php
                                                $count++;
                                            @endphp
                                        @endforeach


                                        @if(isset($items['Items'][0]['PileHeight']) && !empty($items['Items'][0]['PileHeight']))
                                            <div class="col-md-4 col-sm-6"><p class="font-jaipur FieldName" style="font-size: 0.9rem; color:#2A312E;">Pile Height</p></div>
                                            <div class="col-md-5 col-sm-6"><p class="font-jaipur FieldValue top-detail" style="font-size: 0.9rem; color:#656565; margin-left: -45px;">{{ $items['Items'][0]['PileHeight'] }}</p></div>
                                        @endif
                                        @if(isset($items['Items'][0]['GroupPricing']) && !empty($items['Items'][0]['GroupPricing']))
                                            <div class="col-md-4 col-sm-6"><p class="font-jaipur FieldName" style="font-size: 0.9rem; color:#2A312E;">Group Pricing</p></div>
                                            <div class="col-md-5 col-sm-6"><p class="font-jaipur FieldValue top-detail" style="font-size: 0.9rem; color:#656565; margin-left: -45px;">{{ $items['Items'][0]['GroupPricing'] }}</p></div>
                                        @endif
                                    </div>
                                    </div>
                                    <!-- <p class="product-description" id="product-description">{{ $items['Items'][0]['ProductDescription'] }}</p> -->
                                    <div class="item-badges">
                                        @if (isset($items['Items'][0]['SpecialBuy']) && CommonController::check_bit_field($items['Items'][0], 'SpecialBuy'))
                                            <span class="badge bg-warning">Special Buy</span>
                                        @endif
                                        @if (isset($items['Items'][0]['TopSeller']) && CommonController::check_bit_field($items['Items'][0], 'TopSeller'))
                                            <span class="badge bg-success">Top Seller</span>
                                        @endif
                                        @if (isset($items['Items'][0]['Clearence']) && CommonController::check_bit_field($items['Items'][0], 'Clearence'))
                                            <span class="badge bg-info">Clearance</span>
                                        @endif
                                        @if (isset($items['Items'][0]['NewArrivalExpiry']) &&
                                                CommonController::check_bit_field($items['Items'][0], 'NewArrivalExpiry'))
                                            <span class="badge bg-secondary">New Arrival</span>
                                        @endif
                                        @if (isset($items['Items'][0]['HotBuy']) && CommonController::check_bit_field($items['Items'][0], 'HotBuy'))
                                            <span class="badge bg-danger">Hot Buy</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="product-specs d-flex flex-row">
                                    <div class="col-md-5 col-6 d-flex flex-column">
                                        <p class="specs mb-2 UDField-template col-md-12 row">
                                            <strong class="col-md-3 text-nowrap FieldName font-jaipur" style="font-size: 13px"></strong> <span
                                                class="col-md-9 FieldValue font-jaipur" style="font-size: 13px"> </span>
                                        </p>
                                    </div>
                                </div>

                                {{-- new accordians --}}

                                {{-- <div class="product-accordions accordion" id="productAccordions"> --}}
                                    {{-- product DETAILS --}}
                                    {{-- <div class="accordion-item">
                                        <h2 class="accordion-header" id="productDetails">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#productDetailsCollapse"
                                                    aria-expanded="false" aria-controls="productDetailsCollapse">
                                            <span class="row" style="width: 100%">
                                                <span class="col-8">
                                                    PRODUCT DETAILS
                                                </span>
                                                <span class="col-4" style="text-align: right">

                                                </span>
                                            </span>
                                            </button>
                                        </h2>
                                        <div id="productDetailsCollapse" class="accordion-collapse collapse"
                                             aria-labelledby="productDetails" data-bs-parent="#productAccordions">
                                            <div class="accordion-body">
                                                <div class="col-md-12 d-flex flex-wrap flex item-udf-fields"
                                                     id="item-udf-fields">
                                                    @if(empty($items['Items'][0]['GroupPricing']))
                                                        <p class="specs mb-2 UDField col-md-6">
                                                            <strong class=" col-md-6 FieldName"> Group Price :</strong>
                                                            <span
                                                                class="col-md-6 FieldValue"> {{ $items['Items'][0]['GroupPricing'] }} </span>
                                                        </p>
                                                    @endif
                                                    @foreach ($items['Items'][0]['UDFFields'] as $field)
                                                        @if ($field['FieldName'] == 'Size')
                                                            @continue
                                                        @endif
                                                        <p class="specs mb-2 UDField col-md-12 row">
                                                            <strong
                                                                class=" col-md-6 text-nowrap FieldName"> {{ $field['FieldName'] }}
                                                                :</strong>
                                                        @if ($field['FieldName'] == 'Feature(s)')
                                                            <ul class=" FieldValue col-md-6 feature_bullets ps-3">
                                                                @foreach (explode(',', $field['Value']) as $feature)
                                                                    <li>{{ trim($feature) }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @elseif ($field['FieldName'] == 'Feature 1')
                                                            <ul class=" col-md-6 FieldValue feature_bullets ps-3">
                                                                @foreach (explode(',', $field['Value']) as $feature)
                                                                    <li>{{ trim($feature) }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            <span
                                                                class="col-md-6 FieldValue"> {{ $field['Value'] }} </span>
                                                            @endif
                                                            </p>
                                                            @endforeach
                                                            @foreach (['Country', 'PileHeight'] as $key)
                                                                @if (isset($items['Items'][0][$key]) && $items['Items'][0][$key])
                                                                    <p class="specs mb-2 UDField col-md-6">
                                                                        <strong class=" col-md-6 FieldName"> {{ $key }}
                                                                            :</strong>
                                                                        <span
                                                                            class="col-md-6 FieldValue"> {{ $items['Items'][0][$key] }} </span>
                                                                    </p>
                                                                @endif
                                                            @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    {{-- shipping INFO --}}
                                    {{-- <div class="accordion-item">
                                        <h2 class="accordion-header" id="shippingInfo">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#shippingInfoCollapse"
                                                    aria-expanded="false" aria-controls="shippingInfoCollapse">
                                              <span class="row" style="width: 100%">
                                                  <span class="col-8">
                                                    SHIPPING INFO
                                                </span>
                                                <span class="col-4" style="text-align: right">

                                                    </span>
                                                </span>
                                                </span>
                                            </button>
                                        </h2>
                                        <div id="shippingInfoCollapse" class="accordion-collapse collapse"
                                             aria-labelledby="shippingInfo" data-bs-parent="#productAccordions">
                                            <div class="accordion-body">
                                                @if (isset($items['ItemsETA']) && $items['ItemsETA'])
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered align-middle">
                                                            <thead>
                                                            <tr class="table-secondary">
                                                                <td scope="col" style="background: #F8F6F3">Product ID
                                                                </td>
                                                                <td scope="col" style="background: #F8F6F3">Size</td>
                                                                <td scope="col" style="background: #F8F6F3">Weight</td>
                                                                <td scope="col" style="background: #F8F6F3">Package
                                                                    Dimensions
                                                                </td>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach ($items['ItemsETA'] as $itemETA)
                                                                <tr>
                                                                    <td>{{ $itemETA['ItemID'] }}</td>
                                                                    <td>{{ $itemETA['Size'] }}</td>
                                                                    <td>{{ $itemETA['DimentionalWeight'] }}</td>
                                                                    <td>{{ $itemETA['ShippingDimension'] }}</td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div> --}}
                                {{-- </div> --}}

                                <!-- size chart popup modal -->
                                <div class="size-chart-with-inventory">
                                    <p class="font-jaipur" id="show-inventory-p" style="font-size: 0.8rem">
                                        Size
                                        <span class="show-inventory-btn ps-2 font-jaipur" data-bs-toggle="modal"
                                              data-bs-target="#exampleModal" style="font-size: 0.8rem">
                                            Show Inventory
                                        </span>
                                    </p>

                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div
                                            class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center product_chart_model" style="z-index: 99999 !important">
                                                    @if (isset($items['ItemsETA']) && $items['ItemsETA'])
                                                        <div class="m-auto p-0 text-center product_chart_main">
                                                            <div id="" class="prodAvlChart">
                                                                <div class="mb-4">
                                                                    <p class="heading-PAChart">Product Chart</p>
                                                                </div>
                                                                <div style="overflow-x:auto;">
                                                                    <table id="tblProductSizes" class="table" border="0"
                                                                           cellpadding="3"
                                                                           cellspacing="2" width="100%">
                                                                        <tbody>
                                                                        <tr style="vertical-align: middle;border-top: 1px solid #a5a9aa;">
                                                                            <td width="15%" align="center"
                                                                                class="PAChart-Sizes PAChart-text-Heading">{{ $size_heading }}</td>
                                                                            <td width="15%" align="center"
                                                                                class="PAChart-Dimensions-Weight PAChart-text-Heading">
                                                                                Shipping
                                                                                Dimensions / Weight
                                                                            </td>
                                                                        </tr>
                                                                        @foreach ($items['ItemsETA'] as $itemETA)
                                                                            @if($itemETA['SizeID'] != "CUST")
                                                                                <tr class="">
                                                                                    <td width="15%" align="center"
                                                                                        class="PAChart-Sizes">
                                                                                        {{ $itemETA['Size'] }}</td>
                                                                                    <td width="15%" align="center"
                                                                                        class="PAChart-Dimensions-Weight">
                                                                                        {{ $itemETA['ShippingDimension'] }}
                                                                                        <br/>{{ $itemETA['DimentionalWeight'] }}
                                                                                    </td>
                                                                                </tr>
                                                                            @endif
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if (isset($active_theme_json->general->bulk_add_to_cart) && $active_theme_json->general->bulk_add_to_cart && Auth::check())
                                                        @if (isset($items['ItemsETA']) && $items['ItemsETA'])
                                                            <div class="m-auto p-0 text-center product_chart" style="">
                                                                <div id="prodAvlChart" class="prodAvlChart"
                                                                     style="display: block;">
                                                                    <div class="mb-4">
                                                                        <p class="heading-PAChart">Product Availability
                                                                            Chart</p>
                                                                    </div>
                                                                    <div style="overflow-x:auto;">
                                                                        <table id="tblProductSizes"
                                                                               class="table table-striped" border="0"
                                                                               cellpadding="3"
                                                                               cellspacing="2" width="100%">
                                                                            <tbody style="border: 1px solid #a5a9aa;">
                                                                            <tr style="vertical-align: middle;border-top: 1px solid #a5a9aa;">
                                                                                <td width="15%" align="center"
                                                                                    class="PAChart-product-id PAChart-text-Heading">
                                                                                    Product ID
                                                                                </td>
                                                                                <td width="15%" align="center"
                                                                                    class="PAChart-Sizes PAChart-text-Heading">
                                                                                    Size
                                                                                </td>
                                                                                {{-- <td width="15%" align="center"
                                                                                    class="PAChart-Dimensions-Weight PAChart-text-Heading">Shipping
                                                                                    Dimensions / Weight</td> --}}
                                                                                @if (!in_array('.PAChart-Price', $dont_show))
                                                                                    <td width="15%" align="center"
                                                                                        class="PAChart-Price PAChart-text-Heading">
                                                                                        Price
                                                                                    </td>
                                                                                @endif
                                                                                <td width="10%" align="center"
                                                                                    class="PAChart-InStock PAChart-text-Heading">
                                                                                    Available now
                                                                                </td>
                                                                                <td width="15%" align="center"
                                                                                    class="PAChart-Within30Days PAChart-text-Heading">
                                                                                    Available within
                                                                                    30 days
                                                                                </td>
                                                                                <td width="15%" align="center"
                                                                                    class="PAChart-Within2Months PAChart-text-Heading">
                                                                                    Available within
                                                                                    31-60 days
                                                                                </td>
                                                                                {{-- <td width="15%" align="center"
                                                                                    class="PAChart-Over2Months PAChart-text-Heading">Quantity Over 2
                                                                                    Months</td> --}}
                                                                                <td width="15%" align="center"
                                                                                    class=" PAChart-text-Heading">
                                                                                    Available after
                                                                                    60 days
                                                                                </td>
                                                                                <td width="15%" align="center"
                                                                                    class="PAChart-product-id PAChart-text-Heading">
                                                                                    Quantity
                                                                                </td>
                                                                            </tr>
                                                                            @foreach ($items['ItemsETA'] as $itemETA)
                                                                                @if($itemETA['SizeID'] != "CUST")
                                                                                    <tr class="">
                                                                                        <input type="hidden"
                                                                                            class="cart_item_id"
                                                                                            name="product_cart_item_id[]"
                                                                                            value="{{ $itemETA['ItemID'] }}">
                                                                                        <input type="hidden"
                                                                                            class="cart_design_id"
                                                                                            name="cart_design_id[]"
                                                                                            value="{{ $itemETA['DesignID'] }}">
                                                                                        <input type="hidden"
                                                                                            class="cart_customer_id"
                                                                                            name="cart_customer_id"
                                                                                            value="">
                                                                                        <input type="hidden"
                                                                                            class="cart_item_name"
                                                                                            name="cart_item_name[]"
                                                                                            value="{{ $itemETA['ItemName'] }}">
                                                                                        <input type="hidden"
                                                                                            class="cart_item_quantity"
                                                                                            name="cart_item_quantity"
                                                                                            value="">
                                                                                        <input type="hidden"
                                                                                            class="cart_item_price"
                                                                                            name="cart_item_price[]"
                                                                                            value="{{ $itemETA['BasePrice'] }}">
                                                                                        <input type="hidden"
                                                                                            class="cart_item_color"
                                                                                            name="cart_item_color[]"
                                                                                            value="{{ $itemETA['ItemColor'] }}">
                                                                                        <input type="hidden"
                                                                                            class="cart_item_size"
                                                                                            name="cart_item_size[]"
                                                                                            value="{{ $itemETA['Size'] }}">
                                                                                        <input type="hidden"
                                                                                            class="cart_item_currency"
                                                                                            name="cart_item_currency[]"
                                                                                            value="">
                                                                                        <input type="hidden"
                                                                                            class="cart_item_image"
                                                                                            name="cart_item_image[]"
                                                                                            value="{{ $itemETA['ImageName'] }}">
                                                                                        <input type="hidden"
                                                                                            class="cart_item_eta"
                                                                                            name="cart_item_eta[]"
                                                                                            value="">
                                                                                        <td width="15%" align="center"
                                                                                            class="">
                                                                                            {{ $itemETA['ItemID'] }}</td>
                                                                                        <td width="15%" align="center"
                                                                                            class="PAChart-Sizes">
                                                                                            {{ $itemETA['Size'] }}</td>
                                                                                        {{-- <td width="15%" align="center"
                                                                                            class="PAChart-Dimensions-Weight">
                                                                                            {{ $itemETA['ShippingDimension'] }}<br />{{ $itemETA['DimentionalWeight'] }}
                                                                                        </td> --}}
                                                                                        @if (!in_array('.PAChart-Price', $dont_show)  )
                                                                                            <td width="15%" align="center"
                                                                                                class="PAChart-Price">
                                                                                                {{ ConstantsController::CURRENCY . number_format($itemETA['BasePrice'], ConstantsController::ALLOWED_DECIMALS, '.', '') }}
                                                                                            </td>
                                                                                            {{-- @else
                                                                                                <td>
                                                                                                    Login to view price
                                                                                                </td> --}}
                                                                                        @endif
                                                                                        <td width="10%" align="center"
                                                                                            class="PAChart-InStock">
                                                                                            {{ $itemETA['QtyInStock'] }}</td>
                                                                                        <td width="15%" align="center"
                                                                                            class="PAChart-Within30Days PAChart-text-Within30Days">
                                                                                            {{ $itemETA['QtyThirtyDay'] }}</td>
                                                                                        <td width="15%" align="center"
                                                                                            class="PAChart-Within2Months">
                                                                                            {{ $itemETA['QtyTwoMonth'] }}</td>
                                                                                        {{-- <td width="15%" align="center" class="PAChart-Over2Months">
                                                                                            {{ $itemETA['QtyOverTwoMonth'] }}</td> --}}
                                                                                        <td width="15%" align="center"
                                                                                            class="">
                                                                                            @if(!empty(isset($itemETA['QtyOverTowMonthETA'])) && !empty(isset($itemETA['QtyOverTwoMonth'])))
                                                                                                @php
                                                                                                    $eta = formatETA($itemETA['QtyOverTowMonthETA'][0]);
                                                                                                @endphp

                                                                                                <div>{{ $itemETA['QtyOverTwoMonth'] ?? 'N/A' }}</div>
                                                                                                <div>
                                                                                                    ETA:{{ $eta['date'] ?? 'N/A' }}</div>
                                                                                            @else
                                                                                                <div>N/A</div>
                                                                                            @endif
                                                                                        </td>
                                                                                        <td width="15%" align="center" class="PAChart-Quantity">
                                                                                            <input class="item_qty chart_item_qty" autocomplete="off" onkeydown="if(this.key==='.'){this.preventDefault();}" type="number" name="quantity[]" maxlength="4" value="" min="1">
                                                                                        </td>
                                                                                    </tr>
                                                                                @endif
                                                                            @endforeach
                                                                            </tbody>
                                                                        </table>

                                                                        {{--  start--}}
                                                                        <div class="container">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    @if(Auth::check() && !Auth::user()->is_customer)
                                                                                        <div class="d-flex flex-row">
                                                                                            <label class="form-label font-crimson me-2"><p class="m-0">Customers</p></label>
                                                                                            <div class="ps-0 pe-0 d-flex active_customer_select" id="">
                                                                                                <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-html="true" data-placement="left" title="Please select the customer for whom the order needs to be created. Please note once selected than the customer cannot be changed till the order is placed or cart is emptied."></i>
                                                                                            </div>
                                                                                            <div class="ps-0 pe-0 d-flex disabled_customer_select" id="">
                                                                                                <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-html="true" data-placement="left" title="The item will be added in the cart for the selected customer. If the customer needs to be changed then either checkout the order or empty the cart."></i>
                                                                                            </div>
                                                                                        </div>

                                                                                        <select name="customer" class="form-select me-2" aria-label="Default select customer" id="chart_grid_item_customer">
                                                                                            <option selected value="0">Select Customer
                                                                                            </option>
                                                                                        </select>
                                                                                    @endif
                                                                                </div>
                                                                                {{-- <div class="col-md-6">
                                                                                    <button type="button" class="btn btn-dark btn-lg d-none" style="margin: 29px 0px;" id="chart_add_to_cart">Add To Cart</button>
                                                                                </div> --}}
                                                                                {{-- @if($items['Items'][0]['SpecialBuy'] != "True" ) --}}
                                                                                <div class="col-md-6"></div>
                                                                                    <div class="col-md-6 ">
                                                                                @if($items['Items'][0]['SpecialBuy'] == "True" )
                                                                                   
                                                                                    <div class="row">
                                                                                        <div class="col-sm-2">
                                                                                            <img src="{{ asset('RZY/images/special-buy-30-off.png?v=1') }}" class="img-fluid" style="width: 100%;" />
                                                                                        </div>
                                                                                        <div class="col-sm-8">
                                                                                             <p class="my-4">Prices shown reflect final discount.</p>
                                                                                        </div>
                                                                                    </div>
                                                    
                                                                                        {{-- <p>To receive discount pricing, orders can NOT be added to cart. Submit orders for Special buys to:
                                                                                            <a href="mailto:orders@rizzyhome.com">orders@rizzyhome.com</a>
                                                                                        </p> --}}
                                                                                   
                                                                                   
                                                                                @endif
                                                                                </div>
                                                                                @if (strcmp(ConstantsController::USER_ROLES['admin'], Auth::user()->role) !== 0 &&
                                                                                        (strcmp(ConstantsController::USER_ROLES['staff'], Auth::user()->role) !== 0 ||
                                                                                        in_array('allow-checkout', Auth::user()->getDataAttribute('permissions', []))))
                                                                                        <div class="col-md-6">
                                                                                            <button type="submit"
                                                                                                    class="btn btn-dark add-to-cart d-none mt-4 p-2"
                                                                                                    id="chart_add_to_cart">
                                                                                                <span
                                                                                                    class="label-text font-jaipur">Add to Cart</span>
                                                                                                <div class="spinner-border"
                                                                                                    role="status"
                                                                                                    style="margin: 0 auto;">
                                                                                                    <span class="sr-only"
                                                                                                        style="opacity:0;">Loading...</span>
                                                                                                </div>
                                                                                            </button>
                                                                                        </div>
                                                                                    @endif
                                                                            </div>
                                                                        </div>
                                                                        {{-- end --}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    {{-- <div class="login-addtocart-selectcustomer">
                                                        <div class="action-item-lg col-md-4 p-2 ps-0 d-none customers-main position-relative item_customer_parent"
                                                            id="">
                                                            <div class="d-flex flex-row">
                                                                <label class="form-label font-crimson me-2">Customers</label>
                                                                <div class="ps-0 pe-0 d-flex active_customer_select" id="">
                                                                    <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-html="true"
                                                                        data-placement="left"
                                                                        title="Please select the customer for whom the order needs to be created. Please note once selected than the customer cannot be changed till the order is placed or cart is emptied."></i>
                                                                </div>
                                                                <div class="ps-0 pe-0 d-flex disabled_customer_select" id="">
                                                                    <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-html="true"
                                                                        data-placement="left"
                                                                        title="The item will be added in the cart for the selected customer. If the customer needs to be changed then either checkout the order or empty the cart."></i>
                                                                </div>
                                                            </div>
                                                            <select name="customer" class="form-select me-2" aria-label="Default select customer"
                                                                id="grid_item_customer">
                                                                <option selected value="0">Select Customer</option>
                                                            </select>
                                                        </div>
                                                        <div class="d-none cart_main_custom" id="grid_cart_main">
                                                            @auth()
                                                                @if (strcmp(ConstantsController::USER_ROLES['admin'], Auth::user()->role) !== 0 &&
                                                                        (strcmp(ConstantsController::USER_ROLES['staff'], Auth::user()->role) !== 0 ||
                                                                            in_array('allow-checkout', Auth::user()->getDataAttribute('permissions', []))))
                                                                    <button type="submit" class="btn btn-dark add-to-cart d-none mt-4"
                                                                        id="grid_add_to_cart">
                                                                        <span class="label-text">Add to Cart</span>
                                                                        <div class="spinner-border" role="status" style="margin: 0 auto;">
                                                                            <span class="sr-only" style="opacity:0;">Loading...</span>
                                                                        </div>
                                                                    </button>
                                                                @endif
                                                            @endauth
                                                            @guest()
                                                                <button type="button" class="log-in-popup-button btn btn-dark d-none"
                                                                    id="login_by_popup">
                                                                    Log In
                                                                </button>
                                                            @endguest
                                                        </div>
                                                    </div> --}}
                                                    {{-- @else --}}

                                                    {{--                                                @endif--}}
                                                </div>
                                                <div class="d-none modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close
                                                    </button>
                                                    <button type="button" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- size dropdown --}}

                                <div class="standard-custom-size">
                                    <ul class="nav nav-tabs border-0" id="myTabs">
                                        <li class="nav-item pb-2">
                                            <a class="nav-link active font-jaipur" id="standardSizeTab" data-bs-toggle="tab"
                                               href="#standardSize" style="font-family: 'Helvetica','Open Sans','Montserrat','sans-serif' !important;">Standard Sizes</a>
                                        </li>
                                        @if($custom_sizes_items && isset($main_collection['Description']) && strtolower($main_collection['Description']) == 'rugs')
                                            <li class="nav-item m-0 pb-2" title="Click here for Size, style, color and new productions of existing styles or new creations">
                                                <a class="nav-link font-jaipur" id="customSizeTab" data-bs-toggle="tab"
                                                   href="#customSize" style="font-family: 'Helvetica','Open Sans','Montserrat','sans-seri !important;">Custom Size</a>
                                            </li>
                                        @endif
                                        {{-- <pre>
                                        @php
                                            print_r($items);
                                        @endphp
                                        </pre> --}}
                                        @if($items['Items'][0]['ResizeReshape'])
                                            <li class="nav-item m-0 pb-2" title="Click here for a size or shape modification of an existing style (limitations apply)">
                                                <a class="nav-link font-jaipur" id="customSizeTab" data-bs-toggle="tab"
                                                   href="#cutSize" style="font-family: 'Helvetica','Open Sans','Montserrat','sans-seri !important;">Resize Reshape</a>
                                            </li>
                                        @endif
                                    </ul>

                                    <div class="tab-content mt-2 p-0">

                                        {{-- standard size --}}

                                        <div class="tab-pane fade show active" id="standardSize">
                                            @if (isset($items['ItemsETA']) && $items['ItemsETA'])
                                                <div class="dropdown">
                                                    @guest
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            id="dropdownSizeBtnG" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                        Select a size
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownSizeBtnG">
                                                        <li><a class="dropdown-item disabled font-jaipur">Select a size</a></li>
                                                        @foreach ($items['ItemsETA'] as $itemETA)
                                                            @if($itemETA['SizeID'] != "CUST")
                                                                <li>
                                                                    <input type="hidden" class="cart_item_size"
                                                                        name="cart_item_size[]"
                                                                        value="{{ $itemETA['Size'] }}">
                                                                    <a class="dropdown-item size-option" href="#0">
                                                                        <input type="hidden" class="cart_item__id"
                                                                            name="cart_item_id[]"
                                                                            value="{{ $itemETA['ItemID'] }}">
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center col-12">
                                                                            <p class="m-0 cu-item-size">{{ $itemETA['Size'] }}</p>
                                                                            <div class="col-3">
                                                                                @if( $itemETA['QtyInStock'] <= 0)
                                                                                    <span
                                                                                        style="color: #EA7410;">Backorder</span>
                                                                                    {{-- <p class="m-0"> ETA: {{ isset($itemETA['QtyOverTowMonthETA']) ? $itemETA['QtyOverTowMonthETA'][0] : $itemETA['QtyThirtyDayETA'][0]}}</p> --}}
                                                                                    @if(!empty(isset($itemETA['QtyThirtyDayETA'])))
                                                                                        @php
                                                                                            $eta = formatETA($itemETA['QtyThirtyDayETA'][0]);
                                                                                        @endphp

                                                                                        <p class="m-0">
                                                                                            ETA:{{ $eta['date'] ?? 'N/A' }}</p>
                                                                                    @elseif(!empty(isset($itemETA['QtyTwoMonthETA'])))
                                                                                        @php
                                                                                            $eta = formatETA($itemETA['QtyTwoMonthETA'][0]);
                                                                                        @endphp

                                                                                        <p class="m-0">
                                                                                            ETA:{{ $eta['date'] ?? 'N/A' }}</p>
                                                                                    @elseif(!empty(isset($itemETA['QtyOverTowMonthETA'])))
                                                                                        @php
                                                                                            $eta = formatETA($itemETA['QtyOverTowMonthETA'][0]);
                                                                                        @endphp

                                                                                        <p class="m-0">
                                                                                            ETA:{{ $eta['date'] ?? 'N/A' }}</p>
                                                                                    @else
                                                                                        <p class="m-0">N/A</p>
                                                                                    @endif
                                                                                @else
                                                                                    <span
                                                                                        style="color: #0AE848;">In stock</span>
                                                                                    <p class="m-0">Ready to ship</p>
                                                                                @endif

                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                    @endguest
                                                    @auth
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            id="dropdownSizeBtn" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                        Select a size
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownSizeBtn">
                                                        <li><a class="dropdown-item disabled font-jaipur">Select a size</a></li>
                                                            @foreach ($items['ItemsETA'] as $itemETA)
                                                                @if($itemETA['SizeID'] != "CUST")
                                                                    <li>
                                                                        <input type="hidden" class="cart_item_size"
                                                                            name="cart_item_size[]"
                                                                            value="{{ $itemETA['Size'] }}">
                                                                        <a class="dropdown-item size-option" href="#0">
                                                                            <input type="hidden" class="cart_item__id"
                                                                                name="cart_item_id[]"
                                                                                value="{{ $itemETA['ItemID'] }}">
                                                                            <div
                                                                                class="d-flex justify-content-between align-items-center col-12">
                                                                                <p class="m-0 cu-item-size">{{ $itemETA['Size'] }}</p>
                                                                                <div class="col-3">
                                                                                    @if( $itemETA['QtyInStock'] <= 0)
                                                                                        <span
                                                                                            style="color: #EA7410;">Backorder</span>
                                                                                        {{-- <p class="m-0"> ETA: {{ isset($itemETA['QtyOverTowMonthETA']) ? $itemETA['QtyOverTowMonthETA'][0] : $itemETA['QtyThirtyDayETA'][0]}}</p> --}}
                                                                                        @if(!empty(isset($itemETA['QtyThirtyDayETA'])))
                                                                                            @php
                                                                                                $eta = formatETA($itemETA['QtyThirtyDayETA'][0]);
                                                                                            @endphp

                                                                                            <p class="m-0">
                                                                                                ETA:{{ $eta['date'] ?? 'N/A' }}</p>
                                                                                        @elseif(!empty(isset($itemETA['QtyTwoMonthETA'])))
                                                                                            @php
                                                                                                $eta = formatETA($itemETA['QtyTwoMonthETA'][0]);
                                                                                            @endphp

                                                                                            <p class="m-0">
                                                                                                ETA:{{ $eta['date'] ?? 'N/A' }}</p>
                                                                                        @elseif(!empty(isset($itemETA['QtyOverTowMonthETA'])))
                                                                                            @php
                                                                                                $eta = formatETA($itemETA['QtyOverTowMonthETA'][0]);
                                                                                            @endphp

                                                                                            <p class="m-0">
                                                                                                ETA:{{ $eta['date'] ?? 'N/A' }}</p>
                                                                                        @else
                                                                                            <p class="m-0">N/A</p>
                                                                                        @endif
                                                                                    @else
                                                                                        <span
                                                                                            style="color: #0AE848;">In stock</span>
                                                                                        <p class="m-0">Ready to ship</p>
                                                                                    @endif

                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                    </ul>
                                                    @endauth
                                                </div>
                                            @endif
                                            {{-- qty --}}
                                            @if (isset($items['ItemsETA']) && $items['ItemsETA'])
                                                @if (Auth::check())
                                                    <div class="mt-4 cu-quntity">
                                                        @else
                                                            <div class="mt-4 cu-quntity d-none">
                                                                @endif
                                                                <p class="m-0">Quantity</p>
                                                                {{-- @if (!in_array('.PAChart-Quantity', $dont_show)) --}}
                                                                {{-- <div class="PAChart-Quantity border-0 quantity-input d-flex justify-content-start">
                                                                    <button class="btn btn-outline-secondary"
                                                                    type="button" id="minus-btn"><span>-</span>
                                                                    </button>
                                                                    <input class="item_qty form-control text-center"
                                                                        autocomplete="off"
                                                                        id="quantity"
                                                                        aria-label="Quantity"
                                                                        onkeydown="if(this.key==='.'){this.preventDefault();}"
                                                                        type="number" name="quantity[]" maxlength="4"
                                                                        value="1" min="1">
                                                                    <button class="btn btn-outline-secondary"
                                                                            type="button" id="plus-btn"><span>+</span>
                                                                    </button>
                                                                </div> --}}
                                                                <div class="container-fluid">
                                                                    <div class="row">
                                                                        <div class="col-md-5 d-flex justify-content-start" style="margin-left:-12px !important; margin-bottom:25px !important;">
                                                                            <button class="btn btn-outline-secondary"
                                                                                type="button" id="minus-btn"><span>-</span>
                                                                                </button>
                                                                            <input class="item_qty form-control text-center"
                                                                                    autocomplete="off"
                                                                                    id="quantity"
                                                                                    aria-label="Quantity"
                                                                                    onkeydown="if(this.key==='.'){this.preventDefault();}"
                                                                                    type="text" name="quantity[]" maxlength="4"
                                                                                    value="1" min="1">
                                                                            <button class="btn btn-outline-secondary"
                                                                                    type="button" id="plus-btn"><span>+</span>
                                                                            </button>
                                                                        </div>
                                                                        @if (!in_array('.PAChart-Price', $dont_show))
                                                                        <div class="col-md-6 d-flex flex-column p-0">
                                                                            <p class="p-0 m-0"><strong style="font-size: 1.2rem;" class="font-jaipur" id="main-price"></strong>
                                                                                @if($items['Items'][0]['SpecialBuy'] != "True" )
                                                                                <span id="main-price-cut" style="text-decoration: line-through; !important font-size: 1.2rem;" class="font-jaipur mx-3"></span>
                                                                                @endif
                                                                            </p>
                                                                        
                                                                            
                                                                            <div class="d-flex p-0 m-0">
                                                                                <p style="font-size:0.8rem; color:#656565; margin-right:0.9rem; display:none;" class="font-jaipur mr-3 other-price-text">MAP<span style="margin-left:0.3rem; font-size:0.9rem;" id="map-price">$0.00</span></p>
                                                                                <p style="font-size:0.8rem; color:#656565; margin-right:0.9rem; display:none;" class="font-jaipur mr-3 other-price-text">MSRP<span style="margin-left:0.3rem; font-size:0.9rem;" id="msrp-price">$0.00</span></p>
                                                                            </div>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                {{-- @endif --}}
                                                            </div>
                                                        @endif

                                                        {{-- rugpads --}}
                                                        <div class="mt-4 d-none" id="recommended-rugs">
                                                            <p class="m-0" class="font-jaipur">Recommended Rug Pads</p>
                                                            <div class="recc-rug-pad mt-2 mb-4" id="rugs-items-bk">
                                                            </div>
                                                        </div>

                                                        <input type="hidden" id="cart_item_id" name="cart_item_id"
                                                               value="">
                                                        <input type="hidden" id="cart_item_sel_id" name="cart_item_sel_id"
                                                               value="">
                                                        <input type="hidden" id="cart_design_id" name="cart_design_id"
                                                               value="">
                                                        <input type="hidden" id="cart_customer_id"
                                                               name="cart_customer_id" value="">
                                                        <input type="hidden" id="cart_item_name" name="cart_item_name"
                                                               value="">
                                                        <input type="hidden" id="cart_item_quantity"
                                                               name="cart_item_quantity" value="">
                                                        <input type="hidden" id="cart_item_price" name="cart_item_price"
                                                               value="">
                                                        <input type="hidden" id="cart_item_color" name="cart_item_color"
                                                               value="">
                                                        <input type="hidden" id="cart_item_size" name="cart_item_size"
                                                               value="">
                                                        <input type="hidden" id="cart_item_currency"
                                                               name="cart_item_currency" value="">
                                                        <input type="hidden" id="cart_item_image" name="cart_item_image"
                                                               value="">
                                                        <input type="hidden" id="cart_item_eta" name="cart_item_eta"
                                                               value="">

                                                        <div
                                                            class="col-sm-12 col-lg-12 d-flex flex-row product-actions mt-2 flex-wrap d-none"
                                                            style="display: none">
                                                            <div class="action-item-lg col-md-4 p-2 ps-0"
                                                                 id="item_variant_parent">
                                                                <label
                                                                    class="form-label font-crimson">Collections</label>
                                                                <select class="form-select" id="item_variant"
                                                                        aria-label="Default select example"
                                                                        name="variant">
                                                                    <option selected value="0">Select Collection
                                                                    </option>
                                                                </select>
                                                            </div>
                                                            <div class="action-item-lg col-md-4 d-none p-2 ps-0"
                                                                 id="item_color_parent">
                                                                <label class="form-label font-crimson">Colors</label>
                                                                <select class="form-select" id="item_color"
                                                                        aria-label="Default select example"
                                                                        name="color">
                                                                    <option selected value="0">Select Color</option>
                                                                </select>
                                                            </div>
                                                            <div
                                                                class="action-item-lg col-md-4 p-2 ps-0 d-none sizes-main"
                                                                id="item_size_parent">
                                                                <label class="form-label font-crimson">Size</label>
                                                                <select class="form-select"
                                                                        aria-label="Default select example"
                                                                        id="item_size"
                                                                        name="size">
                                                                    <option selected value="0">Select Size</option>
                                                                </select>
                                                            </div>
                                                            <input type="hidden"
                                                                   value="{{ $items['Items'][0]['UserCustomerInfo']['IsSaleRep'] }}"
                                                                   name="sale_rep">
                                                            <div
                                                                class="action-item-lg col-md-4 p-2 ps-0 d-none customers-main flex-row position-relative item_customer_parent"
                                                                id="">
                                                                <div class="d-flex flex-row">
                                                                    <label class="form-label font-crimson me-2">Customers</label>
                                                                    <div class="ps-0 pe-0 d-flex active_customer_select"
                                                                         id="">
                                                                        <i class="bi bi-info-circle-fill"
                                                                           data-toggle="tooltip" data-html="true"
                                                                           data-placement="left"
                                                                           title="Please select the customer for whom the order needs to be created. Please note once selected than the customer cannot be changed till the order is placed or cart is emptied."></i>
                                                                    </div>
                                                                    <div
                                                                        class="ps-0 pe-0 d-flex disabled_customer_select"
                                                                        id="">
                                                                        <i class="bi bi-info-circle-fill"
                                                                           data-toggle="tooltip" data-html="true"
                                                                           data-placement="left"
                                                                           title="The item will be added in the cart for the selected customer. If the customer needs to be changed then either checkout the order or empty the cart."></i>
                                                                    </div>
                                                                </div>
                                                                <select name="customer" class="form-select me-2"
                                                                        aria-label="Default select customer"
                                                                        id="item_customer">
                                                                    <option selected value="0">Select Customer</option>
                                                                </select>
                                                            </div>
                                                            <div class="action-item-lg col-md-4 p-2 ps-0 pe-0 d-none"
                                                                 id="qty-main">
                                                                <label class="form-label font-crimson">Qty</label>
                                                                <input id="item_qty" autocomplete="off"
                                                                       class="form-control"
                                                                       onkeydown="if(this.key==='.'){this.preventDefault();}"
                                                                       type="number"
                                                                       name="quantity" maxlength="4" value="" min="1"
                                                                       required>
                                                                <span class="form-label font-crimson"
                                                                      id="qty_msg"></span>
                                                            </div>
                                                            <div class="m-5 spinner-border qty-loader d-none"
                                                                 role="status">
                                                                <span class="sr-only"
                                                                      style="opacity:0;">Loading...</span>
                                                            </div>
                                                        </div>

                                                        <!-- cart and tearsheet buttons -->
                                                        <div class="row">
                                                            {{-- @if (isset($active_theme_json->general->bulk_add_to_cart) && $active_theme_json->general->bulk_add_to_cart) --}}
                                                            <div
                                                                class="login-addtocart-selectcustomer col-12 d-flex flex-wrap">
                                                                <div
                                                                    class="action-item-lg col-md-12 p-2 ps-0 d-none customers-main position-relative item_customer_parent"
                                                                    id="">
                                                                    <div class="d-flex flex-row">
                                                                        <label class="form-label font-crimson me-2"><p
                                                                                class="m-0">Customers</p></label>
                                                                        <div
                                                                            class="ps-0 pe-0 d-flex active_customer_select"
                                                                            id="">
                                                                            <i class="bi bi-info-circle-fill"
                                                                               data-toggle="tooltip" data-html="true"
                                                                               data-placement="left"
                                                                               title="Please select the customer for whom the order needs to be created. Please note once selected than the customer cannot be changed till the order is placed or cart is emptied."></i>
                                                                        </div>
                                                                        <div
                                                                            class="ps-0 pe-0 d-flex disabled_customer_select"
                                                                            id="">
                                                                            <i class="bi bi-info-circle-fill"
                                                                               data-toggle="tooltip" data-html="true"
                                                                               data-placement="left"
                                                                               title="The item will be added in the cart for the selected customer. If the customer needs to be changed then either checkout the order or empty the cart."></i>
                                                                        </div>
                                                                    </div>
                                                                    <select name="customer" class="form-select me-2"
                                                                            aria-label="Default select customer"
                                                                            id="grid_item_customer">
                                                                        <option selected value="0">Select Customer
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                                <div class="cart_main_custom col-12"
                                                                     id="grid_cart_main">
                                                                    @auth()
                                                                    {{-- @if($items['Items'][0]['SpecialBuy'] != "True") --}}
                                                                        @if (strcmp(ConstantsController::USER_ROLES['admin'], Auth::user()->role) !== 0 &&
                                                                                (strcmp(ConstantsController::USER_ROLES['staff'], Auth::user()->role) !== 0 ||
                                                                                    in_array('allow-checkout', Auth::user()->getDataAttribute('permissions', []))))
                                                                            <button type="submit"
                                                                                    class="btn btn-dark add-to-cart d-none mt-4 p-2"
                                                                                    id="add_to_cart">
                                                                                <span
                                                                                    class="label-text font-jaipur">Add to Cart</span>
                                                                                <div class="spinner-border"
                                                                                     role="status"
                                                                                     style="margin: 0 auto;">
                                                                                    <span class="sr-only"
                                                                                          style="opacity:0;">Loading...</span>
                                                                                </div>
                                                                            </button>
                                                                        @endif
                                                                    {{-- @else --}}
                                                                    @if($items['Items'][0]['SpecialBuy'] == "True")
                                                                        <div class="d-flex">
                                                                            <img src="{{ asset('RZY/images/special-buy-30-off.png?v=1') }}" class="img-fluid" style="width: 10%;" />
                                                                            <p class="my-4">Prices shown reflect final discount.</p>
                                                                        </div>
                                                                        {{-- <p>To receive discount pricing, orders can NOT be added to cart. Submit orders for Special buys to:
                                                                            <a href="mailto:orders@rizzyhome.com">orders@rizzyhome.com</a> --}}
                                                                        </p>
                                                                    @endif
                                                                    @endauth
                                                                    @guest()
                                                                        <button type="button" style="width: 100%;"
                                                                                class="log-in-popup-button btn btn-dark d-none font-jaipur p-2"
                                                                                {{-- @if($items['Items'][0]['SpecialBuy'] == "True") invisible @endif" --}}
                                                                                id="login_by_popup">
                                                                            Log in to add to cart and see pricing
                                                                        </button>
                                                                    @if($items['Items'][0]['SpecialBuy'] == "True")
                                                                        <div class="d-flex">
                                                                            <img src="{{ asset('RZY/images/special-buy-30-off.png?v=1') }}" class="img-fluid" style="width: 10%;" />
                                                                            <p class="my-4">Prices shown reflect final discount.</p>
                                                                        </div>
                                                                        {{-- <p>To receive discount pricing, orders can NOT be added to cart. Submit orders for Special buys to:
                                                                            <a href="mailto:orders@rizzyhome.com">orders@rizzyhome.com</a>
                                                                        </p> --}}
                                                                    @endif
                                                                    @endguest
                                                                </div>
                                                            </div>
                                                            {{-- @endif --}}
                                                            {{-- add to card tear sheet Price --}}

                                                            <div
                                                                class="align-items-center d-flex flex-wrap flex-row-reverse justify-content-between mobile-flex-btns">

                                                                <!-- add to cart------old------ -->
                                                                {{-- <div class="d-flex justify-content-end d-none cart_main_custom" id="cart_main">
                                                                    <div class="d-flex flex-row align-items-center">
                                                                        <label class="base_price d-none me-2" style="display: none">Price : </label>
                                                                        <!-- <span class="base_price prefix d-none"> $ </span> -->
                                                                        <span class="base_price d-none" id="base_price" style="display: none"></span>
                                                                    </div>
                                                                    @auth()
                                                                        @if (strcmp(ConstantsController::USER_ROLES['admin'], Auth::user()->role) !== 0 &&
                                                                                (strcmp(ConstantsController::USER_ROLES['staff'], Auth::user()->role) !== 0 ||
                                                                                    in_array('allow-checkout', Auth::user()->getDataAttribute('permissions', []))))
                                                                            <button type="submit" class="btn btn-dark add-to-cart d-none"
                                                                                id="add_to_cart" style="display: none">
                                                                                <span class="label-text">Add to Cart</span>
                                                                                <div class="spinner-border" role="status" style="margin: 0 auto;">
                                                                                    <span class="sr-only" style="opacity:0;">Loading...</span>
                                                                                </div>
                                                                            </button>
                                                                        @endif
                                                                    @endauth
                                                                    @guest()
                                                                        <button type="button" class="log-in-popup-button btn btn-dark d-none"
                                                                            id="login_by_popup">
                                                                            Log In
                                                                        </button>
                                                                    @endguest
                                                                </div> --}}
                                                                {{-- old --}}
                                                                <!-- Download Tearsheet -->
                                                                {{-- <div class="d-flex justify-content-end my-2 col-12">
                                                                    <div class="download-tearsheet-btn col-12">
                                                                        <form
                                                                            action="{{ route('frontend.item.tearsheet') }}"
                                                                            class="col-md-12"
                                                                            enctype="multipart/form-data" method="post">
                                                                            @csrf
                                                                            @php
                                                                                foreach (['Country', 'PileHeight'] as $key) {
                                                                                    if (isset($items['Items'][0][$key]) && $items['Items'][0][$key]) {
                                                                                        $items['Items'][0]['UDFFields'][] = [
                                                                                            'FieldName' => $key,
                                                                                            'Value' => $items['Items'][0][$key],
                                                                                        ];
                                                                                    }
                                                                                }
                                                                            @endphp
                                                                            <input type="hidden"
                                                                                   value="{{ json_encode($items['Items'][0]['UDFFields']) }}"
                                                                                   name="order_detail"
                                                                                   id="order-detail">
                                                                            <input type="hidden"
                                                                                   value="{{ isset($items['Items'][0]['DesignID']) ? $items['Items'][0]['DesignID'] : '' }}"
                                                                                   name="designID" id="designID">
                                                                            <input type="hidden"
                                                                                   value="{{ isset($items['Items'][0]['ImageNameArray'][0]) ? $items['Items'][0]['ImageNameArray'][0] : '' }}"
                                                                                   name="ImageName" id="ImageName">
                                                                            <input type="hidden"
                                                                                   value="{{ json_encode($items['ItemsETA']) }}"
                                                                                   name="sizes"
                                                                                   id="sizes">
                                                                            <button
                                                                                class="btn btn-primary text-uppercase tearsheet-btn col-md-12"
                                                                                style="max-width: 100%"> Download
                                                                                TearSheet
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div> --}}
                                                                {{-- <div class="d-flex justify-content-center my-2"> --}}
                                                                    {{-- <div class="col-lg-6 col-md-12 col-sm-12">
                                                                        <div class="download-tearsheet-btn">
                                                                            <form
                                                                                action="{{ route('frontend.item.tearsheet') }}"
                                                                                class="col-md-12"
                                                                                enctype="multipart/form-data" method="post">
                                                                                @csrf
                                                                                @php
                                                                                    foreach (['Country', 'PileHeight'] as $key) {
                                                                                        if (isset($items['Items'][0][$key]) && $items['Items'][0][$key]) {
                                                                                            $items['Items'][0]['UDFFields'][] = [
                                                                                                'FieldName' => $key,
                                                                                                'Value' => $items['Items'][0][$key],
                                                                                            ];
                                                                                        }
                                                                                    }
                                                                                @endphp
                                                                                <input type="hidden"
                                                                                       value="{{ json_encode($items['Items'][0]['UDFFields']) }}"
                                                                                       name="order_detail"
                                                                                       id="order-detail">
                                                                                <input type="hidden"
                                                                                       value="{{ isset($items['Items'][0]['DesignID']) ? $items['Items'][0]['DesignID'] : '' }}"
                                                                                       name="designID" id="designID">
                                                                                <input type="hidden"
                                                                                       value="{{ isset($items['Items'][0]['ImageNameArray'][0]) ? $items['Items'][0]['ImageNameArray'][0] : '' }}"
                                                                                       name="ImageName" id="ImageName">
                                                                                <input type="hidden"
                                                                                       value="{{ json_encode($items['ItemsETA']) }}"
                                                                                       name="sizes"
                                                                                       id="sizes">
                                                                                <button
                                                                                    class="btn btn-outline-dark font-jaipur text-uppercase tearsheet-btn col-md-12"
                                                                                    style="max-width: 100%"> Download
                                                                                    TearSheet
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </div> --}}
                                                                    <div class="col-lg-6 col-md-12 col-sm-12 item-download-btn invisible">
                                                                        <form
                                                                        action="{{ route('frontend.item.tearsheet') }}"
                                                                        class="col-md-12"
                                                                        enctype="multipart/form-data" method="post">
                                                                        @csrf
                                                                        @php
                                                                            foreach (['Country', 'PileHeight'] as $key) {
                                                                                if (isset($items['Items'][0][$key]) && $items['Items'][0][$key]) {
                                                                                    $items['Items'][0]['UDFFields'][] = [
                                                                                        'FieldName' => $key,
                                                                                        'Value' => $items['Items'][0][$key],
                                                                                    ];
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        <input type="hidden"
                                                                               value="{{ json_encode($items['Items'][0]['UDFFields']) }}"
                                                                               name="order_detail"
                                                                               id="order-detail">
                                                                        <input type="hidden"
                                                                               value="{{ isset($items['Items'][0]['DesignID']) ? $items['Items'][0]['DesignID'] : '' }}"
                                                                               name="designID" id="designID">
                                                                        <input type="hidden"
                                                                               value="{{ isset($items['Items'][0]['ImageNameArray'][0]) ? $items['Items'][0]['ImageNameArray'][0] : '' }}"
                                                                               name="ImageName" id="ImageName">
                                                                        <input type="hidden"
                                                                               value="{{ json_encode($items['ItemsETA']) }}"
                                                                               name="sizes"
                                                                               id="sizes">
                                                                        <button class="btn btn-outline-dark font-jaipur btn-lg  tearsheet-btn"  style="width: 100%">
                                                                            Download TearSheet
                                                                        </button>
                                                                    </form>
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-12 col-sm-12 item-wish-btn invisible">
                                                                        <button class="btn btn-outline-dark btn-lg px-5 my-wish_btn design-add-wishlist"  style="max-width: 100%">
                                                                            <i class="bi {{ $items['Items'][0]['InWishlist'] ? 'bi-suit-heart-fill' : 'bi-suit-heart' }}
                                                                            ">
                                                                            </i><span class="font-jaipur">Add to Wishlist</span>
                                                                        </button>
                                                                    </div>
                                                                {{-- </div> --}}
                                                            </div>
                                                            {{-- FOR MOBILE MODE --}}
                                                           <div class="container">
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                                    <button class="btn btn-info btn-lg px-5 custom-download-btn font-jaipur mobile-tearsheet-btn d-none" style="width:100%; border:1px solid #1f1f1e; background-color:#fff; border-radius:0; font-size:15px; text-transform:uppercase; font-weight:500;">
                                                                        Download TearSheet
                                                                    </button>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                                    <button class="btn btn-info btn-lg px-5 custom-download-btn font-jaipur mobile-design-add-wishlist d-none" style="width:100%; border:1px solid #1f1f1e; background-color:#fff; border-radius:0; font-size:15px; text-transform:uppercase; font-weight:500;">
                                                                        <i class="bi {{ $items['Items'][0]['InWishlist'] ? 'bi-suit-heart-fill' : 'bi-suit-heart' }}
                                                                        mx-2">
                                                                        </i><span class="font-jaipur">Add to Wishlist</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                           </div>
                                                        </div>
                                                    </div>
                                                   
                                                    {{-- Resize Reshape --}}
                                                    @if($items['Items'][0]['ResizeReshape'])
                                                    <div class="tab-pane fade" id="cutSize">
                                                        <form id="product_custom_size" class="product-custim-size"
                                                              id="product-custom-size" method="post"
                                                              action="{{route('frontend.item.CutAndSizeQuote_Price')}}">
                                                            @csrf
                                                            <div class="mb-3 custom-size-width">
                                                                <div class="d-flex align-items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="25"
                                                                         height="25" fill="currentColor"
                                                                         class="bi bi-arrows" viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M1.146 8.354a.5.5 0 0 1 0-.708l2-2a.5.5 0 1 1 .708.708L2.707 7.5h10.586l-1.147-1.146a.5.5 0 0 1 .708-.708l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L13.293 8.5H2.707l1.147 1.146a.5.5 0 0 1-.708.708z"/>
                                                                    </svg>
                                                                    <p class="m-0 ps-2 font-jaipur"><b>width</b></p>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <input type="number" name="width-feet-size"
                                                                               class="form-control text-center custom-input p-2"
                                                                               id="feet_width_cut" placeholder="Feet" pattern="^[0-9]+$"
                                                                               
                                                                               min="0" required>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        {{-- <input type="hidden" name="item-id" id="item_id"
                                                                               value="{{$item['ItemID']}}"> --}}
                                                                        <input type="number" name="width-inch-size"
                                                                               class="form-control text-center custom-input p-2"
                                                                               id="inches_width_cut" placeholder="Inches" pattern="^[0-9]+$"
                                                                               min="0" max="11" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                {{-- <img src="https://rizzyhome.com/media/Full_Images/antant7440009.jpg" width="200"> --}}
                                                                @if (isset($items['Items'][0]['ImageNameArray'][0]))
                                                                    <div class="" id="">
                                                                        <a
                                                                            href="{{ isset($items['Items'][0]['ImageNameArray']) && $items['Items'][0]['ImageNameArray'] ? $items['Items'][0]['ImageNameArray'][0] : url('/') . ConstantsController::IMAGE_PLACEHOLDER }}">
                                                                            <img class="xzoom" id="image_0" width="200"
                                                                                 src="{{ isset($items['Items'][0]['ImageNameArray']) && $items['Items'][0]['ImageNameArray'] ? $items['Items'][0]['ImageNameArray'][0] : url('/') . ConstantsController::IMAGE_PLACEHOLDER }}"
                                                                                 xoriginal="{{ isset($items['Items'][0]['ImageNameArray']) && $items['Items'][0]['ImageNameArray'] ? $items['Items'][0]['ImageNameArray'][0] : url('/') . ConstantsController::IMAGE_PLACEHOLDER }}"
                                                                                 alt="{{ $items['Items'][0]['DesignID'] }}"
                                                                                 onerror="this.onerror=null; this.src='{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}'"/>
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                                @if(!empty($custom_sizes_items))
                                                                    <input type="hidden" name="item_id"
                                                                           id="custom_size_item_id"
                                                                           value="{{ $custom_sizes_items['ItemID'] }}">
                                                                    <input type="hidden" name="price"
                                                                           id="custom_size_item_price"
                                                                           value="{{ $custom_sizes_items['BasePrice'] }}">
                                                                @endif
                                                                <div class="ms-3 custom-size-length">
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                             width="25" height="25" fill="currentColor"
                                                                             class="bi bi-arrows-vertical"
                                                                             viewBox="0 0 16 16">
                                                                            <path
                                                                                d="M8.354 14.854a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 13.293V2.707L6.354 3.854a.5.5 0 1 1-.708-.708l2-2a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 2.707v10.586l1.146-1.147a.5.5 0 0 1 .708.708z"/>
                                                                        </svg>
                                                                        <p class="m-0 pt-1 font-jaipur"><b>Length</b></p>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <input type="number" name="length-feet-size"
                                                                                   class="form-control text-center custom-input p-2"
                                                                                   id="feet_length_cut" placeholder="Feet" pattern="^[0-9]+$"
                                                                                  
                                                                                   min="0" required style="width: 140px">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="number" name="length-inch-size"
                                                                                   class="form-control text-center custom-input p-2"
                                                                                   id="inches_length_cut" pattern="^[0-9]+$"
                                                                                   placeholder="Inches" min="0" max="11"
                                                                                   required style="width: 140px">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex service-type">
                                                                <p class="para-one d-none">Service Type</p>
                                                                <p class="para-two"  id="size_para">
                                                                    <span id="service-type-design">{{ $items['Items'][0]['DesignID'] }}</span> <span class="service-wf">0</span>'<span class="service-wi">0</span>" × <span class="service-lf">0</span>'<span class="service-li">0</span>"</p> 
                                                                 <p id="user_message" class="p-2 d-none" style="color: red"></p>

                                                            </div>
                                                            <div class="m-2">
                                                                <span  class="d-none mt-2 priceItemDiv" style='font-family: "Helvetica", "Open Sans", "Montserrat", "sans-serif" !important'>Price <br><span class="priceItemDiv d-none" style='font-family: "Helvetica", "Open Sans", "Montserrat", "sans-serif" !important'><b class="priceItemDiv d-none" style='font-family: "Helvetica", "Open Sans", "Montserrat", "sans-serif" !important'>$<span id="priceOfItem" style='font-family: "Helvetica", "Open Sans", "Montserrat", "sans-serif" !important'></span></b></span> </span>
                                                            </div>
                                                                <p class="mt-4">For illustration purpose ONLY. Pattern will
                                                                be adjusted to the size and shape prior to production.
                                                                Rugs with borders will also be reflected regardless of
                                                                selected shape.</p>
                                                            <div class="mb-4">
                                                                <p class="m-0">Shape</p>
                                                                <select class="form-select custom-input" name="shape" id="shape"
                                                                        aria-label="Default select example" required>
                                                                    <option disabled selected value="">Select Shape</option>
                                                                    @foreach($shapes as $shape)
                                                                        <option
                                                                            value="{{ $shape['Description'] }}">{{ $shape['Description'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div
                                                                class="action-item-lg col-md-12 p-2 ps-0 d-none customers-main position-relative item_customer_parent_quote"
                                                                id="">
                                                                <div class="d-flex flex-row">
                                                                    <label class="form-label font-crimson me-2"><p
                                                                            class="m-0">Customers</p></label>
                                                                    <div class="ps-0 pe-0 d-flex active_customer_select"
                                                                         id="">
                                                                        <i class="bi bi-info-circle-fill"
                                                                           data-toggle="tooltip" data-html="true"
                                                                           data-placement="left"
                                                                           title="Please select the customer for whom the order needs to be created. Please note once selected than the customer cannot be changed till the order is placed or cart is emptied."></i>
                                                                    </div>
                                                                    <div
                                                                        class="ps-0 pe-0 d-flex disabled_customer_select"
                                                                        id="">
                                                                        <i class="bi bi-info-circle-fill"
                                                                           data-toggle="tooltip" data-html="true"
                                                                           data-placement="left"
                                                                           title="The item will be added in the cart for the selected customer. If the customer needs to be changed then either checkout the order or empty the cart."></i>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" id="cart_count" value="{{ $collectionCount }}">
                                                        <select name="customer" data-required="true"
                                                                        class="form-select me-2"
                                                                        aria-label="Default select customer" required
                                                                        id="grid_item_customer_quote">
                                                                    <option selected value="0">Select Customer</option>
                                                                </select>
                                                            </div>
                                                            @auth
                                                                
                                                            @if (!in_array('.PAChart-Price', $dont_show))
                                                            
                                                            <div class="col-lg-12 col-md-12 col-12">
                                                                <button type="button" class="btn btn-dark mt-4 d-none" id="CutAndSizeQuotePrice" style="width: 100% !important;">
                                                                    <span class="label-text">Get Quote</span>
                                                                </button>
                                                            </div>
                                                            @endif
                                                            @endauth
                                                                <input type="hidden" name="custom-price-value" class="custom-price-value" value="">
                                                                <input type="hidden" name="custom-price-type" class="custom-price-type" value="">
                                                                {{-- <div class="d-flex justify-content-between d-none" id="main-regular-service-box">
                                                                    <div class="d-flex">
                                                                        <input type="radio" name="service-price" class="form-check-input mx-3" id="regular-service-input" value="regular">
                                                                        <div class="d-flex flex-column" id="regular-service-text">
                                                                            <p class="para-one font-jaipur">REGULAR SERVICE</p>
                                                                            <p class="para-two font-jaipur">Est. Delivery {{ isset($custom_sizes_items['RegularDeliveryTime']) ? $custom_sizes_items['RegularDeliveryTime'] : '0' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex flex-column" id="regular-service-price">
                                                                        <p class="para-one font-jaipur">Per Unit: <span class="mx-1 regular-service-per-unit">$0</span></p>
                                                                        <p class="para-two font-jaipur mx-4">Total: <span class="mx-1 regular-service-total">$0</span></p>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-between d-none" id="main-express-service-box">
                                                                    <div class="d-flex">
                                                                        <input type="radio" name="service-price" class="form-check-input mx-3" id="express-service-input" value="express">
                                                                        <div class="d-flex flex-column" id="express-service-text">
                                                                            <p class="para-one font-jaipur">EXPRESS SERVICE</p>
                                                                            <p class="para-two font-jaipur">Est. Delivery {{ isset($custom_sizes_items['ExpressDeliveryTime']) ? $custom_sizes_items['ExpressDeliveryTime'] : '0' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex flex-column" id="express-service-price">
                                                                        <p class="para-one font-jaipur">Per Unit: <span class="mx-1 express-service-per-unit">$0</span></p>
                                                                        <p class="para-two font-jaipur mx-4">Total: <span class="mx-1 express-service-total">$0</span></p>
                                                                    </div>
                                                                </div> --}}
                                                                <div class="">
                                                                   <div>
                                                                   <input type="hidden" name="cust_rug_item_id" id="cust_rug_item_id" value="">
                                                                </div>
                                                                 </div>
                                                                 {{-- rugpads ss --}}
                                                                {{-- <div class="mt-4" id="recommended-rugs2">
                                                                    @if(
                                                                        (isset($custom_sizes_items['PrePad']) && !empty($custom_sizes_items['PrePad']) && $custom_sizes_items['PrePad'] != "NULL") ||
                                                                        ( isset($custom_sizes_items['ULTPad']) && !empty($custom_sizes_items['ULTPad']) && $custom_sizes_items['ULTPad'] != "NULL")
                                                                        )
                                                                        <p class="m-0" class="font-jaipur">Recommended Rug Pads</p>
                                                                    @endif
                                                                    <div class="recc-rug-pad mt-2 mb-4" id="rugs-items-bk">
                                                                        @if(isset($custom_sizes_items['PrePad']) && !empty($custom_sizes_items['PrePad']) && $custom_sizes_items['PrePad'] != "NULL")
                                                                        <div class="form-check form-check-inline recommended-rug" data-id="CUSTPREPAD">
                                                                            <input class="form-check-input checkbox-rugs PREPAD" type="radio" name="rugsPad" id="CUSTPREPAD" value="{{ $custom_sizes_items['PrePad'] }}:PREPAD">
                                                                            <div class="d-flex">
                                                                                <img src="{{ $recommended_rugs['PREPAD']['Items'][0]['ImageName'] }}" width="50" />
                                                                                <div style="display:flex; flex-direction:column;">
                                                                                    <span class="form-check-span ms-2 font-jaipur" for="PREPAD" style="margin-right:50px; font-size:0.7rem;"><strong>Premium Rug Pad</strong></span>
                                                                                    <span class="form-check-span ms-2 font-jaipur" for="PREPAD" style="margin-right:50px; font-size:0.7rem;"><strong class="padcust-dimension"><span class="service-wf">0</span>'<span class="service-wi">0</span>" × <span class="service-lf">0</span>'<span class="service-li">0</span></strong></span>
                                                                                  @auth
                                                                                      
                                                                                   @if (!in_array('.PAChart-Price', $dont_show)) <span class="form-check-span ms-2 font-jaipur" for="PREPAD" style="margin-right:50px; font-size:0.7rem;"><strong class="pad-price-custpre"></strong></span>@endif @endauth 
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @endif
                                                                        @if(isset($custom_sizes_items['ULTPad']) && !empty($custom_sizes_items['ULTPad']) && $custom_sizes_items['ULTPad'] != "NULL")
                                                                        <div class="form-check form-check-inline recommended-rug" data-id="CUSTULTPAD">
                                                                            <input class="form-check-input checkbox-rugs ULTPAD" type="radio" name="rugsPad" id="CUSTULTPAD" value="{{ $custom_sizes_items['ULTPad'] }}:ULTPAD">
                                                                           <div class="d-flex">
                                                                            <img src="{{ $recommended_rugs['ULTPAD']['Items'][0]['ImageName'] }}" width="50" />
                                                                                <div style="display:flex; flex-direction:column;">
                                                                                    <span class="form-check-span ms-2 font-jaipur" for="ULTPAD" style="font-size:0.7rem;"><strong>Ultra Rug Pad</strong></span>
                                                                                    <span class="form-check-span ms-2 font-jaipur" for="ULTPAD" style="font-size:0.7rem;"><strong class="ultcust-dimension"><span class="service-wf">0</span>'<span class="service-wi">0</span>" × <span class="service-lf">0</span>'<span class="service-li">0</span></strong></span>
                                                                                  @auth
                                                                                      
                                                                                   @if (!in_array('.PAChart-Price', $dont_show))   <span class="form-check-span ms-2 font-jaipur" for="ULTPAD" style="font-size:0.7rem;"><strong class="pad-price-custult"></strong></span> @endif @endauth 
                                                                                </div>
                                                                           </div>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                </div> --}}
                                                            <div class="cart_main_custom col-12" id="grid_custom_size">
                                                                @auth()
                                                                    @if (strcmp(ConstantsController::USER_ROLES['admin'], Auth::user()->role) !== 0 &&
                                                                            (strcmp(ConstantsController::USER_ROLES['staff'], Auth::user()->role) !== 0 ||
                                                                                in_array('allow-checkout', Auth::user()->getDataAttribute('permissions', []))))
                                                                        <button type="button"
                                                                                class="btn btn-dark add-to-cart d-none mt-4"
                                                                                id="get_quote_btn_placeOrder">
                                                                            <span class="label-text">Place Order</span>
                                                                            <div class="spinner-border" role="status"
                                                                                 style="margin: 0 auto;">
                                                                                <span class="sr-only"
                                                                                      style="opacity:0;">Loading...</span>
                                                                            </div>
                                                                        </button>
                                                                    @endif
                                                                @endauth
                                                                @guest()
                                                                    <button type="button" style="width: 100%;"
                                                                            class="log-in-popup-button btn font-jaipur btn-dark text-uppercase text-white tearsheet-btn col-md-12 d-none"
                                                                            id="login_by_popup_quote">
                                                                        Log in to Add To Cart
                                                                    </button>
                                                                @endguest
                                                            </div>
                                                        </form>
                                                    </div>
                                                    @endif
                                                    {{-- custom size --}}
                                                    <div class="tab-pane fade" id="customSize">
                                                        <form id="product_custom_size" class="product-custim-size"
                                                              id="product-custom-size" method="post"
                                                              action="{{route('form.submission', ['reugs_custon_size'])}}">
                                                            @csrf
                                                            <div class="mb-3 custom-size-width">
                                                                <div class="d-flex align-items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="25"
                                                                         height="25" fill="currentColor"
                                                                         class="bi bi-arrows" viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M1.146 8.354a.5.5 0 0 1 0-.708l2-2a.5.5 0 1 1 .708.708L2.707 7.5h10.586l-1.147-1.146a.5.5 0 0 1 .708-.708l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L13.293 8.5H2.707l1.147 1.146a.5.5 0 0 1-.708.708z"/>
                                                                    </svg>
                                                                    <p class="m-0 ps-2 font-jaipur"><b>width</b></p>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <input type="number" name="width-feet-size"
                                                                               class="form-control text-center custom-input p-2"
                                                                               id="feet_width" placeholder="Feet" pattern="^[0-9]+$"
                                                                               max="{{ !empty($item['Width']) ? floor($item['Width']) : 1 }}"
                                                                               min="0" required>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input type="hidden" name="item-id" id="item_id"
                                                                               value="{{$item['ItemID']}}">
                                                                        <input type="number" name="width-inch-size"
                                                                               class="form-control text-center custom-input p-2"
                                                                               id="inches_width" placeholder="Inches" pattern="^[0-9]+$"
                                                                               min="0" max="11" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                {{-- <img src="https://rizzyhome.com/media/Full_Images/antant7440009.jpg" width="200"> --}}
                                                                @if (isset($items['Items'][0]['ImageNameArray'][0]))
                                                                    <div class="" id="">
                                                                        <a
                                                                            href="{{ isset($items['Items'][0]['ImageNameArray']) && $items['Items'][0]['ImageNameArray'] ? $items['Items'][0]['ImageNameArray'][0] : url('/') . ConstantsController::IMAGE_PLACEHOLDER }}">
                                                                            <img class="xzoom" id="image_0" width="200"
                                                                                 src="{{ isset($items['Items'][0]['ImageNameArray']) && $items['Items'][0]['ImageNameArray'] ? $items['Items'][0]['ImageNameArray'][0] : url('/') . ConstantsController::IMAGE_PLACEHOLDER }}"
                                                                                 xoriginal="{{ isset($items['Items'][0]['ImageNameArray']) && $items['Items'][0]['ImageNameArray'] ? $items['Items'][0]['ImageNameArray'][0] : url('/') . ConstantsController::IMAGE_PLACEHOLDER }}"
                                                                                 alt="{{ $items['Items'][0]['DesignID'] }}"
                                                                                 onerror="this.onerror=null; this.src='{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}'"/>
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                                @if(!empty($custom_sizes_items))
                                                                    <input type="hidden" name="item_id"
                                                                           id="custom_size_item_id"
                                                                           value="{{ $custom_sizes_items['ItemID'] }}">
                                                                    <input type="hidden" name="price"
                                                                           id="custom_size_item_price"
                                                                           value="{{ $custom_sizes_items['BasePrice'] }}">
                                                                @endif
                                                                <div class="ms-3 custom-size-length">
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                             width="25" height="25" fill="currentColor"
                                                                             class="bi bi-arrows-vertical"
                                                                             viewBox="0 0 16 16">
                                                                            <path
                                                                                d="M8.354 14.854a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 13.293V2.707L6.354 3.854a.5.5 0 1 1-.708-.708l2-2a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 2.707v10.586l1.146-1.147a.5.5 0 0 1 .708.708z"/>
                                                                        </svg>
                                                                        <p class="m-0 pt-1 font-jaipur"><b>Length</b></p>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <input type="number" name="length-feet-size"
                                                                                   class="form-control text-center custom-input p-2"
                                                                                   id="feet_length" placeholder="Feet" pattern="^[0-9]+$"
                                                                                   max="{{ !empty($item['Length']) ? floor($item['Length']) : 1 }}"
                                                                                   min="0" required style="width: 140px">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="number" name="length-inch-size"
                                                                                   class="form-control text-center custom-input p-2"
                                                                                   id="inches_length" pattern="^[0-9]+$"
                                                                                   placeholder="Inches" min="0" max="11"
                                                                                   required style="width: 140px">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex service-type">
                                                                <p class="para-one d-none">Service Type</p>
                                                                <p class="para-two"><span id="service-type-design mx-5">{{ $items['Items'][0]['DesignID'] }}</span> <span class="service-wf">0</span>'<span class="service-wi">0</span>" × <span class="service-lf">0</span>'<span class="service-li">0</span>"</p>
                                                            </div>
                                                            <p class="mt-4">For illustration purpose ONLY. Pattern will
                                                                be adjusted to the size and shape prior to production.
                                                                Rugs with borders will also be reflected regardless of
                                                                selected shape.</p>
                                                            <div class="mb-4">
                                                                <p class="m-0">Shape</p>
                                                                <select class="form-select custom-input" name="shape" id="shape"
                                                                        aria-label="Default select example" required>
                                                                    <option disabled selected value="">Select Shape</option>
                                                                    @foreach($shapes as $shape)
                                                                        <option
                                                                            value="{{ $shape['Description'] }}">{{ $shape['Description'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div
                                                                class="action-item-lg col-md-12 p-2 ps-0 d-none customers-main position-relative item_customer_parent_quote"
                                                                id="">
                                                                <div class="d-flex flex-row">
                                                                    <label class="form-label font-crimson me-2"><p
                                                                            class="m-0">Customers</p></label>
                                                                    <div class="ps-0 pe-0 d-flex active_customer_select"
                                                                         id="">
                                                                        <i class="bi bi-info-circle-fill"
                                                                           data-toggle="tooltip" data-html="true"
                                                                           data-placement="left"
                                                                           title="Please select the customer for whom the order needs to be created. Please note once selected than the customer cannot be changed till the order is placed or cart is emptied."></i>
                                                                    </div>
                                                                    <div
                                                                        class="ps-0 pe-0 d-flex disabled_customer_select"
                                                                        id="">
                                                                        <i class="bi bi-info-circle-fill"
                                                                           data-toggle="tooltip" data-html="true"
                                                                           data-placement="left"
                                                                           title="The item will be added in the cart for the selected customer. If the customer needs to be changed then either checkout the order or empty the cart."></i>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" id="cart_count" value="{{ $collectionCount }}">
                                                        <select name="customer" data-required="true"
                                                                        class="form-select me-2"
                                                                        aria-label="Default select customer" required
                                                                        id="grid_item_customer_quote">
                                                                    <option selected value="0">Select Customer</option>
                                                                </select>
                                                            </div>
                                                            @auth
                                                                
                                                            @if (!in_array('.PAChart-Price', $dont_show))
                                                            
                                                            <div class="col-lg-12 col-md-12 col-12">
                                                                <button type="button" class="btn btn-dark mt-4 d-none" id="get-quote-price-btn" style="width: 100% !important;">
                                                                    <span class="label-text">Get Quote</span>
                                                                </button>
                                                            </div>
                                                            @endif
                                                            @endauth
                                                                <input type="hidden" name="custom-price-value" class="custom-price-value" value="">
                                                                <input type="hidden" name="custom-price-type" class="custom-price-type" value="">
                                                                <div class="d-flex justify-content-between d-none" id="main-regular-service-box">
                                                                    <div class="d-flex">
                                                                        <input type="radio" name="service-price" class="form-check-input mx-3" id="regular-service-input" value="regular">
                                                                        <div class="d-flex flex-column" id="regular-service-text">
                                                                            <p class="para-one font-jaipur">REGULAR SERVICE</p>
                                                                            <p class="para-two font-jaipur">Est. Delivery {{ isset($custom_sizes_items['RegularDeliveryTime']) ? $custom_sizes_items['RegularDeliveryTime'] : '0' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex flex-column" id="regular-service-price">
                                                                        <p class="para-one font-jaipur">Per Unit: <span class="mx-1 regular-service-per-unit">$0</span></p>
                                                                        <p class="para-two font-jaipur mx-4">Total: <span class="mx-1 regular-service-total">$0</span></p>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-between d-none" id="main-express-service-box">
                                                                    <div class="d-flex">
                                                                        <input type="radio" name="service-price" class="form-check-input mx-3" id="express-service-input" value="express">
                                                                        <div class="d-flex flex-column" id="express-service-text">
                                                                            <p class="para-one font-jaipur">EXPRESS SERVICE</p>
                                                                            <p class="para-two font-jaipur">Est. Delivery {{ isset($custom_sizes_items['ExpressDeliveryTime']) ? $custom_sizes_items['ExpressDeliveryTime'] : '0' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex flex-column" id="express-service-price">
                                                                        <p class="para-one font-jaipur">Per Unit: <span class="mx-1 express-service-per-unit">$0</span></p>
                                                                        <p class="para-two font-jaipur mx-4">Total: <span class="mx-1 express-service-total">$0</span></p>
                                                                    </div>
                                                                </div>
                                                                 {{-- rugpads ss --}}
                                                                 
                                                                <div class="mt-4" id="recommended-rugs2">
                                                                    @if(
                                                                        (isset($custom_sizes_items['PrePad']) && !empty($custom_sizes_items['PrePad']) && $custom_sizes_items['PrePad'] != "NULL") ||
                                                                        ( isset($custom_sizes_items['ULTPad']) && !empty($custom_sizes_items['ULTPad']) && $custom_sizes_items['ULTPad'] != "NULL")
                                                                        )
                                                                        <p class="m-0" class="font-jaipur">Recommended Rug Pads</p>
                                                                    @endif
                                                                    <div class="recc-rug-pad mt-2 mb-4" id="rugs-items-bk">
                                                                        @if(isset($custom_sizes_items['PrePad']) && !empty($custom_sizes_items['PrePad']) && $custom_sizes_items['PrePad'] != "NULL")
                                                                        <div class="form-check form-check-inline recommended-rug" data-id="CUSTPREPAD">
                                                                            <input class="form-check-input checkbox-rugs PREPAD" type="radio" name="rugsPad" id="CUSTPREPAD" value="{{ $custom_sizes_items['PrePad'] }}:PREPAD">
                                                                            <div class="d-flex">
                                                                                <img src="{{ $recommended_rugs['PREPAD']['Items'][0]['ImageName'] }}" width="50" />
                                                                                <div style="display:flex; flex-direction:column;">
                                                                                    <span class="form-check-span ms-2 font-jaipur" for="PREPAD" style="margin-right:50px; font-size:0.7rem;"><strong>Premium Rug Pad</strong></span>
                                                                                    <span class="form-check-span ms-2 font-jaipur" for="PREPAD" style="margin-right:50px; font-size:0.7rem;"><strong class="padcust-dimension"><span class="service-wf">0</span>'<span class="service-wi">0</span>" × <span class="service-lf">0</span>'<span class="service-li">0</span></strong></span>
                                                                                  @auth
                                                                                      
                                                                                   @if (!in_array('.PAChart-Price', $dont_show)) <span class="form-check-span ms-2 font-jaipur" for="PREPAD" style="margin-right:50px; font-size:0.7rem;"><strong class="pad-price-custpre"></strong></span>@endif @endauth 
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @endif
                                                                        @if(isset($custom_sizes_items['ULTPad']) && !empty($custom_sizes_items['ULTPad']) && $custom_sizes_items['ULTPad'] != "NULL")
                                                                        <div class="form-check form-check-inline recommended-rug" data-id="CUSTULTPAD">
                                                                            <input class="form-check-input checkbox-rugs ULTPAD" type="radio" name="rugsPad" id="CUSTULTPAD" value="{{ $custom_sizes_items['ULTPad'] }}:ULTPAD">
                                                                           <div class="d-flex">
                                                                            <img src="{{ $recommended_rugs['ULTPAD']['Items'][0]['ImageName'] }}" width="50" />
                                                                                <div style="display:flex; flex-direction:column;">
                                                                                    <span class="form-check-span ms-2 font-jaipur" for="ULTPAD" style="font-size:0.7rem;"><strong>Ultra Rug Pad</strong></span>
                                                                                    <span class="form-check-span ms-2 font-jaipur" for="ULTPAD" style="font-size:0.7rem;"><strong class="ultcust-dimension"><span class="service-wf">0</span>'<span class="service-wi">0</span>" × <span class="service-lf">0</span>'<span class="service-li">0</span></strong></span>
                                                                                  @auth
                                                                                      
                                                                                   @if (!in_array('.PAChart-Price', $dont_show))   <span class="form-check-span ms-2 font-jaipur" for="ULTPAD" style="font-size:0.7rem;"><strong class="pad-price-custult"></strong></span> @endif @endauth 
                                                                                </div>
                                                                           </div>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            <div class="cart_main_custom col-12" id="grid_custom_size">
                                                                @auth()
                                                                    @if (strcmp(ConstantsController::USER_ROLES['admin'], Auth::user()->role) !== 0 &&
                                                                            (strcmp(ConstantsController::USER_ROLES['staff'], Auth::user()->role) !== 0 ||
                                                                                in_array('allow-checkout', Auth::user()->getDataAttribute('permissions', []))))
                                                                        <button type="button"
                                                                                class="btn btn-dark add-to-cart d-none mt-4"
                                                                                id="get_quote_btn">
                                                                            <span class="label-text">Place Order</span>
                                                                            <div class="spinner-border" role="status"
                                                                                 style="margin: 0 auto;">
                                                                                <span class="sr-only"
                                                                                      style="opacity:0;">Loading...</span>
                                                                            </div>
                                                                        </button>
                                                                    @endif
                                                                @endauth
                                                                @guest()
                                                                    <button type="button" style="width: 100%;"
                                                                            class="log-in-popup-button btn font-jaipur btn-dark text-uppercase text-white tearsheet-btn col-md-12 d-none"
                                                                            id="login_by_popup_quote">
                                                                        Log in to Add To Cart
                                                                    </button>
                                                                @endguest
                                                            </div>
                                                        </form>
                                                    </div>
                                        </div>
                                         {{-- new accordians --}}
                                         <div class="my-2 col-12">
                                            <div class="product-accordions accordion" id="productAccordions">
                                                {{-- product DETAILS --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="productDetails">
                                                        <button class="accordion-button detail-accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#productDetailsCollapse"
                                                                aria-expanded="false" aria-controls="productDetailsCollapse">
                                                        <span class="row" style="width: 100%">
                                                            <span class="col-8 font-jaipur" style="font-size: 0.8rem; font-weight:550 !important;">
                                                                PRODUCT DETAILS
                                                            </span>
                                                            <span class="col-4" style="text-align: right">

                                                            </span>
                                                        </span>
                                                        </button>
                                                    </h2>
                                                    <div id="productDetailsCollapse" class="accordion-collapse collapse"
                                                        aria-labelledby="productDetails" data-bs-parent="#productAccordions">
                                                        <div class="accordion-body">
                                                            <div>
                                                                <p class="font-jaipur mt-2 mb-3" style="font-size: 0.8rem; color:#656565;">{{ $items['Items'][0]['ProductDescription'] }}</p>
                                                            </div>
                                                            <div class="col-md-12 d-flex flex-wrap flex item-udf-fields"
                                                                id="item-udf-fields">
                                                                @if(empty($items['Items'][0]['GroupPricing']))
                                                                    <p class="specs mb-2 UDField col-md-6">
                                                                        <strong class="col-md-3 FieldName font-jaipur" style="font-size: 13px"> Group Price :</strong>
                                                                        <span
                                                                            class="col-md-9 FieldValue font-jaipur" style="font-size: 13px"> {{ $items['Items'][0]['GroupPricing'] }} </span>
                                                                    </p>
                                                                @endif
                                                                @foreach ($items['Items'][0]['UDFFields'] as $field)
                                                                @if (!in_array($field['FieldName'], ["Construction", "Color(s)", "Material", "Weaving", "PileHeight"]))
                                                                    @if ($field['FieldName'] == 'Size')
                                                                        @continue
                                                                    @endif
                                                                    <p class="specs mb-2 UDField col-md-12 row font-jaipur" style="font-size:13px">
                                                                        <strong
                                                                            class=" col-md-6 text-nowrap FieldName font-jaipur" style="font-size:13px"> {{ $field['FieldName'] }}
                                                                            :</strong>
                                                                    @if ($field['FieldName'] == 'Feature(s)')
                                                                        <ul class="FieldValue col-md-6 feature_bullets ps-3 font-jaipur" style="font-size:13px">
                                                                            @foreach (explode(',', $field['Value']) as $feature)
                                                                                <li class="font-jaipur" style="font-size:13px">{{ trim($feature) }}</li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @elseif ($field['FieldName'] == 'Feature 1')
                                                                        <ul class=" col-md-6 FieldValue feature_bullets ps-3 font-jaipur" style="font-size:13px">
                                                                            @foreach (explode(',', $field['Value']) as $feature)
                                                                                <li class="font-jaipur" style="font-size:13px">{{ trim($feature) }}</li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @else
                                                                        <span
                                                                            class="col-md-6 FieldValue font-jaipur" style="font-size:13px"> {{ $field['Value'] }} </span>
                                                                    @endif
                                                                    </p>
                                                                @endif
                                                                @endforeach
                                                                {{-- @foreach (['Country', 'PileHeight'] as $key)
                                                                    @if (isset($items['Items'][0][$key]) && $items['Items'][0][$key])
                                                                        <p class="specs mb-2 UDField col-md-6">
                                                                            <strong class="col-md-6 FieldName font-jaipur" style="font-size:13px"> {{ $key }}
                                                                                :</strong>
                                                                            <span
                                                                                class="col-md-6 FieldValue font-jaipur" style="font-size:13px"> {{ $items['Items'][0][$key] }} </span>
                                                                        </p>
                                                                    @endif
                                                                @endforeach --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- shipping INFO --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="shippingInfo">
                                                        <button class="accordion-button detail-accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#shippingInfoCollapse"
                                                                aria-expanded="false" aria-controls="shippingInfoCollapse">
                                                        <span class="row" style="width: 100%">
                                                            <span class="col-8 font-jaipur" style="font-size: 0.8rem; font-weight:550 !important;">
                                                                SHIPPING INFO
                                                            </span>
                                                            <span class="col-4" style="text-align: right">

                                                                </span>
                                                            </span>
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="shippingInfoCollapse" class="accordion-collapse collapse"
                                                        aria-labelledby="shippingInfo" data-bs-parent="#productAccordions">
                                                        <div class="accordion-body">
                                                            @if (isset($items['ItemsETA']) && $items['ItemsETA'])
                                                                <div class="">
                                                                    <table class="table table-bordered align-middle">
                                                                        <thead>
                                                                        <tr class="table-secondary">
                                                                            <td scope="col" style="background: #F8F6F3">Product ID
                                                                            </td>
                                                                            <td scope="col" style="background: #F8F6F3">Size</td>
                                                                            <td scope="col" style="background: #F8F6F3">Weight</td>
                                                                            <td scope="col" style="background: #F8F6F3">Package
                                                                                Dimensions
                                                                            </td>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach ($items['ItemsETA'] as $itemETA)
                                                                            @if($itemETA['SizeID'] != "CUST")
                                                                                <tr>
                                                                                    <td>{{ $itemETA['ItemID'] }}</td>
                                                                                    <td>{{ $itemETA['Size'] }}</td>
                                                                                    <td>{{ $itemETA['DimentionalWeight'] }}</td>
                                                                                    <td>{{ $itemETA['ShippingDimension'] }}</td>
                                                                                </tr>
                                                                            @endif
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Care and instruction --}}
                                                @if(!empty($items['Items'][0]['CareInstructions']))
                                                    <div class="product-accordions accordion" id="productAccordions_bottom">
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="careIns">
                                                                <button class="accordion-button detail-accordion-button collapsed" type="button"
                                                                        data-bs-toggle="collapse" data-bs-target="#careInsCollapse"
                                                                        aria-expanded="false" aria-controls="scareInsCollapse">
                                                                    <span class="row" style="width: 100%">
                                                                        <span class="col-8 font-jaipur" style="font-size: 0.8rem; font-weight:550 !important;">
                                                                            CARE INSTRUCTIONS
                                                                        </span>
                                                                        <span class="col-4 font-jaipur" style="text-align: right">

                                                                        </span>
                                                                    </span>
                                                                </button>
                                                            </h2>

                                                            <div id="careInsCollapse" class="accordion-collapse collapse"
                                                                aria-labelledby="careIns"
                                                                data-bs-parent="#productAccordions_bottom">
                                                                <div class="accordion-body font-jaipur" style="font-size: 0.8rem; color:#656565;">
                                                                    {{$items['Items'][0]['CareInstructions']}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>


                                    {{-- CARE INSTRUCTION --}}
                                    {{-- @if(!empty($items['Items'][0]['CareInstructions']))
                                        <div class="product-accordions accordion" id="productAccordions_bottom">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="careIns">
                                                    <button class="accordion-button detail-accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#careInsCollapse"
                                                            aria-expanded="false" aria-controls="scareInsCollapse">
                                                        <span class="row" style="width: 100%">
                                                            <span class="col-8 font-jaipur" style="font-size: 0.8rem; font-weight:550 !important;">
                                                                CARE INSTRUCTIONS
                                                            </span>
                                                            <span class="col-4 font-jaipur" style="text-align: right">

                                                            </span>
                                                        </span>
                                                    </button>
                                                </h2>

                                                <div id="careInsCollapse" class="accordion-collapse collapse"
                                                     aria-labelledby="careIns"
                                                     data-bs-parent="#productAccordions_bottom">
                                                    <div class="accordion-body font-jaipur" style="font-size: 0.8rem; color:#656565;">
                                                        {{$items['Items'][0]['CareInstructions']}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif --}}
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center product_chart_model">
                                        <img src="" alt="" id="image-preview" style="width: 100%">
                                        {{-- <iframe src="https://www.youtube.com/embed/R1D_w2Ifyuw&ab_channel=RbnWebSolutions" width="300" height="300" frameborder="0" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen allow="autoplay"></iframe> --}}
                                        <video id="video-preview" width="300" height="300" controls autoplay loop>
                                            <source src="" type="video/mp4" id="video-src" frameborder="0" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen allow="autoplay">
                                        </video>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
            </section>
            @if (
                    isset($rug_pads) &&
                    !empty($rug_pads) &&
                    isset($rug_pads['Designs']) &&
                    count($rug_pads['Designs']) &&
                    isset($main_collection['Description']) &&
                    strtolower($main_collection['Description']) == 'rugs' &&
                    isset($items['Items'][0]['HotBuy']) &&
                    !CommonController::check_bit_field($items['Items'][0], 'HotBuy') &&
                    isset($items['Items'][0]['RugPad']) &&
                    !CommonController::check_bit_field($items['Items'][0], 'RugPad')
                )
                {{-- frequently bought Together --}}
                <section class="freq-bgt-together d-none">
                    <div class="container">
                        <h1 class="section-title text-center mb-5 font-ropa">Frequently Bought Together</h1>
                        <div class="col-md-12">
                            <ul class="owl-carousel owl-carousel-fbt owl-products d-flex justify-content-center align-items-center">
                                @if (!empty($rug_pads))
                                    @foreach ($rug_pads['Designs'] as $design)
                                        <li class="slider-item">
                                            <a href="{{ $design['LinkUrl'] }}"
                                               class="d-flex flex-column text-decoration-none">
                                                <figure class="align-items-center d-flex m-0 overflow-hidden"
                                                        style="height: 300px;">
                                                    <img
                                                        src="{{ CommonController::getApiFullImage($design['ImageName']) }}"
                                                        onerror="this.onerror=null; this.src='{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}'"/>
                                                    @php
                                                        $badges = [
                                                            [
                                                                'condition' => $design['SpecialBuy'],
                                                                'background' => 'special-buy',
                                                                'label' => 'Special Buy',
                                                            ],
                                                            [
                                                                'condition' => $design['Clearence'],
                                                                'background' => 'clearance',
                                                                'label' => 'Clearence',
                                                            ],
                                                            [
                                                                'condition' => $design['NewArrivalExpiry'],
                                                                'background' => 'new-arrival',
                                                                'label' => 'New Arrival',
                                                            ],
                                                            [
                                                                'condition' => $design['TopSeller'],
                                                                'background' => 'top-seller',
                                                                'label' => 'Top Seller',
                                                            ],
                                                            [
                                                                'condition' => $design['HotBuy'],
                                                                'background' => 'hot-buy',
                                                                'label' => 'Hot Buy',
                                                            ],
                                                        ];
                                                        $count = 0;
                                                        foreach ($badges as $badge) {
                                                            if (strtolower($badge['condition']) != 'false' && strtolower($badge['condition']) !== '') {
                                                                echo '<div style="background: url(/RZY/images/labels/' . $badge['background'] . '.png)" class="position-absolute handles-position"></div>';
                                                            }
                                                        }
                                                    @endphp
                                                </figure>
                                                <span class="product-lable">{{ $design['DesignID'] }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </section>
            @endif
            {{-- our more products section --}}
            <section class="our-productss">
                @if (isset($related_designs) &&
                        !empty($related_designs) &&
                        isset($related_designs['Designs']) &&
                        count($related_designs['Designs']) &&
                        isset($items['Items'][0]['HotBuy']) &&
                        !CommonController::check_bit_field($items['Items'][0], 'HotBuy'))
                    <div class="container">
                        <h1 class="section-title text-center mb-5 font-jaipur our-product-margin-item"> Our Products</h1>
                        <div class="col-md-12">
                            <ul class="owl-carousel owl-carousel-products owl-products">
                                @if (!empty($related_designs))
                                    @foreach ($related_designs['Designs'] as $design)
                                        <li class="slider-item">
                                            <a href="{{ $design['LinkUrl'] }}"
                                               class="d-flex flex-column text-decoration-none">
                                                <figure class="align-items-center d-flex m-0 overflow-hidden"
                                                        style="height: 400px;">
                                                    <img
                                                        src="{{ CommonController::getApiFullImage($design['ImageName']) }}"
                                                        onerror="this.onerror=null; this.src='{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}'"/>
                                                    @php
                                                        $badges = [
                                                            [
                                                                'condition' => $design['SpecialBuy'],
                                                                'background' => 'special-buy',
                                                                'label' => 'Special Buy',
                                                            ],
                                                            [
                                                                'condition' => $design['Clearence'],
                                                                'background' => 'clearance',
                                                                'label' => 'Clearence',
                                                            ],
                                                            [
                                                                'condition' => $design['NewArrivalExpiry'],
                                                                'background' => 'new-arrival',
                                                                'label' => 'New Arrival',
                                                            ],
                                                            [
                                                                'condition' => $design['TopSeller'],
                                                                'background' => 'top-seller',
                                                                'label' => 'Top Seller',
                                                            ],
                                                            [
                                                                'condition' => $design['HotBuy'],
                                                                'background' => 'hot-buy',
                                                                'label' => 'Hot Buy',
                                                            ],
                                                        ];
                                                        $count = 0;
                                                        foreach ($badges as $badge) {
                                                            if (strtolower($badge['condition']) != 'false' && strtolower($badge['condition']) !== '') {
                                                                echo '<div style="background: url(/RZY/images/labels/' . $badge['background'] . '.png)" class="position-absolute handles-position"></div>';
                                                            }
                                                        }
                                                    @endphp
                                                </figure>
                                                <span class="product-lable">{{ $design['CollectionID'] }} -
                                                    {{ $design['DesignID'] }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                @endif
            </section>
        </main>
        @include('frontend.' . $active_theme->theme_abrv . '.components.footer')
    </div>
    @if (isset($item['VideoURL']) && $item['VideoURL'])
        <div class="video-player" style="display: none; z-index: 9999 !important;">
            <div class="player-wrapper">
                <div class="player">
                    <a href="javascript:void(0)" onclick="closePlayer()" class="close-player">&times;</a>
                    @php
                        $item['VideoURL'] = str_replace('dl=0', 'raw=1', $item['VideoURL']);
                    @endphp
                    <iframe id="video-iframe" src="{{ $item['VideoURL'] }}" height="700px" width="1200" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    @endif
    @include('frontend.' . $active_theme->theme_abrv . '.components.login-modal')

    {{-- FULL IMG SLIDER MODAL START --}}
    <div class="big-img-modal" style="display: none; z-index: 9999 !important;">
        <div class="big-img-wrapper">
            <div class="player">
                <a href="javascript:void(0)" onclick="closePlayer()" class="close-img-btn"><img src="{{ asset('RZY/images/cross.png') }}"></a>
                <div class="container" style="width:100% !important; height:100% !importnat;">
                    <div id="viewer">
                        <button type="button" class="arrow arrow-left"></button>
                        <img id="currentImage" src="" alt="Image Viewer" class="">
                        <iframe id="currentVideo" src="{{ $item['VideoURL'] }}" height="800px" width="500" allowfullscreen class="d-none"></iframe>
                        <button class="arrow arrow-right"></button>
                    </div>
                    <div class="slider">
                        @foreach ($items_images as $key => $image)
                        <img src="{{$image}}" alt="product image">
                        @endforeach
                        @if (isset($item['VideoURL']) && $item['VideoURL'])
                            <div class="video-thumbnail" onclick="videoPlay()">
                                <img id="vid-icon" src="{{ asset('images/video-play.png') }}" alt="">
                            </div>
                        @endif
                    </div>

                    <div class="zoom-controls">
                        <button type="button" id="zoomOut"><img src="{{ asset('RZY/images/zoom-out.png') }}"></button>
                        <button type="button" id="zoomIn"><img src="{{ asset('RZY/images/zoom-in.png') }}"></button>
                    </div>
                    <div class="navigation-controls">
                        <button type="button" class="arrow arrow-left"><img src="{{ asset('RZY/images/fig-modal-img-right.png') }}"></button>
                        <button type="button" class="arrow arrow-right"><img src="{{ asset('RZY/images/fig-modal-img-right.png') }}"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- FULL IMG SLIDER MODAL END --}}

    {{-- CUSTOM SIZE ORDER MODAL START --}}
    <div class="modal fade" id="orderPlacedModal" tabindex="-1" aria-labelledby="orderPlacedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header col-sm-12 justify-content-center flex-column">
              <h2 class="title d-flex flex-column justify-content-center text-center">
                <i class="bi bi-check-circle-fill" style="color:#127812;font-size:30px;"></i> Order Placed
              </h2>
            </div>
            <div class="modal-body thanku flex-column justify-content-center" style="margin-top:-0;">
              <p class="thanku-msg text-center orderPlacedModalMessage"></p>
              <a href="{{ url()->previous() }}" class="btn btn-primary text-uppercase checkout-signin m-auto btn-back-to-home">Back</a>
            </div>
          </div>
        </div>
    </div>
    <div class="modal fade" id="orderPlacedModalError" tabindex="-1" aria-labelledby="orderPlacedModalErrorLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header col-sm-12 justify-content-center flex-column">
              <h2 class="title d-flex flex-column justify-content-center text-center">
                <i class="bi bi-exclamation-triangle-fill me-2" style="color:#ffc107;font-size:30px;"></i>
              </h2>
            </div>
            <div class="modal-body thanku flex-column justify-content-center" style="margin-top:-0;">
              <p class="thanku-msg text-center orderPlacedModalErrorMessage"></p>
              <button type="button" class="btn btn-primary text-uppercase checkout-signin m-auto btn-back-to-home" id="orderPlacedModalErrorBtn">Back</button>
            </div>
          </div>
        </div>
    </div>
     {{-- CUSTOM SIZE ORDER MODAL END --}}
@endsection
@section('styles')
    <!-- <link rel="stylesheet" type="text/css" href="https://raw.githubusercontent.com/bbbootstrap/libraries/main/xzoom.css" media="all" /> -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/RZY/vendor/easyzoom/easyzoom.css') }}?v=0.02"
          media="all"/>
@endsection
@section('head_scripts')
    <script type="text/javascript" src="{{ asset('/RZY/vendor/easyzoom/easyzoom.js') }}"></script>
@endsection
@section('scripts')
    <script>
        var selectedid =  "";
        var tabname = 'standardSizeTab';

        $('#standardSizeTab').on('click', function(){
            $('input[name="rugsPad"]').prop('checked', false);
            tabname = 'standardSizeTab';
        });
        $('#customSizeTab').on('click', function(){
            $('input[name="rugsPad"]').prop('checked', false);
            tabname = 'customSizeTab';
            var is_sale_rep = "{{ isset(Auth::user()->is_sale_rep) ? Auth::user()->is_sale_rep : '' }}";
            var is_customer = "{{ isset(Auth::user()->is_customer) ? Auth::user()->is_customer : '' }}";

            if(is_customer != 1 && is_sale_rep == 1){
                cu_id = $('#grid_item_customer_quote option').eq(1).val() ? $('#grid_item_customer_quote option').eq(1).val().split(' :: ')[1] : '';
            }else{
                cu_id = "{{  Auth::user() ? Auth::user()->customer_id : ''}}";
            }
            setCustomRugPadsPrice(cu_id, 1);
        })

        $('#orderPlacedModalErrorBtn').on('click', function() {
            $('#orderPlacedModalError').modal('hide');
        });

        function setCustomRugPadsPrice(cu_id, area){
            var custpre =  $('#CUSTPREPAD').val() ?  $('#CUSTPREPAD').val().split(':')[0] :  '';
            var custutl =  $('#CUSTULTPAD').val() ?  $('#CUSTULTPAD').val().split(':')[0] :  '';
            console.log('Custom Size PRE/ULT AREA ::',  parseFloat(area.toFixed(2)));

            $.post('{{ route('frontend.item.ats') }}', {
                _token: '{{ csrf_token() }}',
                item_id: custpre,
                customer_id: cu_id,
            }, function (response) {
                console.log('Custom Size PREPAD ATS API :: ',custpre, cu_id,  response.data);
                var final_price = response.data.ReqularSQFT * parseFloat(area.toFixed(2));
                $('.pad-price-custpre').text(`{{ConstantsController::CURRENCY}}${final_price.toFixed({{ConstantsController::ALLOWED_DECIMALS}})}`);
            });

            $.post('{{ route('frontend.item.ats') }}', {
                _token: '{{ csrf_token() }}',
                item_id: custutl,
                customer_id: cu_id,
            }, function (response) {
                console.log('Custom Size ULTPAD ATS API :: ',custutl, cu_id, response.data);
                var final_price = response.data.ReqularSQFT * parseFloat(area.toFixed(2));
                $('.pad-price-custult').text(`{{ConstantsController::CURRENCY}}${final_price.toFixed({{ConstantsController::ALLOWED_DECIMALS}})}`);
            });
        }

        function videoPlay() {
            var element = document.querySelector('.wrapper');
            element.style.height = '100%';
            element.style.overflow = 'hidden';
            element.style.zIndex = '999999 !important';
            $('.video-player').show();
        }

        function closePlayer() {
            $('.wrapper').removeAttr('style');
            $('.video-player').hide();
            $('.big-img-modal').hide();
            $('body').removeAttr('style');
        }


        var item_object = ""; //get the json decoded object
        var $easyzoom = $('.easyzoom').easyZoom({
            loadingNotice: '',
            errorNotice: ''
        });

        function setMainImage(img) {
            $("#image_0").attr("src", img.src);
            $("#image_0").closest('a').attr("href", img.src);
            $easyzoom.data('easyZoom').swap(img.src, img.src);
        }

        function refreshItemJson(callback) {

            if ($('#item_json').length) {
                $.ajax({
                    method: 'GET',
                    url: window.location.href,
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'refresh': 'true',
                    },
                    success: function (response) {
                        // console.log('refreshitemjson response', response);
                        $('.cu-quntity').removeClass('d-none');
                        // console.log("In refreshItemJson");
                        var new_html = $($.parseHTML(response));
                        wishlist_html = new_html.find('#wishlist-name option');
                        // console.log('Length: ', new_html.find('#item_json').length);
                        // console.log('Value: ', new_html.find('#item_json').val());
                        $('#dont_show').val(new_html.find('#dont_show').val());
                        $('#item_json').val(new_html.find('#item_json').val());

                        item_object = JSON.parse($('#item_json').val());
                        if ($('.product_chart').length < 1) {
                            if (item_object.ItemsETA != '' && item_object.ItemsETA) {
                                var tableData = '',
                                    product_chart_html = '';
                                if (
                                    "{{ isset($active_theme_json->general->bulk_add_to_cart) && $active_theme_json->general->bulk_add_to_cart }}") {
                                    console.log("test");
                                    item_object.ItemsETA.forEach(function (item, index) {
                                        tableData += `<tr>
                                                    <input type="hidden" class="cart_item_id" name="product_cart_item_id[]" value="${item['ItemID']}"/>
                                                    <input type="hidden" class="cart_design_id" name="cart_design_id[]" value="${item['DesignID']}"/>
                                                    <input type="hidden" class="cart_customer_id" name="cart_customer_id" value=""/>
                                                    <input type="hidden" class="cart_item_name" name="cart_item_name[]" value="${item['ItemName']}"/>
                                                    <input type="hidden" class="cart_item_quantity" name="cart_item_quantity" value=""/>
                                                    <input type="hidden" class="cart_item_price" name="cart_item_price[]" value="${item['BasePrice']}"/>
                                                    <input type="hidden" class="cart_item_color" name="cart_item_color[]" value="${item['ItemColor']}"/>
                                                    <input type="hidden" class="cart_item_size" name="cart_item_size[]" value="${itemETA['Size']}"/>
                                                    <input type="hidden" class="cart_item_currency" name="cart_item_currency[]" value="$"/>
                                                    <input type="hidden" class="cart_item_image" name="cart_item_image[]" value="${item['ImageName']}"/>
                                                    <input type="hidden" class="cart_item_eta" name="cart_item_eta[]" value=""/>
                                                    <td class="PAChart-Sizes">${item['Size']}</td>
                                                    <td class="PAChart-Dimensions-Weight">${item['ShippingDimension']}<br/>${item['DimentionalWeight']}</td>
                                                    <td class="PAChart-InStock">${item['QtyInStock']}</td>
                                                    <td class="PAChart-Within30Days PAChart-text-Within30Days">${item['QtyThirtyDay']}</td>
                                                    <td class="PAChart-Within2Months">${item['QtyTwoMonth']}</td>
                                                    <td class="PAChart-Over2Months">${item['QtyTwoMonth']}</td>
                                                    <td class="PAChart-Price">${item['BasePrice'].toLocaleString('en-US', {
                                            style: 'currency',
                                            currency: 'USD',
                                        })}</td>
                                                </tr>`;
                                    });
                                    product_chart_html = `<div class="m-auto mt-5 p-0 text-center product_chart">
                                                            <div id="prodAvlChart" class="prodAvlChart" style="display: block;">
                                                                <div class="mb-4">
                                                                    <p class="heading-PAChart">Product Availability Chart</p>
                                                                </div>
                                                                <table id="tblProductSizes" class="table" border="0" cellpadding="3" cellspacing="2" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: middle;border-top: 1px solid #a5a9aa;">
                                                                            <td width="20%" align="center" class="PAChart-Sizes PAChart-text-Heading">{{ $size_heading }}</td>
                                                                            <td width="20%" align="center" class="PAChart-Dimensions-Weight PAChart-text-Heading">Shipping Dimensions / Weight</td>
                                                                            <td width="10%" align="center" class="PAChart-InStock PAChart-text-Heading">Quantity In-Stock</td>
                                                                            <td width="15%" align="center" class="PAChart-Within30Days PAChart-text-Heading">Quantity Within 30 Days</td>
                                                                            <td width="15%" align="center" class="PAChart-Within2Months PAChart-text-Heading">Quantity Within 2 Months</td>
                                                                            <td width="15%" align="center" class="PAChart-Over2Months PAChart-text-Heading">Quantity Over 2 Months</td>
                                                                            <td width="15%" align="center" class="PAChart-Price PAChart-text-Heading">Price</td>
                                                                        </tr>
                                                                        ${tableData}
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>`;
                                } else {
                                    item_object.ItemsETA.forEach(function (item, index) {
                                        tableData += `<tr>
                                                    <td class="PAChart-Sizes">` + item['Size'] + `</td>
                                                    <td class="PAChart-Dimensions-Weight">` + item[
                                                'ShippingDimension'] + '<br/>' + item['DimentionalWeight'] + `</td>
                                                    <td class="PAChart-InStock">` + item['QtyInStock'] + `</td>
                                                    <td class="PAChart-Within30Days PAChart-text-Within30Days">` +
                                            item['QtyThirtyDay'] + `</td>
                                                </tr>`;
                                    });
                                    product_chart_html = `<div class="m-auto mt-5 p-0 text-center product_chart">
                                                            <div id="prodAvlChart" class="prodAvlChart" style="display: block;">
                                                                <div class="mb-4">
                                                                    <p class="heading-PAChart">Product Availability Chart</p>
                                                                </div>
                                                                <table id="tblProductSizes" class="table" border="0" cellpadding="3" cellspacing="2" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: middle;border-top: 1px solid #a5a9aa;">
                                                                            <td width="20%" align="center" class="PAChart-Sizes PAChart-text-Heading">{{ $size_heading }}</td>
                                                                            <td width="20%" align="center" class="PAChart-Dimensions-Weight PAChart-text-Heading">Shipping Dimensions / Weight</td>
                                                                            <td width="10%" align="center" class="PAChart-InStock PAChart-text-Heading">Quantity In-Stock</td>
                                                                            <td width="15%" align="center" class="PAChart-Within30Days PAChart-text-Heading">Quantity Within 30 Days</td>
                                                                        </tr>
                                                                        ${tableData}
                                                                        </tbody>
                                                                        </table>
                                                            </div>
                                                        </div>`;
                                }

                                // $(product_chart_html).insertAfter('.singleProduct');
                                $('.product_chart_model').html(product_chart_html);
                            }
                        }
                        //add new name to wishlist
                        $("#wishlist-name").html('');
                        wishlist_html.each(function () {
                            $("#wishlist-name").append($('<option>', {
                                value: $(this).val(),
                                text: $(this).text()
                            }));
                        });

                        var arr_component = ['.PAChart-Price', '.PAChart-Quantity'];
                        arr_component.forEach(function (component) {
                            if (eval($('#dont_show').val()).includes(component)) {
                                console.log('Component: ', component);
                                $(component).addClass('d-none');
                            }
                        })

                        // console.log('from here');
                        //TODO : for some reason cart-parent is not working here - need to see why
                        $('#quick_cart').html(new_html.find('#quick_cart').html());
                        // console.log($('#quick_cart').html());
                        // console.log(new_html.find('#quick_cart').html());
                        $('#profile-parent').html(new_html.find('#profile-parent').html());
                        $('#cart_main').html(new_html.find('#cart_main').html());
                        $('#grid_cart_main').html(new_html.find('#grid_cart_main').html());
                        $('#grid_custom_size').html(new_html.find('#grid_custom_size').html());
                        show_components(['#add_to_cart', '#get_quote_btn', '#chart_add_to_cart']);
                        show_components(['#grid_add_to_cart']);
                        // $('#grid_cart_main').find('#grid_add_to_cart').removeClass('d-none');
                        $('#cart_main').find('#login_by_popup').remove();
                        $('#grid_cart_main').find('#login_by_popup').remove();
                        $('#cart_main').find('#login_by_popup_quote').remove();
                        $('#grid_custom_size').find('#login_by_popup_quote').remove();

                        // console.log('4');
                        $('#add_to_cart').off('click');
                        $('#add_to_cart').on('click', function (e) {
                            $('#cart_item_quantity').val($('#quantity').val());
                            var item_id = $('#cart_item_sel_id').val();

                            var payload = {
                                // '_token': '{{ csrf_token() }}',
                                'cart_item_id': item_id,//$('#cart_item_id').val(),
                                'cart_design_id': $('#cart_design_id').val(),
                                'cart_customer_id': $('#cart_customer_id').val(),
                                'cart_item_name': $('#cart_item_name').val(),
                                'cart_item_quantity': $('#cart_item_quantity').val(),
                                // 'cart_item_price': $('#cart_item_price').val(),
                                'cart_item_price': $('#main-price').text().replace('$', '').trim(),
                                'cart_item_color': $('#cart_item_color').val(),
                                'cart_item_size': $('#cart_item_size').val(),
                                'cart_item_currency': $('#cart_item_currency').val(),
                                'cart_item_image': $('#cart_item_image').val(),
                           //     'cart_item_data': $items_json,
                                'cart_item_eta': $('#cart_item_eta').val()
                            };
                            //console.log('my payload', payload);
                            pushToCart(true, payload);
                        });

                        $("#grid_add_to_cart").off('click').on('click', function (e) {
                            chartPushToCart();
                        });

                        $("#chart_add_to_cart").off('click').on('click', function (e) {
                            chartAddToCart();
                        });

                        if (callback) {
                            callback();
                        }

                        if (
                            "{{ isset($active_theme_json->general->bulk_add_to_cart) && $active_theme_json->general->bulk_add_to_cart }}") {
                            $('#grid_item_customer').prop('disabled', false);
                            collectionCount = new_html.find('#cart_count').val();

                            if (item_object.Items[0].UserCustomerInfo.IsSaleRep == 1) {
                                getCustomers(item_object.Items[0]);

                                if (item_object.Items[0].UserCustomerInfo.CustomerSet && collectionCount != 0) {
                                    $('#grid_item_customer').prop('disabled', 'disabled');
                                    console.log('diable 1');

                                    var split_arr = $('#grid_item_customer').val().split(' :: ');
                                    var customer_id = split_arr[1].trim();

                                    $.post('{{ route('frontend.item.design_ats') }}', {
                                        _token: '{{ csrf_token() }}',
                                        design_id: item_object.Items[0]['DesignID'],
                                        customer_id: customer_id
                                    }, function (response) {
                                        startBuyingBulk(item_object.Items[0].ItemID, customer_id,
                                            response.data);
                                    });
                                }
                            } else {
                                $.post('{{ route('frontend.item.design_ats') }}', {
                                    _token: '{{ csrf_token() }}',
                                    design_id: item_object.Items[0]['DesignID'],
                                    customer_id: item_object.Items[0].UserCustomerInfo.Customers[0]
                                        .CustomerID
                                }, function (response) {
                                    startBuyingBulk(item_object.Items[0].ItemID, item_object.Items[0]
                                        .UserCustomerInfo.Customers[0].CustomerID, response.data);
                                });
                            }
                        }
                        // else {
                        getQuantity($("#item_size option:selected").val().trim());
                        // }

                    }
                });
            }
        }

        function refresh_product(ItemID) {
            // console.log("In refresh_product");
            item_object.Items.forEach(function (item, index) {
                if (item.ItemID == ItemID) {
                    $('#product-main-image').fadeOut(400, function () {
                        $("#image_0").attr('src', item.ImageNameArray[0]).attr('onerror',
                            "this.src='{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}'");
                        setMainImage({
                            src: item.ImageNameArray[0]
                        });
                    }).fadeIn(400);

                    $('#image_0').attr('alt', item.DesignID);

                    /* $('.overflow-pipe').html('');
                    for (var i = 0; i < item.ImageNameArray.length; i++) {
                        var anchor = $('<a href="javascript:void(0);"> </a>');
                        anchor.append($('<img />', {
                            id: 'thumbnail_' + i,
                            src: item.ImageNameArray[i],
                            onclick: "setMainImage(this)",
                            onerror: "this.src='{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}'"
                        }));
                        $('.overflow-pipe').append(anchor);
                        //$('#thumbnail_'+i).attr('src',item.ImageNameArray[i]).attr('onerror', "this.src='{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}'");
                    } */
                    $('#product-heading').text(item.QualityDescription);
                    $('#product-heading-design').text(item.DesignID);
                    $('#service-type-design').text(item.DesignID);

                    if (item.ProductDescription == null) {
                        item.ProductDescription = '';
                    }
                    var description = item.ProductDescription.toString().trim();
                    if (description.length == 0) {
                        description = ''; // as per recommendation of Shahid Sb
                    }
                    $('#product-description').text(description);

                    $('#item-udf-fields').html("");
                    // item.UDFFields.forEach(function(field, f_index) {
                    //     if (field.FieldName.trim() != 'Size') {
                    //         var field_clone = $('.UDField-template').clone(true);
                    //         field_clone.find('.FieldName').html(field.FieldName.trim() + " : ");
                    //         field_clone.find('.FieldValue').html(field.Value.trim());
                    //         field_clone.removeClass('UDField-template');
                    //         field_clone.addClass('UDField');
                    //         $('#item-udf-fields').append(field_clone);
                    //     }
                    // });

                    item.UDFFields.forEach(function (field, f_index) {
                        // if (!["Construction", "Color(s)", "Material", "Weaving", "PileHeight"].includes(field.FieldName)) {
                            if (f_index < 4) {
                                return;
                            }
                            if (field.FieldName.trim() != 'Size') {
                                var field_clone = $('.UDField-template').clone(true);
                                field_clone.find('.FieldName').html(field.FieldName.trim() + " : ");

                                if (field.FieldName.trim() == 'Feature(s)') {
                                    var featureList = $('<ul></ul>').addClass('col-md-6 FieldValue feature_bullets ps-3 font-jaipur');
                                    var features = field.Value.trim().split(',');
                                    features.forEach(function (feature) {
                                        var listItem = $('<li class="font-jaipur" style="font-size:13px"></li>').text(feature.trim());
                                        featureList.append(listItem);
                                    });
                                    field_clone.find('.FieldValue').replaceWith(featureList);
                                } else if (field.FieldName.trim() == 'Feature 1') {
                                    var featureList = $('<ul></ul>').addClass(' col-md-6 FieldValue feature_bullets ps-3 font-jaipur');
                                    var features = field.Value.trim().split(',');
                                    features.forEach(function (feature) {
                                        var listItem = $('<li class="font-jaipur" style="font-size:13px"></li>').text(feature.trim());
                                        featureList.append(listItem);
                                    });
                                    field_clone.find('.FieldValue').replaceWith(featureList);
                                } else {
                                    field_clone.find('.FieldValue').html(field.Value.trim());
                                }

                                field_clone.removeClass('UDField-template');
                                field_clone.addClass('UDField');
                                $('#item-udf-fields').append(field_clone);
                            }
                        // }
                    });
                    $('.feature_bullets').parent().addClass('d-flex');

                    if (item.Country && item.Country != '') {
                        $('#item-udf-fields').append(
                            '<p class="specs mb-2 UDField col-md-12 row"><strong class="col-md-3 FieldName font-jaipur" style="font-size:13px">Country : </strong> <span class="col-md-9 FieldValue font-jaipur" style="font-size: 0.7rem">' +
                            item.Country + '</span></p>');
                    }

                    // if (item.PileHeight && item.PileHeight != '') {
                    //     $('#item-udf-fields').append(
                    //         '<p class="specs mb-2 UDField col-md-12 row"><strong class="col-md-3 FieldName font-jaipur" style="font-size:13px">Pile Height : </strong> <span class="col-md-9 FieldValue font-jaipur" style="font-size: 0.7rem">' +
                    //         item.PileHeight + '</span></p>');
                    // }
                    // if (item.GroupPricing && item.GroupPricing != '') {
                    //     $('#item-udf-fields').append(
                    //         '<p class="specs mb-2 UDField col-md-12 row"><strong class="col-md-3 FieldName font-jaipur" style="font-size:13px">Group Pricing : </strong> <span class="col-md-9 FieldValue" style="font-size:13px">' +
                    //         item.GroupPricing + '</span></p>');
                    // }
                }
            });
        }

        function hide_components(class_arr) {
            class_arr.forEach(function (component) {
                $(component).removeClass('d-none');
                $(component).addClass('d-none');
            });
        }

        function show_components(class_arr) {
            class_arr.forEach(function (component) {
                if (!eval($('#dont_show').val()).includes(component)) {
                    $(component).removeClass('d-none');
                }
            });
        }

        function init() {
            // console.log("In init");
            item_object = JSON.parse($('#item_json').val());
            collectionCount = $('#cart_count').val();
            if (
                "{{ isset($active_theme_json->general->bulk_add_to_cart) && $active_theme_json->general->bulk_add_to_cart }}") {
                $('#grid_item_customer').prop('disabled', false);
                if (item_object.Items[0].UserCustomerInfo.IsSaleRep == 1) {
                    getCustomers(item_object.Items[0]);

                    if (item_object.Items[0].UserCustomerInfo.CustomerSet &&  collectionCount != 0) {
                        $('#grid_item_customer').prop('disabled', 'disabled');
                        console.log('diable 2');
                        var split_arr = $('#grid_item_customer').val().split(' :: ');
                        var customer_id =  split_arr ? split_arr[1].trim() : '';

                        $.post('{{ route('frontend.item.design_ats') }}', {
                            _token: '{{ csrf_token() }}',
                            design_id: item_object.Items[0]['DesignID'],
                            customer_id: customer_id
                        }, function (response) {
                            console.log('init');
                            startBuyingBulk(item_object.Items[0].ItemID, customer_id, response.data);
                        });
                    }
                } else {
                    $.post('{{ route('frontend.item.design_ats') }}', {
                        _token: '{{ csrf_token() }}',
                        design_id: item_object.Items[0]['DesignID'],
                        customer_id: item_object.Items[0].UserCustomerInfo.Customers[0].CustomerID
                    }, function (response) {
                        startBuyingBulk(item_object.Items[0].ItemID, item_object.Items[0].UserCustomerInfo
                            .Customers[0].CustomerID, response.data);
                    });
                }
            }
            // else {
            var counter = 0;
            $('#item_variant').html('');
            item_object.Items.forEach(function (item, index) {
                if (item.DesignID == '') return;
                if (!$('#item_variant option:contains(' + item.DesignID + ')').length) {
                    $('#item_variant').append($('<option>', {
                        value: item.ItemID,
                        text: item.DesignID
                    }));
                    counter++;
                }
            });
            if (counter > 1) {
                $("#item_variant").val($("#item_variant option:first").val());
            } else {
                hide_components(['#item_variant_parent']);
            }
            getColors($("#item_variant option:selected").text());
            // }
        }

        function getColors(DesignID) {
            var counter = 0;
            show_components(['#item_color_parent']);
            hide_components(['#item_size_parent', '.item_customer_parent', '.item_customer_parent_quote', '#qty-main', '#cart_main', '.base_price',
                '#add_to_cart', '#login_by_popup', '#login_by_popup_quote', '.product_chart_main', '#get_quote_btn', '#chart_add_to_cart',
            ]);
            $('#item_color').html('<option value="0">Select Color</option>');
            item_object.Items.forEach(function (item, index) {
                if (item.DesignID.trim() == DesignID.trim()) {
                    if (!$('#item_color option:contains(' + item.ItemColor + ')').length) {
                        $('#item_color').append($('<option>', {
                            value: item.ItemID,
                            text: item.ItemColor
                        }));
                        counter++;
                    }
                }
            });
            if (counter > 1) {
                $("#item_color_parent").val($("#item_color_parent option:first").val());
            } else {
                hide_components(['#item_color_parent']);
            }

            bind_events();
            $('select#item_color option:nth-child(2)').attr('selected', true).change();
        }

        function getSizes(DesignID, ItemColor, ItemValue) {
            if (ItemValue == '0') {
                hide_components(['#item_size_parent']);
            } else {
                show_components(['#item_size_parent']);
            }
            hide_components(['.item_customer_parent', '.item_customer_parent_quote', '#qty-main', '#cart_main', '.base_price', '#add_to_cart', '#chart_add_to_cart',
                '#login_by_popup', '#login_by_popup_quote', '.product_chart_main', '#get_quote_btn'
            ]);
            $('#item_size').html('<option value="0">Select Size</option>');
            item_object.Items.forEach(function (item, index) {
                if ((item.DesignID.trim() == DesignID.trim()) && (item.ItemColor.trim() == ItemColor.trim())) {
                    // if ( item.ItemColor.trim() == ItemColor.trim() ) {
                    if (!$('#item_size option:contains(' + item.ItemSize + ')').length) {
                        $('#item_size').append($('<option>', {
                            value: item.ItemID,
                            text: item.ItemSize
                        }));
                    }
                }
            });

            bind_events();
            $('select#item_size option:nth-child(2)').attr('selected', true).change();
            if ('Pillows' === "{{ isset($main_collection['Description']) ? $main_collection['Description'] : '' }}" && $(
                '#item_size').length <= 2) hide_components(['#item_size_parent']);
        }

        function getQuantity(ItemID) {
            // console.log("In getQuantity");
            if (ItemID == '0') {
                hide_components(['.item_customer_parent', '.item_customer_parent_quote']);
                hide_components(['#qty-main', '#cart_main', '.base_price', '#add_to_cart', '#get_quote_btn', '#chart_add_to_cart']);
                return;
            }

            item_object.Items.forEach(function (item, index) {
                if ((item.ItemID == ItemID)) {
                    $('#item_customer').prop('disabled', false);

                    if (item.UserCustomerInfo.IsSaleRep == 1) {
                        getCustomers(item);

                        if (item.UserCustomerInfo.CustomerSet) {
                            $('#item_customer').prop('disabled', 'disabled');
                            var split_arr = $('#item_customer').val().split(' :: ');
                            if(split_arr.length > 0 && split_arr[0] != 0){
                                var customer_id = split_arr[1].trim();
                            }
                            $('#qty-main, .base_price').addClass('muted');
                            if (!$('#qty-main').is(':visible'))
                                show_components(['.qty-loader']);
                            $.post('{{ route('frontend.item.ats') }}', {
                                _token: '{{ csrf_token() }}',
                                item_id: ItemID,
                                customer_id: customer_id
                            }, function (response) {
                                startBuying(item.ItemID, customer_id, response.data);
                            });
                        }
                    } else {
                        $('#qty-main, .base_price').addClass('muted');
                        if (!$('#qty-main').is(':visible'))
                            show_components(['.qty-loader']);
                        console.log('one');
                        $.post('{{ route('frontend.item.ats') }}', {
                            _token: '{{ csrf_token() }}',
                            item_id: ItemID,
                            customer_id: item.UserCustomerInfo.Customers[0].CustomerID
                        }, function (response) {
                            startBuying(item.ItemID, item.UserCustomerInfo.Customers[0].CustomerID, response.data);
                        });
                    }
                    if ($('#checkOut_popup').is(':visible'))
                        $('#checkOut_popup').hide();
                }
            });
        }

        function getCustomer(item) {
            //console.log(`item is ${(JSON.stringify(item))}`);
            // console.log("hallow");
            // console.log(item.UserCustomerInfo.CustomerSet,'length is followign');
            console.log("In getCustomers");
            show_components(['.item_customer_parent', '.item_customer_parent_quote', '#cart_main', '#grid_cart_main', '#grid_custom_size']);
            hide_components(['#qty-main', '.base_price', '#add_to_cart', '#login_by_popup', '#login_by_popup_quote', '#grid_add_to_cart', '#get_quote_btn', '#chart_add_to_cart',
                '.product_chart_main'
            ]);
            $('#item_customer').html('<option value="0">Select Customer</option>');
            $('#grid_item_customer').html('<option value="0">Select Customer</option>');
            $('#chart_grid_item_customer').html('<option value="0">Select Customer</option>');
            $('#grid_item_customer_quote').html('<option value="0">Select Customer</option>');
            
            //  console.log(item.UserCustomerInfo.CustomerSet,'length is followign');
            //  console.log(Customer.CustomerID)
            item.UserCustomerInfo.Customers.forEach(function (Customer, index) {
                console.log('i am on index number ', index);
                if(index<10){
                if (item.UserCustomerInfo.CustomerSet && collectionCount != 0) {
                    // console.log(item.length ,`item.userCus is ${item.UserCustomerInfo.length} and index is ${index} and Customer lenght is`)
                    // console.log(item.UserCustomerInfo.CustomerSet.length,'length is followign');
                    $('.active_customer_select').addClass('d-none');
                    $('.disabled_customer_select').removeClass('d-none');
                    if (Customer.CustomerID == item.UserCustomerInfo.CustomerSet ) {
                        $('#item_customer, #grid_item_customer, #grid_item_customer_quote, #chart_grid_item_customer').append($('<option>', {
                            value: item.ItemID + ' :: ' + Customer.CustomerID,
                            text: Customer.CompanyName + ' (' + Customer.CustomerID + ')',
                            selected: 'selected'
                        }));
                    }

                } else {
                    // console.log("item length is ",item.ItemID.length);
                    $('.active_customer_select').removeClass('d-none');
                    $('.disabled_customer_select').addClass('d-none');
                    $('#item_customer, #grid_item_customer, #grid_item_customer_quote, #chart_grid_item_customer').append($('<option>', {
                        value: item.ItemID + ' :: ' + Customer.CustomerID,
                        text: Customer.CompanyName + ' (' + Customer.CustomerID + ')'
                    }));
                }
                }
                else {
                    return;
                }
            });
        }
let storedItem = null;

function getCustomers(item) {
    // Store for use in second function
    storedItem = item;

     console.log("In getCustomers");
            show_components(['.item_customer_parent', '.item_customer_parent_quote', '#cart_main', '#grid_cart_main', '#grid_custom_size']);
            hide_components(['#qty-main', '.base_price', '#add_to_cart', '#login_by_popup', '#login_by_popup_quote', '#grid_add_to_cart', '#get_quote_btn', '#chart_add_to_cart',
                '.product_chart_main'
            ]);
    // Reset dropdowns
    $('#item_customer, #grid_item_customer, #grid_item_customer_quote, #chart_grid_item_customer')
        .html('<option value="0">Select Customer</option>');

    // Load first 10 customers
    item.UserCustomerInfo.Customers.forEach(function (Customer, index) {
        if (index < 100) {
            addCustomerOption(item, Customer);
        } else {
            return;
        }
    });
}

function addCustomerOption(item, Customer) {
    const isSelected = item.UserCustomerInfo.CustomerSet && collectionCount != 0 && Customer.CustomerID == item.UserCustomerInfo.CustomerSet;

    const option = $('<option>', {
        value: item.ItemID + ' :: ' + Customer.CustomerID,
        text: Customer.CompanyName + ' (' + Customer.CustomerID + ')',
        selected: isSelected ? 'selected' : false
    });

    $('#item_customer, #grid_item_customer, #grid_item_customer_quote, #chart_grid_item_customer')
        .append(option);

    if (isSelected) {
        $('.active_customer_select').addClass('d-none');
        $('.disabled_customer_select').removeClass('d-none');
    } else {
        $('.active_customer_select').removeClass('d-none');
        $('.disabled_customer_select').addClass('d-none');
    }
}
function appendRemainingCustomers() {
    if (!storedItem || !Array.isArray(storedItem.UserCustomerInfo.Customers)) return;

    const remainingCustomers = storedItem.UserCustomerInfo.Customers.slice(10);
    let index = 0;
    const batchSize = 100;

    function appendBatch() {
        const end = Math.min(index + batchSize, remainingCustomers.length);
        for (let i = index; i < end; i++) {
            const Customer = remainingCustomers[i];
            addCustomerOption(storedItem, Customer);
        }

        index = end;
        if (index < remainingCustomers.length) {
            requestIdleCallback(appendBatch);
        }
    }

    requestIdleCallback(appendBatch);
}

// Safe fallback for old browsers
window.requestIdleCallback = window.requestIdleCallback || function (cb) {
    return setTimeout(cb, 50);
};

// Automatically run after page load
window.addEventListener('load', function () {
    appendRemainingCustomers();
});

        function getCartReady(item_customer_id) {
            console.log("In getCartReady", item_customer_id);
            hide_components(['#add_to_cart']);
            // console.log("item_customer_id: ", item_customer_id);
            $('#qty-main, .base_price').addClass('muted');
            var split_arr = item_customer_id.split(' :: ');
            var item_id = split_arr[0].trim();
            var customer_id = split_arr[1].trim();
            item_object.Items.forEach(function (item, index) {
                if ((item.ItemID == item_id)) {
                    item.UserCustomerInfo.Customers.forEach(function (Customer, index) {
                        if (Customer.CustomerID == customer_id) {
                            if (
                                "{{ isset($active_theme_json->general->bulk_add_to_cart) && $active_theme_json->general->bulk_add_to_cart }}") {
                                $.post('{{ route('frontend.item.design_ats') }}', {
                                    _token: '{{ csrf_token() }}',
                                    design_id: item.DesignID,
                                    customer_id: customer_id
                                }, function (response) {
                                    startBuyingBulk(item_id, customer_id, response.data);
                                });
                            }
                            // else {
                            if (!$('#qty-main').is(':visible'))
                                show_components(['.qty-loader']);
                            console.log('2');
                            $.post('{{ route('frontend.item.ats') }}', {
                                _token: '{{ csrf_token() }}',
                                item_id: item_id,
                                customer_id: customer_id
                            }, function (response) {
                                startBuying(item_id, customer_id, response.data);
                            });
                            // }

                        }
                    });
                }
            });
        }

        function startBuying(ItemID, CustomerID, ATSInfo) {
            console.log("In startBuying");
            // console.log('item id', ItemID);
            console.log('ATSInfo', ATSInfo);

            if ($('#login_by_popup').length) {
                show_components(['#login_by_popup', '#login_by_popup_quote', '#cart_main', '.product_chart_main']);
                hide_components(['#qty-main', '.base_price', '.qty-loader']);
                $('#qty-main, .base_price').removeClass('muted');
            } else {
                hide_components(['.qty-loader']);
                show_components(['#qty-main', '#cart_main', '.base_price', '#grid_add_to_cart', '#chart_add_to_cart']);
                $('#qty-main, .base_price').removeClass('muted');
            }

            $('#qty_msg').text(getQuantityMessage(ATSInfo));
            $('#item_qty').attr('max', ATSInfo.OnlyMaxQuantity ? ATSInfo.ATSQty : 9999);
            $('#item_qty').attr('max', ATSInfo.OnlyMaxQuantity ? ATSInfo.ATSQty : 9999);
            $('#base_price').html((ATSInfo.Price).toLocaleString('en-US', {
                style: 'currency',
                currency: 'USD',
            }));

            if(tabname == "customSizeTab"){
                var lengthFeet = parseFloat($('#feet_length').val()) || 0;
                var lengthInches = parseFloat($('#inches_length').val()) || 0;
                var widthFeet = parseFloat($('#feet_width').val()) || 0;
                var widthInches = parseFloat($('#inches_width').val()) || 0;
                var lengthInFeet = lengthFeet + (lengthInches / 12);
                var widthInFeet = widthFeet + (widthInches / 12);
                var area = lengthInFeet * widthInFeet;

if(area<24){
    area=24;
}
                var regular_price = parseFloat(area.toFixed(2)) * ATSInfo.ReqularSQFT;
                //var regular_price =123;
                var regular_format_price = '$' + regular_price.toFixed(2);
                var express_price = parseFloat(area.toFixed(2)) * ATSInfo.ExpressSQFT;
                var express_format_price = '$' + express_price.toFixed(2);

                $('.regular-service-per-unit').text(regular_format_price);
                $('.regular-service-total').text(regular_format_price);
                $('.express-service-per-unit').text(express_format_price);
                $('.express-service-total').text(express_format_price);

                var totalprice = $('.regular-service-total').text().replace('$', '').trim();
                $('.custom-price-value').val(totalprice);
                $('.custom-price-type').val('Regular');
            }

            var is_sale_rep = "{{ isset(Auth::user()->is_sale_rep) ? Auth::user()->is_sale_rep : '' }}";
            var is_customer = "{{ isset(Auth::user()->is_customer) ? Auth::user()->is_customer : '' }}";
            var db_cutomer_id = "{{ Auth::user() ? Auth::user()->customer_id : '' }}";

            if(tabname  != "customSizeTab"){
                CustomerID = (is_customer != 1 && is_sale_rep == 1) ? CustomerID : db_cutomer_id
                $.post('{{ route('frontend.item.ats') }}', {
                    _token: '{{ csrf_token() }}',
                    item_id: selectedid,
                    customer_id: CustomerID,
                }, function (response) {
                    console.log('Main Item Standard Size ATS API :: ',selectedid, CustomerID, response.data);
                    $('#main-price').text(`{{ConstantsController::CURRENCY}}${response.data.Price.toFixed({{ConstantsController::ALLOWED_DECIMALS}})}`);
                    if(parseFloat(response.data.ActualPrice) > response.data.Price){
                        $('#main-price-cut').text(`{{ConstantsController::CURRENCY}}${parseFloat(response.data.ActualPrice).toFixed({{ConstantsController::ALLOWED_DECIMALS}})}`);
                    }else{
                        $('#main-price-cut').text('');
                    }
                    $('.other-price-text').css('display', 'block');

                    response.data.MAPPrice  = (response.data.MAPPrice != null ? response.data.MAPPrice : '0.00');
                    response.data.MSRPPrice = (response.data.MSRPPrice != null ? response.data.MSRPPrice : '0.00');
                    $('#map-price').text(`{{ConstantsController::CURRENCY}}${parseFloat(response.data.MAPPrice).toFixed({{ConstantsController::ALLOWED_DECIMALS}})}`);
                    $('#msrp-price').text(`{{ConstantsController::CURRENCY}}${parseFloat(response.data.MSRPPrice).toFixed({{ConstantsController::ALLOWED_DECIMALS}})}`);
                });
            }

            if(tabname  != "customSizeTab"){
                var pre = $('.PREPAD').val();
                var ult =  $('.ULTPAD').val();
                CustomerID = (is_customer != 1 && is_sale_rep == 1) ? CustomerID : db_cutomer_id;
                if(pre != undefined){
                    $.post('{{ route('frontend.item.ats') }}', {
                    _token: '{{ csrf_token() }}',
                    item_id:  pre,
                    customer_id: CustomerID,
                    }, function (response) {
                        console.log('Main Item Standard Size PREPAD ATS API :: ',pre, CustomerID, response.data);
                        $('.pad-price-pre').text(`{{ConstantsController::CURRENCY}}${response.data.Price.toFixed({{ConstantsController::ALLOWED_DECIMALS}})}`);
                    });
                }

                if(ult != undefined){
                    $.post('{{ route('frontend.item.ats') }}', {
                    _token: '{{ csrf_token() }}',
                    item_id: ult,
                    customer_id: CustomerID,
                    }, function (response) {
                        console.log('Main Item Standard Size ULTPAD ATS API :: ',ult, CustomerID, response.data);
                        $('.pad-price-ult').text(`{{ConstantsController::CURRENCY}}${response.data.Price.toFixed({{ConstantsController::ALLOWED_DECIMALS}})}`);

                        if ($('#login_by_popup').length == 0 && response && $('#cart_item_size').val().trim() !== '') {
                            show_components(['#add_to_cart']);
                        }
                    });
                }else{
                    if ($('#login_by_popup').length == 0 && $('#cart_item_size').val().trim() !== '') {
                        show_components(['#add_to_cart']);
                    }
                }
            }

            if(tabname == "customSizeTab"){
                CustomerID = (is_customer != 1 && is_sale_rep == 1) ? CustomerID : db_cutomer_id;
                setCustomRugPadsPrice(CustomerID, area);
            }

            item_object.Items.forEach(function (item, index) {
                if ((item.ItemID == ItemID)) {
                    // console.log('item.ItemID', item.ItemID);
                    $('#cart_item_id').val(item.ItemID);
                    $('#cart_design_id').val(item.DesignID);
                    $('#cart_customer_id').val(CustomerID);
                    $('#cart_item_name').val(item.QualityDescription);
                    $('#cart_item_quantity').val($('#item_qty').val(0));
                    $('#cart_item_price').val(ATSInfo.Price);
                    $('#cart_item_color').val($("#item_color option:selected").text());
                    // $('#cart_item_size').val($("").text());
                    $('#cart_item_currency').val('$');
                    $('#cart_item_image').val(item.ImageNameArray[0]);
                    $('#cart_item_eta').val(ATSInfo.ETADate);
                }
            });
        }

        function startBuyingBulk(ItemID, CustomerID, ATSInfo) {
            // console.log("In startBuyingBulk");
            // console.log("ATSInfo: ", ATSInfo);
            if ($('#login_by_popup').length) {
                show_components(['#login_by_popup', '#login_by_popup_quote', '#cart_main', '#grid_cart_main', '.product_chart_main', '#grid_custom_size']);
                hide_components([]);
            } else {
                hide_components([]);
                show_components(['#cart_main', '#grid_add_to_cart', '#chart_add_to_cart', '#grid_cart_main', '#grid_custom_size']);
            }

            ATSInfo.forEach(function (item, index) {
                // console.log("item.ATSQty: ", item.ATSQty);
                // console.log("item.Price: ", item.Price);
                $('.cart_item_id').each(function () {
                    if ($(this).val() == item.ItemID) {
                        $(this).siblings('.PAChart-Price').text(item.Price.toLocaleString('en-US', {
                            style: 'currency',
                            currency: 'USD',
                        }));
                        $(this).siblings('.cart_item_price').val(item.Price);
                        $(this).siblings('.PAChart-InStock').text(item.ATSQty < 0 ? 0 : item.ATSQty);
                        $(this).siblings('.PAChart-Quantity').children('.item_qty').attr('max', item
                            .OnlyMaxQuantity ? item.ATSQty : 9999);
                    }
                });
            });

            item_object.ItemsETA.forEach(function (item, index) {
                if ((item['ItemID'] == ItemID)) {
                    // $('.cart_customer_id').val(CustomerID);
                    $('.cart_customer_id').each(function () {
                        $(this).val(CustomerID);
                    });
                }
            });
        }

        function getQuantityMessage(ATSInfo) {
            if ($('#login_by_popup').length) {
                if (ATSInfo.ATSQty == 0) {
                    return `Backorder. ETA: ${ATSInfo.ETADate}`;
                } else if (ATSInfo.ATSQty <= 5) {
                    return `Limited Quantity Available, please email to confirm`;
                } else
                    return `In Stock`;
            } else {
                $('.product_chart_main').addClass('d-none');
                if (ATSInfo.ATSQty == 0) {
                    return `Backorder. ETA: ${ATSInfo.ETADate}`;
                } else if (ATSInfo.ATSQty <= 5) {
                    return `Limited Quantity Available, please email to confirm`;
                } else if (ATSInfo.ATSQty > 5 && ATSInfo.ATSQty <= 15) {
                    return `${ATSInfo.ATSQty} Available`;
                } else {
                    return `15+ Available`;
                }
            }
        }

        let cart_status = false;

        function pushToCart(toaster = true, payload, recommended_item = false) {
          //  console.log('payload', payload);
            var cart_items_data = [];

            $('#add_to_cart').addClass('btn-muted');
            // $('#cart_item_quantity').val($('#item_qty').val());
            // $('#cart_item_quantity').val($('#quantity').val());
            // console.log("cart_item: ", $('#cart_item_quantity').val());
            // console.log("cart_push: ", $('#cart_item_size').val());
            // console.log("cart_push: ", $('#cart_item_id').val());
            // console.log(parseInt($('#quantity').val()))
            // console.log($('#cart_item_size').val())
            // console.log(((/^\+?[1-9]\d*/).test(parseInt($('#quantity').val())) && $('#cart_item_size').val() != ""))

             // SINGLE ADD
             if (((/^\+?[1-9]\d*/).test(parseInt($('#quantity').val())) && $('#cart_item_size').val() != "") || recommended_item) {
                    $.ajax({
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        dataType: 'json',
                        url: '{{ route("frontend.cart.add") }}',
                        data: payload,
                        // data:{'test' : 'testvalue'},
                        success: function (response) {
                            console.log(response);
                            if (response.success) {
                               // console.log('response success');
                                if ($('#item_json').length) {
                                    refreshItemJson(function () {
                                        if (toaster) {
                                            toastr.success(response.message, {
                                                hideDuration: 10000,
                                                closeButton: true,
                                            });
                                        }
                                        var recommended_rug_selected = $('input[name="rugsPad"]:checked').val();
                                        var recommended_rug_selected_id = $('input[name="rugsPad"]:checked').attr('id');
                                         console.log('recommended_rug_selected', recommended_rug_selected);
                                        // console.log('recommended_rug_selected_id', recommended_rug_selected_id);
                                        var recommended_rug_selected_arr = (recommended_rug_selected != undefined) ? recommended_rug_selected.split(':') : '';
                                        if (recommended_rug_selected) {
                                            // console.log('raw rug json', $('#recommended_rugs_json').val());
                                            let recommended_rugs_json = JSON.parse($('#recommended_rugs_json').val());
                                            // console.log('recommended_rugs_json', recommended_rugs_json);

                                            // recommended_rug_selected = recommended_rugs_json[recommended_rug_selected_arr[1]];
                                            recommended_rug_selected = recommended_rugs_json[recommended_rug_selected_id];

                                            // recommended_rug_selected = JSON.parse(recommended_rug_selected);
                                            let rugpad_price;
                                            let rugpad_itemid;
                                            if(recommended_rug_selected['Items'][0]['DesignID'] == "PREPAD"){
                                                rugpad_price = $('#pad-price-pre').text().replace('$', '').trim();
                                                rugpad_itemid =  $('.PREPAD').val();
                                                rugpad_itemid = rugpad_itemid.split(':')[0];
                                                rugpad_size = $('.pad-dimension').text();
                                            }else{
                                                rugpad_price = $('#pad-price-ult').text().replace('$', '').trim();
                                                rugpad_itemid =  $('.ULTPAD').val();
                                                rugpad_itemid = rugpad_itemid.split(':')[0];
                                                rugpad_size = $('.ult-dimension').text();
                                            }

                                            let payload_2 = {
                                               // '_token': '{{ csrf_token() }}',
                                                'cart_item_id': rugpad_itemid,
                                                'cart_design_id': recommended_rug_selected['Items'][0]['DesignID'],
                                                'cart_customer_id': $('#cart_customer_id').val(),
                                                'cart_item_name': recommended_rug_selected['Items'][0]['ItemName'],
                                                'cart_item_quantity': $('#cart_item_quantity').val(), //1,
                                                'cart_item_price': rugpad_price,
                                                'cart_item_color': recommended_rug_selected['Colors'][0]['Description'],
                                                'cart_item_size': rugpad_size, //recommended_rug_selected['Sizes'][0]['Description'],
                                                'cart_item_currency': $('#cart_item_currency').val(),
                                                'cart_item_image': recommended_rug_selected['Items'][0]['ImageName'],
                                            //    'cart_item_data': $items_json,
                                                'cart_item_eta': $('#cart_item_eta').val()
                                            }
                                            console.log('payload_2', payload_2);
                                            pushToCart(false, payload_2, true);
                                            $('input[name="rugsPad"]').prop('checked', false);
                                        }
                                        $('#add_to_cart').removeClass('btn-muted');
                                    });
                                    cart_status = true;
                                } else {
                                    console.log('item json fail');
                                    refreshUser('quick-cart', function () {
                                        refreshUser('profile', function () {
                                            $("#quick_cart").removeClass('d-none');
                                            if (toaster) {
                                                toastr.success(response.message, {
                                                    hideDuration: 10000,
                                                    closeButton: true,
                                                });
                                            }
                                            $('#add_to_cart').removeClass('btn-muted');
                                        });
                                    });

                                    cart_status = false;

                                }
                            } else {
                                console.log('repsone success fail');
                                if (toaster) {
                                    toastr.warning(response.message, {
                                        hideDuration: 10000,
                                        closeButton: true,
                                    });
                                }
                                $('#add_to_cart').removeClass('btn-muted');

                                cart_status = false;
                            }
                        },
                        error: function (response) {
                            if (toaster) {
                                toastr.warning(response.message, {
                                    hideDuration: 10000,
                                    closeButton: true,
                                });
                            }
                            $('#add_to_cart').removeClass('btn-muted');

                            cart_status = false;
                        }
                    });
                } else {
                    if (toaster) {
                        // toastr.warning("Please enter a valid value", {
                        //     hideDuration: 10000,
                        //     closeButton: true,
                        // });
                        toastr.warning("Please enter a valid quantity and select an item size.", {
                            hideDuration: 10000,
                            closeButton: true,
                        });
                    }
                    $('#add_to_cart').removeClass('btn-muted');

                    cart_status = false;
                }
        }

        function chartAddToCart(){
            var cart_items_data = [];
            var cart_status = false;
            $('#chart_add_to_cart').addClass('btn-muted');

            var is_customer = "{{Auth::check() && Auth::user()->is_customer}}";
            if(is_customer){
                customer_value = $('.cart_customer_id').val();
            }else{
                if ($('#chart_grid_item_customer').val().split('::')[1].trim() === '') {
                    toastr.warning("Please select a customer.", {
                        hideDuration: 10000,
                        closeButton: true,
                    });
                    $('#chart_add_to_cart').removeClass('btn-muted');
                }else{
                    customer_value = $('#chart_grid_item_customer').val().split('::')[1].trim();
                }
            }

            $('.item_qty').each(function() {
                if ($(this).val() !== '') {
                    console.log("item_qty: ", $(this).val());
                    if ((/^\+?[1-9]\d*/).test(parseInt($(this).val()))) {
                        var cart_items_array = {
                            'cart_item_id': $(this).parent().siblings('.cart_item_id').val(),
                            'cart_design_id': $(this).parent().siblings('.cart_design_id').val(),
                            'cart_customer_id': customer_value,
                            'cart_item_name': $(this).parent().siblings('.cart_item_name').val(),
                            'cart_item_quantity': $(this).val(),
                            'cart_item_price': $(this).parent().siblings('.cart_item_price').val(),
                            'cart_item_color': $(this).parent().siblings('.cart_item_color').val(),
                            'cart_item_size': $(this).parent().siblings('.cart_item_size').val(),
                            'cart_item_currency': '$',
                            'cart_item_image': $(this).parent().siblings('.cart_item_image').val(),
                            'cart_item_data': '',
                            'cart_item_eta': $(this).parent().siblings('.cart_item_eta').val()
                        };
                        cart_items_data.push(cart_items_array);
                    }
                }
            });
            cart_items_data.pop();

            if (cart_items_data.length === 0) {
                toastr.warning("Please enter a valid value", {
                    hideDuration: 10000,
                    closeButton: true,
                });
                $('#chart_add_to_cart').removeClass('btn-muted');
            }

            if (cart_items_data.length > 0 && cart_items_data[0]['cart_item_id'] != undefined) {
                $.ajax({
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    url: '{{ route('frontend.cart.bulk_add') }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'cart_items_data': cart_items_data
                    },
                    success: function (response) {
                        if (response.success) {
                            $('.btn-close').click();
                            $('.chart_item_qty').val('');
                            console.log('response success');
                            refreshUser('quick-cart', function () {
                                refreshUser('profile', function () {
                                    refreshItemJson(function () {
                                        if (toaster) {
                                            toastr.success(response.message, {
                                                hideDuration: 10000,
                                                closeButton: true,
                                            });
                                        }
                                    });
                                });
                            });
                            toastr.success(
                                response.message, {
                                    hideDuration: 10000,
                                    closeButton: true,
                            });
                            $('#chart_add_to_cart').removeClass('btn-muted');
                            cart_status = true;
                        } else {
                            console.log('repsone success fail');
                            if (toaster) {
                                toastr.warning(response.message, {
                                    hideDuration: 10000,
                                    closeButton: true,
                                });
                            }
                            $('#chart_add_to_cart').removeClass('btn-muted');
                            cart_status = false;
                        }
                    }
                });
            }
        }

        function chartPushToCart() {
            $('#grid_add_to_cart').addClass('btn-muted');

            if ($('#grid_item_customer').length && $('#grid_item_customer').val() !== '0') {
                var item_customer_id = $('#grid_item_customer').val();
                var split_arr = item_customer_id.split(' :: ');
                var customer_id = split_arr[1].trim();
            } else {
                var customer_id = $('.cart_customer_id').val();
            }
            // console.log("customer_id: ", customer_id);

            if (customer_id === '') {
                toastr.warning("Please select a customer.", {
                    hideDuration: 10000,
                    closeButton: true,
                });
                $('#grid_add_to_cart').removeClass('btn-muted');
                return
            }
            console.log("test: ", $(this).parent().siblings('.cart_item_size').val());
            var cart_items_data = [];
            $('.item_qty').each(function () {
                if ($(this).val() !== '') {
                    // console.log("item_qty: ", $(this).val());
                    if ((/^\+?[1-9]\d*/).test(parseInt($(this).val()))) {

                        var cart_items_array = {
                            'cart_item_id': $(this).parent().siblings('.cart_item_id').val(),
                            'cart_design_id': $(this).parent().siblings('.cart_design_id').val(),
                            'cart_customer_id': customer_id,
                            'cart_item_name': $(this).parent().siblings('.cart_item_name').val(),
                            'cart_item_quantity': $(this).val(),
                            'cart_item_price': $(this).parent().siblings('.cart_item_price').val(),
                            'cart_item_color': $(this).parent().siblings('.cart_item_color').val(),
                            'cart_item_size': $(this).parent().siblings('.cart_item_size').val(),
                            'cart_item_currency': '$',
                            'cart_item_image': $(this).parent().siblings('.cart_item_image').val(),
                            'cart_item_data': $('#item_json').val(),
                            'cart_item_eta': $(this).parent().siblings('.cart_item_eta').val()
                        };

                        cart_items_data.push(cart_items_array);
                    } else {
                        toastr.warning("Please enter a valid value", {
                            hideDuration: 10000,
                            closeButton: true,
                        });
                        $('#grid_add_to_cart').removeClass('btn-muted');
                    }

                }
            });
            // console.log(cart_items_data);
            if (cart_items_data.length > 0) {
                $.ajax({
                    method: 'POST',
                    url: '{{ route('frontend.cart.bulk_add') }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'cart_items_data': cart_items_data
                    },
                    success: function (response) {
                        if (response.success) {
                            if ($('#item_json').length) {
                                refreshItemJson(function () {
                                    toastr.success(response.message, {
                                        hideDuration: 10000,
                                        closeButton: true,
                                    });
                                    $('#grid_add_to_cart').removeClass('btn-muted');
                                    $('.item_qty').val('');
                                });
                            } else {
                                refreshUser('quick-cart', function () {
                                    refreshUser('profile', function () {
                                        $("#quick_cart").removeClass('d-none');
                                        toastr.success(response.message, {
                                            hideDuration: 10000,
                                            closeButton: true,
                                        });
                                        $('#grid_add_to_cart').removeClass('btn-muted');
                                        $('.item_qty').val('');
                                    });
                                });
                            }
                        } else {
                            toastr.warning(response.message, {
                                hideDuration: 10000,
                                closeButton: true,
                            });
                            $('#grid_add_to_cart').removeClass('btn-muted');
                        }
                    },
                    error: function (response) {
                        toastr.warning(response.message, {
                            hideDuration: 10000,
                            closeButton: true,
                        });
                        $('#grid_add_to_cart').removeClass('btn-muted');
                    }
                });
            } else {
                toastr.warning('Please add some quantity before adding in cart.', {
                    hideDuration: 10000,
                    closeButton: true,
                });
                $('#grid_add_to_cart').removeClass('btn-muted');
            }

        }

        function bind_events() {
            // console.log("In bind_events");
            $("#item_variant").off('change').on('change', function () {
                refresh_product($(this).val());
                getColors($("#item_variant option:selected").text().trim());
            });

            $("#item_color").off('change').on('change', function () {
                refresh_product($(this).val());
                getSizes($("#item_variant option:selected").text().trim(), $('#item_color option:selected').text()
                    .trim(), $(this).val());
            });

            $("#item_size").off('change').on('change', function () {
                refresh_product($(this).val());
                getQuantity($(this).val());
            });

            $("#item_customer, #grid_item_customer, #chart_grid_item_customer, #grid_item_customer_quote").off('change').on('change', function () {
                var split_arr = $(this).val().split(' :: ');
                var item_id = split_arr[0].trim();
                console.log("item: on chnage", item_id);
                refresh_product(item_id);
                getCartReady($(this).val());
            });

            $("#add_to_cart").off('click').on('click', function (e) {
                console.log('clckkkk');
                $('#cart_item_quantity').val($('#quantity').val());

                var item_id = $('#cart_item_sel_id').val();
                var payload = {
                    // '_token': '{{ csrf_token() }}',
                    'cart_item_id': item_id, //$('#cart_item_id').val(),
                    'cart_design_id': $('#cart_design_id').val(),
                    'cart_customer_id': $('#cart_customer_id').val(),
                    'cart_item_name': $('#cart_item_name').val(),
                    'cart_item_quantity': $('#cart_item_quantity').val(),
                    // 'cart_item_price': $('#cart_item_price').val(),
                    'cart_item_price': $('#main-price').text().replace('$', '').trim(),
                    'cart_item_color': $('#cart_item_color').val(),
                    'cart_item_size': $('#cart_item_size').val(),
                    'cart_item_currency': $('#cart_item_currency').val(),
                    'cart_item_image': $('#cart_item_image').val(),
                  //  'cart_item_data': $items_json,
                    'cart_item_eta': $('#cart_item_eta').val()
                };
                console.log('payload check', payload);
                pushToCart(true, payload);
            });

            $("#grid_add_to_cart").off('click').on('click', function (e) {
                chartPushToCart();
            });

            $("#chart_add_to_cart").off('click').on('click', function (e) {
                chartAddToCart();
            });

            $('.design-add-wishlist').off('click').on('click', function (e) {
                if ($('#login_by_popup').length) {
                    $('#login_by_popup').click();

                } else {
                    $(".design-wishlist-main").show();
                }

                if ($('#login_by_popup_quote').length) {
                    $('#login_by_popup_quote').click();

                } else {
                    $(".design-wishlist-main").show();
                }
            });
            // $('#item-add-wishlist').click(function() {
            //     $('.item-wishlist-popup-main').toggle();
            // });
        }

        $(document).ready(function () {
            init();
            bind_events();

            let arr_up = "{{ asset('images/arr-up.png') }}";
            let arr_down = "{{ asset('images/arr-down.png') }}";
            $('.owl-carousel-imgaes').slick({
                autoplay: false,
                autoplaySpeed: 2000,
                dots: false,
                vertical: true,
                slidesToShow: 5,
                slidesToScroll: 1,
                arrows: true,
                prevArrow: '<div style="text-align: center; margin-right:30px; margin-bottom: 10px"><img src="' + arr_up + '" width="15px" /></div>',
                nextArrow: '<div style="text-align: center; margin-right:30px; margin-top: 10px"><img src="' + arr_down + '" width="15px" /></div>',
            });

            $('.owl-carousel-fbt').owlCarousel({
                loop: false,
                autoplay: false,
                margin: 20,
                nav: true,
                dots: true,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 2,
                        nav: true,
                        dots: true
                    },
                    600: {
                        items: 3,
                        dots: true,
                        nav: false
                    },
                    1000: {
                        items: 4,
                        autoplay: true,
                        autoplaySpeed: 1000,
                        nav: true,
                        dots: true,
                    }
                }
            }).trigger('stop.owl.autoplay');
            $('.owl-carousel-products').owlCarousel({
                loop: false,
                autoplay: false,
                margin: 20,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 2,
                        nav: true,
                        dots: true
                    },
                    600: {
                        items: 3,
                        dots: true,
                        nav: false
                    },
                    1000: {
                        items: 4,
                        autoplay: true,
                        autoplaySpeed: 1000,
                        nav: true,
                        dots: true,
                        loop: eval(
                            '{{ isset($related_designs) && isset($related_designs['Designs']) && count($related_designs['Designs']) > 4 }}'
                        )
                    }
                }
            }).trigger('stop.owl.autoplay');


            //create new list and add item
            $(".add-to-wishlist-main").click(function () {
                $('.add-to-wishlist-main').addClass('btn-muted');
                if ($(".new-list-field").is(':visible')) {
                    if ($('.new-list').val() != '') {

                        var list_name = $('.new-list').val();

                    } else {
                        toastr.error("Please enter the Wishlist name first....", {
                            hideDuration: 10000,
                            closeButton: true,
                        });
                        $('.add-to-wishlist-main').removeClass('btn-muted');
                        return;
                    }

                } else if (!$('#wishlist-name').find(":selected").val()) {
                    toastr.error("No Wishlist exist Please add a new wishlist....", {
                        hideDuration: 10000,
                        closeButton: true,
                    });
                    $('.add-to-wishlist-main').removeClass('btn-muted');
                    return;

                } else {
                    var list_name = $('#wishlist-name').find(":selected").text();
                }

                const filteredItems = item_object.ItemsETA.filter(item => item.Size === ($(
                    '#wishlist-item-size').find(":selected").val()));
                //console.log(filteredItems[0].ItemID);
                $.post('{{ route('wishlists.store') }}', {

                    _token: '{{ csrf_token() }}',
                    name: list_name,
                    item_id: filteredItems[0].ItemID,
                    design_id: filteredItems[0].DesignID,
                    item_name: filteredItems[0].ItemName,
                    item_price: filteredItems[0].BasePrice,
                    item_color: filteredItems[0].ItemColor,
                    item_size: $('#wishlist-item-size').find(":selected").val(),
                    item_currency: filteredItems[0].ItemCurrency,
                    item_image: filteredItems[0].ImageName,

                }, function (response) {
                    //console.log(response.success);
                    if (response.success) {
                        $('.add-to-wishlist-main').removeClass('btn-muted');
                        console.log(response.input);
                        refreshItemJson(function () {
                            toastr.success(response.message, {
                                hideDuration: 10000,
                                closeButton: true,
                            });
                            $(".design-add-wishlist").removeClass("bi-suit-heart").addClass(
                                "bi-suit-heart-fill");
                            $('.new-list').val('');
                            $(".list-remove").click();
                            $('.design-wishlist-main').hide();
                        });
                    } else {
                        toastr.error(response.message, {
                            hideDuration: 10000,
                            closeButton: true,
                        });
                        $('.add-to-wishlist-main').removeClass('btn-muted');
                    }
                });

            });
        });
        // new by asad
        $(document).ready(function () {
            $('#plus-btn').click(function () {
                var quantityInput = $('#quantity');
                if(quantityInput.val() >= 9999){
                    toastr.warning('Maximun Quantity Reached', {
                        hideDuration: 10000,
                        closeButton: true,
                    });
                }else{
                    quantityInput.val(parseInt(quantityInput.val()) + 1);
                }
            });

            $('#minus-btn').click(function () {
                var quantityInput = $('#quantity');
                if (parseInt(quantityInput.val()) > 1) {
                    quantityInput.val(parseInt(quantityInput.val()) - 1);
                }
            });

            function checkRecommendedRugs(id, size) {
                let items = JSON.parse($('#item_json').val());
                items = items.Items;
                let item = items.find(item => item.ItemID === id && item.Dimensions === size);

                $('#recommended-rugs').addClass('d-none');
                $('#rugs-items-bk').html('');

                let html = '';
                @if(count($recommended_rugs))
                    @if(isset($recommended_rugs['PREPAD']) && !empty($recommended_rugs['PREPAD']))
                    if (item.PrePad) {
                        html += `<div class="form-check form-check-inline recommended-rug" data-id="PREPAD">
                                                                                    <input class="form-check-input checkbox-rugs PREPAD" type="radio" name="rugsPad" id="PREPAD" value="{{ $recommended_rugs['PREPAD']['Items'][0]['ItemID'] }}:PREPAD">
                                                                                    <div class="d-flex">
                                                                                    <img src="{{ $recommended_rugs['PREPAD']['Items'][0]['ImageName'] }}" width="50" />
                                                                                    <div style="display:flex; flex-direction:column;">
                                                                                        <span class="form-check-span ms-2 font-jaipur" for="PREPAD" style="margin-right:50px; font-size:0.7rem;"><strong>{{ $recommended_rugs['PREPAD']['Items'][0]['ItemName'] }}</strong></span>
                                                                                        <span class="form-check-span ms-2 font-jaipur" for="PREPAD" style="margin-right:50px; font-size:0.7rem;"><strong class="pad-dimension"></strong></span>
                                                                                     @auth
                                                                                         
                                                                                      @if (!in_array('.PAChart-Price', $dont_show))  <span class="form-check-span ms-2 font-jaipur" for="PREPAD" style="margin-right:50px; font-size:0.7rem;"><strong class="pad-price-pre" id="pad-price-pre"></strong></span> @endif  @endauth
                                                                                    </div>
                                                                                    </div>
                                                                                </div>`;
                    }
                    @endif

                    @if(isset($recommended_rugs['ULTPAD']) && !empty($recommended_rugs['ULTPAD']))
                    if (item.ULTPad) {
                        html += `<div class="form-check form-check-inline recommended-rug" data-id="ULTPAD">
                                                                            <input class="form-check-input checkbox-rugs ULTPAD" type="radio" name="rugsPad" id="ULTPAD" value="{{ $recommended_rugs['ULTPAD']['Items'][0]['ItemID'] }}:ULTPAD">
                                                                            <div class="d-flex">
                                                                            <img src="{{ $recommended_rugs['ULTPAD']['Items'][0]['ImageName'] }}" width="50" />
                                                                            <div style="display:flex; flex-direction:column;">
                                                                            <span class="form-check-span ms-2 font-jaipur" for="ULTPAD" style="font-size:0.7rem;"><strong>{{ $recommended_rugs['ULTPAD']['Items'][0]['ItemName'] }}</strong></span>
                                                                            <span class="form-check-span ms-2 font-jaipur" for="ULTPAD" style="margin-right:50px; font-size:0.7rem;"><strong  class="ult-dimension"></strong></span>
                                                                         @auth
                                                                             
                                                                          @if (!in_array('.PAChart-Price', $dont_show))  <span class="form-check-span ms-2 font-jaipur" for="ULTPAD" style="margin-right:50px; font-size:0.7rem;"><strong class="pad-price-ult" id="pad-price-ult">$0.00</strong></span> @endif @endauth 
                                                                            </div></div>
                                                                        </div>`;
                    }
                    @endif

                if (html) {
                    $('#rugs-items-bk').html(html);
                    $('#recommended-rugs').removeClass('d-none');

                }
                @endif
            }

            // Handle click on size option
            $('.size-option').on('click', function (e) {
                e.preventDefault();

                hide_components(['#add_to_cart']);
                var itemsJson = $('#item_json').val();
                var allItems = JSON.parse(itemsJson);

                // Get the selected size value
                var selectedSize = $(this).find('.cu-item-size').text();
                selectedid = $(this).find('.cart_item__id').val();
                console.log('selected size: ', selectedSize);
                console.log('selected id: ', selectedid);
                checkRecommendedRugs(selectedid, selectedSize)
                $('#cart_item_size').val(selectedSize);
                $('#cart_item_id').val(selectedid);
                $('#cart_item_sel_id').val(selectedid);

                $('#dropdownSizeBtn').text(selectedSize);

                var sel_cust = $('#grid_item_customer').val();
                var is_sale_rep = "{{ isset(Auth::user()->is_sale_rep) ? Auth::user()->is_sale_rep : '' }}";
                var is_customer = "{{ isset(Auth::user()->is_customer) ? Auth::user()->is_customer : '' }}";
                if(sel_cust != 0 && is_sale_rep == 1 && is_customer != 1){
                    var customer_id = sel_cust.split('::')[1].trim();
                }else{
                    var customer_id = "{{ isset(Auth::user()->customer_id) ? Auth::user()->customer_id : '' }}";
                }

                $.post('{{ route('frontend.item.ats') }}', {
                    _token: '{{ csrf_token() }}',
                    item_id: selectedid,
                    customer_id: customer_id,
                }, function (response) {
                    console.log('Main Item Standard Size on size dropdown ATS API ::', selectedid, customer_id, response.data);
                    $('#main-price').text(`{{ConstantsController::CURRENCY}}${response.data.Price.toFixed({{ConstantsController::ALLOWED_DECIMALS}})}`);
                    if(parseFloat(response.data.ActualPrice) > response.data.Price){
                        $('#main-price-cut').text(`{{ConstantsController::CURRENCY}}${parseFloat(response.data.ActualPrice).toFixed({{ConstantsController::ALLOWED_DECIMALS}})}`);
                    }else{
                        $('#main-price-cut').text('');
                    }
                    $('.other-price-text').css('display', 'block');
                    $('#map-price').text(`{{ConstantsController::CURRENCY}}${parseFloat(response.data.MAPPrice).toFixed({{ConstantsController::ALLOWED_DECIMALS}})}`);
                    $('#msrp-price').text(`{{ConstantsController::CURRENCY}}${parseFloat(response.data.MSRPPrice).toFixed({{ConstantsController::ALLOWED_DECIMALS}})}`);
                });

                var pre = $('.PREPAD');
                var ult =  $('.ULTPAD');
                $.each(allItems.Items, function(index, item) {
                    if(selectedSize == item.ItemSizeDimension.ShippingDimension || selectedSize == item.Dimensions){
                        console.log('item find: ', item.ItemID);
                        $('#cart_item_sel_id').val(item.ItemID);
                        pre.val(item.PrePad);
                        ult.val(item.ULTPad);
                        console.log('pre.val', pre.val());
                        console.log('ult.val', ult.val());
                    }
                });

                if(pre.val() != undefined){
                    $.post('{{ route('frontend.item.ats') }}', {
                        _token: '{{ csrf_token() }}',
                        item_id: pre.val(),
                        customer_id: customer_id,
                    }, function (response) {
                        console.log('Main Item Standard Size on size dropdown PREPAD ATS API :: ',pre.val(), customer_id, response.data);
                        $('.pad-price-pre').text(`{{ConstantsController::CURRENCY}}${response.data.Price.toFixed({{ConstantsController::ALLOWED_DECIMALS}})}`);
                    });

                    $.post('{{ route('frontend.item.pre.ult') }}', {
                        _token: '{{ csrf_token() }}',
                        name: 'PREPAD',
                        item_id: pre.val(),
                    }, function (response) {
                        const dimensionsArray = response.map(item => item.Dimensions);
                        $('.pad-dimension').text(dimensionsArray);
                    });
                }

                if(ult.val() != undefined){
                    $.post('{{ route('frontend.item.ats') }}', {
                        _token: '{{ csrf_token() }}',
                        item_id: ult.val(),
                        customer_id: customer_id,
                    }, function (response) {
                        console.log('Main Item Standard Size on size dropdown ULTPAD ATS API :: ',ult.val(), customer_id, response.data);
                        $('.pad-price-ult').text(`{{ConstantsController::CURRENCY}}${response.data.Price.toFixed({{ConstantsController::ALLOWED_DECIMALS}})}`);

                        if ($('#login_by_popup').length == 0 && response && $('#grid_item_customer').val() != 0 && is_sale_rep == 1 && is_customer != 1) {
                            show_components(['#add_to_cart']);
                        }else if ($('#login_by_popup').length == 0 && response && is_sale_rep != 1 && is_customer == 1){
                            show_components(['#add_to_cart']);
                        }
                    });

                    $.post('{{ route('frontend.item.pre.ult') }}', {
                        _token: '{{ csrf_token() }}',
                        name: 'ULTPAD',
                        item_id: ult.val(),
                    }, function (response) {
                        const dimensionsArray = response.map(item => item.Dimensions);
                        $('.ult-dimension').text(dimensionsArray);
                    });
                }else{
                    if ($('#login_by_popup').length == 0  && $('#grid_item_customer').val() != 0 && is_sale_rep == 1 && is_customer != 1) {
                        show_components(['#add_to_cart']);
                    }else if ($('#login_by_popup').length == 0 && is_sale_rep != 1 && is_customer == 1){
                        show_components(['#add_to_cart']);
                    }
                }
            });

            // rugpad checkbox
            $(document).on('click', '.recommended-rug', function (event) {
                event.stopPropagation();
                console.log('.recommended-rug clicked');
                console.log($(this).data('id'));
                const radioButton = $(this).find('#' + $(this).data('id'));
                console.log(radioButton);
                if (radioButton.is(':checked')) {
                    console.log('in if');
                    radioButton.prop('checked', false);
                } else {
                    console.log('in else');
                    radioButton.prop('checked', !radioButton.is(':checked'));
                }
            });

            $(document).on('click', '#get_quote_btn', function () {
                $('#inches_width').val($('#inches_width').val() ? $('#inches_width').val() : 0);
                $('#inches_length').val($('#inches_length').val() ? $('#inches_length').val() : 0);

                var allOK = true;
                $(".product-custim-size [required]").each(function () {
                    if ($(this).val() === "") {
                        allOK = false;
                        $(this).addClass("error");
                    }
                });

                if (!$('#shape').val()) {
                    $('#shape').addClass('error')
                    allOK = false;
                }

                const lengthFeet = parseFloat($('#feet_length').val()) || 0;
                const lengthInch = parseFloat($('#inches_length').val()) || 0;
                const widthFeet = parseFloat($('#feet_width').val()) || 0;
                const widthInch = parseFloat($('#inches_width').val()) || 0;
                const totalLengthFeet = lengthFeet + (lengthInch / 12);
                const totalWidthFeet = widthFeet + (widthInch / 12);
                const totalArea = totalLengthFeet * totalWidthFeet
                if (totalArea < 24) {
                    $('.orderPlacedModalErrorMessage').html(`The minimum order size should be 24 square feet or more. <br />Your current order size is:  ${totalArea.toFixed(2)} square feet.`);
                    $('#orderPlacedModalError').modal({ backdrop: 'static',  keyboard: false  });
                    $('#orderPlacedModalError').modal('show');
                    allOK = false;
                }



                if (!allOK) {
                    return false;
                } else {
                    $('#get_quote_btn').addClass('btn-muted');
                    $('.custom-input').removeClass('error');

                    var inch_l = convertInches(parseInt($('#inches_length').val()));
                    var inch_w = convertInches(parseInt($('#inches_width').val()));

                    var recommended_rug_selected = $('input[name="rugsPad"]:checked').val();
                    if (recommended_rug_selected) {
                        var cust_rug_item_id = recommended_rug_selected ? recommended_rug_selected.split(':')[0] : '';
                        var cust_rug_item_type = recommended_rug_selected ? recommended_rug_selected.split(':')[1] : '';
                        var cust_rug_price = cust_rug_item_type == "PREPAD" ? $(".pad-price-custpre").text().replace('$', '') : $(".pad-price-custult").text().replace('$', '')

                        $.ajax({
                        url: "{{ route('frontend.checkout.place_order_for_custom_size') }}",
                        type: "POST",
                        data: {
                                _token: "{{ csrf_token() }}",
                                custom_size: true,
                                length_feet: $('#feet_length').val(),
                                length_inch: $('#inches_length').val(),
                                width_feet: $('#feet_width').val(),
                                width_inch: $('#inches_width').val(),
                                shape: $('#shape').val(),
                                customer_id: $('#grid_item_customer_quote').val(),
                                item_id: $('#custom_size_item_id').val(),
                                price:  $('.custom-price-value').val(),
                                price_type: $('.custom-price-type').val(),
                                rug_pad: true,
                                rug_pad_item_id: cust_rug_item_id,
                                rug_pad_item_price: cust_rug_price
                            },
                            success: function (response) {
                                $('#get_quote_btn').removeClass('btn-muted');

                                if (response.success) {
                                    // toastr.success(response.message, {
                                    //     hideDuration: 10000,
                                    //     closeButton: true,
                                    // });
                                    //$('.orderPlacedModalMessage').text(response.message);
                                    $('.orderPlacedModalMessage').text(`
                                    Thank you for your custom order request. Someone from our office will reach out to you with details to verify your information and to make sure your order is placed with our factory.
                                    Should you have any questions in the meantime, please call Customer Service at 877-499-7847. Thanks.
                                    `);
                                    $('#orderPlacedModal').modal({
                                        backdrop: 'static',   // Prevent closing when clicking outside the modal
                                        keyboard: false       // Prevent closing when pressing the escape key
                                    });
                                    $('#orderPlacedModal').modal('show');
                                } else {
                                    toastr.error(response.message, {
                                        hideDuration: 10000,
                                        closeButton: true,
                                    });
                                }
                            }
                        })
                    }else{
                        console.log('inside else');

                        $.ajax({
                        url: "{{ route('frontend.checkout.place_order_for_custom_size') }}",
                        type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                custom_size: true,
                                length_feet: $('#feet_length').val(),
                                length_inch: $('#inches_length').val(),
                                width_feet: $('#feet_width').val(),
                                width_inch: $('#inches_width').val(),
                                shape: $('#shape').val(),
                                customer_id: $('#grid_item_customer_quote').val(),
                                item_id: $('#custom_size_item_id').val(),
                                price: $('.custom-price-value').val(),
                                price_type: $('.custom-price-type').val(),
                            },
                            success: function (response) {
                                console.log('get_quote_btn click response', response);

                                $('#get_quote_btn').removeClass('btn-muted');

                                if (response.success) {
                                    // toastr.success(response.message, {
                                    //     hideDuration: 10000,
                                    //     closeButton: true,
                                    // });
                                    $('.orderPlacedModalMessage').text(`
                                    Thank you for your custom order request. Someone from our office will reach out to you with details to verify your information and to make sure your order is placed with our factory.
                                    Should you have any questions in the meantime, please call Customer Service at 877-499-7847. Thanks.
                                    `);
                                    $('#orderPlacedModal').modal({
                                        backdrop: 'static',   // Prevent closing when clicking outside the modal
                                        keyboard: false       // Prevent closing when pressing the escape key
                                    });
                                    $('#orderPlacedModal').modal('show');
                                } else {
                                    toastr.error(response.message, {
                                        hideDuration: 10000,
                                        closeButton: true,
                                    });
                                }
                            }
                        })
                    }
                }
            })

            $(document).on('click', '#get_quote_btn_placeOrder', function () {
                $('#inches_width_cut').val($('#inches_width_cut').val() ? $('#inches_width_cut').val() : 0);
                $('#inches_length_cut').val($('#inches_length_cut').val() ? $('#inches_length_cut').val() : 0);

                var allOK = true;
                // $(".product-custim-size [required]").each(function () {
                //     if ($(this).val() === "") {
                //         allOK = false;
                //         $(this).addClass("error");
                //     }
                // });

                if (!$('#shape').val()) {
                    $('#shape').addClass('error')
                    allOK = false;
                }

                const lengthFeet = parseFloat($('#feet_length_cut').val()) || 0;
                const lengthInch = parseFloat($('#inches_length_cut').val()) || 0;
                const widthFeet = parseFloat($('#feet_width_cut').val()) || 0;
                const widthInch = parseFloat($('#inches_width_cut').val()) || 0;
                const totalLengthFeet = lengthFeet + (lengthInch / 12);
                const totalWidthFeet = widthFeet + (widthInch / 12);
                const totalArea = totalLengthFeet * totalWidthFeet;
                // if (totalArea < 24) {
                //     $('.orderPlacedModalErrorMessage').html(`The minimum order size should be 24 square feet or more. <br />Your current order size is:  ${totalArea.toFixed(2)} square feet.`);
                //     $('#orderPlacedModalError').modal({ backdrop: 'static',  keyboard: false  });
                //     $('#orderPlacedModalError').modal('show');
                //     allOK = false;
                // }



                if (!allOK) {
                    console.log(`Value of all ok is ${allOK}`);
                    return false;
                } else {
                    $('#get_quote_btn_placeOrder').addClass('btn-muted');
                    $('.custom-input').removeClass('error');

                    var inch_l = convertInches(parseInt($('#inches_length').val()));
                    var inch_w = convertInches(parseInt($('#inches_width').val()));
                    var unitPrice=parseFloat($("#priceOfItem").text()) / totalArea;
                    console.log(`unit price is ${unitPrice}`);

                    
                        $.ajax({
                        url: "{{ route('frontend.checkout.place_order_for_custom_size') }}",
                        type: "POST",
                        data: {
                                _token: "{{ csrf_token() }}",
                                custom_size: true,
                                length_feet: $('#feet_length_cut').val(),
                                length_inch: $('#inches_length_cut').val(),
                                width_feet: $('#feet_width_cut').val(),
                                width_inch: $('#inches_width_cut').val(),
                                shape: $('#shape').val(),
                                customer_id: $('#grid_item_customer_quote').val(),
                                item_id: $('#cust_rug_item_id').val(),
                                price:   parseFloat($("#priceOfItem").text()) / totalArea,
                                price_type: 'ResizeReshape',
                                // rug_pad: false,
                                // rug_pad_item_id: $("#cust_rug_item_id").val(),
                                // rug_pad_item_price: parseFloat($("#priceOfItem").text()) / totalArea,
                                 
                            },
                            success: function (response) {
                                $('#get_quote_btn').removeClass('btn-muted');

                                if (response.success) {
                                    
                                    $('.orderPlacedModalMessage').text(`
                                    Thank you for your Resize Reshape order request. Someone from our office will reach out to you with details to verify your information and to make sure your order is placed with our factory.
                                    Should you have any questions in the meantime, please call Customer Service at 877-499-7847. Thanks.
                                    `);
                                    $('#orderPlacedModal').modal({
                                        backdrop: 'static',   // Prevent closing when clicking outside the modal
                                        keyboard: false       // Prevent closing when pressing the escape key
                                    });
                                    $('#orderPlacedModal').modal('show');
                                } else {
                                    toastr.error(response.message, {
                                        hideDuration: 10000,
                                        closeButton: true,
                                    });
                                }
                            }
                        })
                    
                }
            });

            function convertInches(value) {
                switch (value) {
                    case 1:
                        return  value * 0.08;
                    case 2:
                        return  value * 0.17;
                    case 3:
                        return  value * 0.25;
                    case 4:
                        return  value * 0.33;
                    case 5:
                        return  value * 0.42;
                    case 6:
                        return  value * 0.50;
                    case 7:
                        return  value * 0.58;
                    case 8:
                        return  value * 0.67;
                    case 9:
                        return  value * 0.75;
                    case 10:
                        return  value * 0.83;
                    case 11:
                        return  value * 0.92;
                    default:
                        return 0;
            }
        }

            var myCarousel = document.querySelector('#myCarousel')
            var carousel = new bootstrap.Carousel(myCarousel, {
                interval: 5000, // Interval between slides in milliseconds
                wrap: true // Set to true to loop the carousel
            });

            // Customize carousel animation
            var isAnimating = false;
            myCarousel.addEventListener('slide.bs.carousel', function () {
                if (!isAnimating) {
                    isAnimating = true;
                    var activeIndicator = document.querySelector('.carousel-item.active');
                    var nextIndicator = activeIndicator.nextElementSibling || myCarousel.querySelector('.carousel-item:first-child');
                    if (nextIndicator) {
                        nextIndicator.style.left = '100%';
                        nextIndicator.classList.add('carousel-animate-next');
                        setTimeout(function () {
                            activeIndicator.classList.remove('active');
                            nextIndicator.classList.add('active');
                            nextIndicator.style.left = '0';
                            nextIndicator.classList.remove('carousel-animate-next');
                            isAnimating = false;
                        }, 600); // Adjust timing to match your transition duration
                    }
                }
            });

            $('.owl-carousel-images').owlCarousel({
                items: 5,
                loop: true,
                margin: 0,
                nav: true,
                dots: false,
                autoplay: false,
                autoplayTimeout: 3000,
                autoplayHoverPause: false,
                video: true,
                vertical: true, // Enable vertical mode
            })

            $('.accordion').on('shown.bs.collapse', function (event) {
                $('#show-inventory-p').addClass('mt-4');
            });

            $('.accordion').on('hidden.bs.collapse', function (event) {
                $('#show-inventory-p').removeClass('mt-4');
            });
        });

        function imagePreview(src, type = 'image'){
            var scrollTop = $(window).scrollTop();
            console.log('scrollTop', scrollTop);
            if(scrollTop > 10){
                $(window).scrollTop(0);
            }
            $('#myFotorama .fotorama__stage__frame.fotorama__loaded.fotorama__loaded--img.fotorama__active .fotorama__img').attr('src', src);
            // $('#imageModal').modal('show');
            if(type == 'image'){
                $('#video-preview').hide();

                $('#currentImage').attr('src', src);
                var element = document.querySelector('.wrapper');
                element.style.height = '100%';
                element.style.overflow = 'hidden';
                element.style.zIndex = '999999 !important';
                $('body').attr('style', 'overflow: hidden !important');
                $('.slider img').each(function() {
                    if ($(this).attr('src') === src) {
                        $(this).addClass('selected');
                    } else {
                        $(this).removeClass('selected');
                    }
                });
                $('#currentImage').removeClass('d-none');
                $('#currentVideo').addClass('d-none');
                $('.big-img-modal').show();
            }else{
                $('#image-preview').hide();
                $('#video-preview').show();
                $('#video-src').attr('src',src);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var fotorama = $('#myFotorama').fotorama().data('fotorama');

            $('#myFotorama').fotorama({
                nav: 'dots'
            });

            $('#myFotorama').on('fotorama:show', function (e, fotorama) {
                $('.fotorama__stage__frame').off('click').on('click', function() {
                    var imgSrc = $(this).find('img').attr('src');
                    $('#currentImage').attr('src', imgSrc);
                    // var element = document.querySelector('.wrapper');
                    // element.style.height = '100%';
                    // element.style.overflow = 'hidden !important';
                    // element.style.zIndex = '999999 !important';
                    // element.style.scrollBehavior = 'none !important'
                    // element.style.overflowY = 'hidden !important';
                    // $('#currentImage').removeClass('d-none');
                    // $('#currentVideo').addClass('d-none');
                    // $('.big-img-modal').show();
                });
            });
        });


        $(document).ready(function(){
            $('.accordion-button').on('click', function() {
                 $(this).toggleClass('my-accordion-button');
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#shape').val('Rectangle');
            var currentZoom = 1;
            var images = $('.slider img').map(function() { return $(this).attr('src'); }).get();
            var currentIndex = 0;

            $(document).ready(function(){
            $('.item-product-slide-carousel').owlCarousel({
                loop: true,
                margin: 10,
                nav: true,
                items: 1,
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
                });
            });

            // Update the displayed image
            function updateImage(index) {
                $('#currentImage').attr('src', images[index]);
                currentZoom = 1;
                $('#currentImage').css('transform', 'scale(1)');
                $('.slider img, .slider video').removeClass('selected');
                $('.slider img').eq(index).addClass('selected');
            }

            // Switch images on thumbnail click
            $('.slider img').click(function() {
                $('#currentImage').removeClass('d-none');
                $('#currentVideo').addClass('d-none');
                currentIndex = $(this).index();
                updateImage(currentIndex);
            });

            $('#vid-icon').click(function(){
                $('#currentImage').addClass('d-none');
                $('#currentVideo').removeClass('d-none');
            });

            // Zoom in
            $('#zoomIn').click(function() {
                currentZoom += 0.1;
                $('#currentImage').css('transform', 'scale(' + currentZoom + ')');
            });

            $('#viewer').on('mousewheel', function(event) {
                event.preventDefault();
                if (event.originalEvent.wheelDelta > 0 || event.originalEvent.detail < 0) {
                    currentZoom += 0.1;
                    $('#currentImage').css('transform', 'scale(' + currentZoom + ')');
                } else {
                    if (currentZoom > 0.1) {
                        currentZoom -= 0.1;
                        $('#currentImage').css('transform', 'scale(' + currentZoom + ')');
                    }
                }
            });

            // Zoom out
            $('#zoomOut').click(function() {
                if (currentZoom > 0.1) {
                    currentZoom -= 0.1;
                    $('#currentImage').css('transform', 'scale(' + currentZoom + ')');
                }
            });


            $('#viewer').on('mousewheel DOMMouseScroll', function(event) {
                var delta = event.originalEvent.wheelDelta || -event.originalEvent.detail;

                if (delta > 0) {
                    // Scrolling up
                    console.log('Mouse wheel up');
                } else {
                    // Scrolling down
                    console.log('Mouse wheel down');
                }
                return false;
            });


            // Next image
            $('.arrow-right').click(function() {
                currentIndex = (currentIndex + 1) % images.length;
                updateImage(currentIndex);
            });

            // Previous image
            $('.arrow-left').click(function() {
                currentIndex = (currentIndex - 1 + images.length) % images.length;
                updateImage(currentIndex);
            });

            $('#regular-service-input').change(function() {
                if ($(this).is(':checked')) {
                    console.log('checked regular');

                    $('#main-regular-service-box').addClass('checked-styles');
                    $('#main-express-service-box').removeClass('checked-styles');
                    var totalprice = $('.regular-service-total').text().replace('$', '').trim();
                    $('.custom-price-value').val(totalprice);
                    $('.custom-price-type').val('Regular');
                } else {
                    $('#main-express-service-box').addClass('checked-styles');
                    $('#main-regular-service-box').removeClass('checked-styles');
                    $('.custom-price-value').val('');
                    $('.custom-price-type').val('');
                }
            });
            $('#express-service-input').change(function() {
                if ($(this).is(':checked')) {
                    $('#main-express-service-box').addClass('checked-styles');
                    $('#main-regular-service-box').removeClass('checked-styles');
                    var totalprice = $('.express-service-total').text().replace('$', '').trim();
                    $('.custom-price-value').val(totalprice);
                    $('.custom-price-type').val('Express');
                } else {
                    $('#main-regular-service-box').addClass('checked-styles');
                    $('#main-express-service-box').removeClass('checked-styles');
                    $('.custom-price-value').val('');
                    $('.custom-price-type').val('');
                }
            });

            $('#get-quote-price-btn').click(function() {
                console.log('someone click me wrong');
                var isCheckConditions = false;
                var is_sale_rep = "{{ isset(Auth::user()->is_sale_rep) ? Auth::user()->is_sale_rep : '' }}";
                var is_customer = "{{ isset(Auth::user()->is_customer) ? Auth::user()->is_customer : '' }}";

                if(is_sale_rep == 1 && is_customer != 1){
                    if($('#grid_item_customer_quote').val() == '' || $('#grid_item_customer_quote').val() == 0){
                        toastr.error('Kindly select customer', {
                            hideDuration: 10000,
                            closeButton: true,
                        });
                        isCheckConditions = true;
                    }
                }
                var cutomeSizeItemId = $('#custom_size_item_id').val();

                if(!isCheckConditions){
                    if(is_sale_rep != 1 && is_customer == 1){
                        var customSizeCustomerVal = "{{ isset(Auth::user()->customer_id) ? Auth::user()->customer_id : '' }}";
                        console.log(`i am in condition ${customSizeCustomerVal}`);

                        $.post('{{ route('frontend.item.ats') }}', {
                            _token: '{{ csrf_token() }}',
                            item_id: cutomeSizeItemId,
                            customer_id: customSizeCustomerVal
                        }, function (response) {
                            console.log('Get Quote Custom Size ATS API (Customer) :: ',cutomeSizeItemId, customSizeCustomerVal,  response.data);
                            startBuying(cutomeSizeItemId, customSizeCustomerVal, response.data);
                        });
                    }else{
                        var customSizeCustomerVal = $('#grid_item_customer_quote option:selected').val() ? $('#grid_item_customer_quote option:selected').val().split(' :: ')[1] : '';
                       console.log(`i am in else condition ${customSizeCustomerVal}`);
                        $.post('{{ route('frontend.item.ats') }}', {
                            _token: '{{ csrf_token() }}',
                            item_id: cutomeSizeItemId,
                            customer_id: customSizeCustomerVal
                        }, function (response) {
                            console.log('Get Quote Custom Size ATS API (Sales) :: ',cutomeSizeItemId, customSizeCustomerVal,  response.data);
                            startBuying(cutomeSizeItemId, customSizeCustomerVal, response.data);
                        });
                    }

                    $('#main-express-service-box, #main-regular-service-box').removeClass('d-none');
                    $('#regular-service-input').prop('checked', true);
                    $('#main-regular-service-box').addClass('checked-styles');
                    $('#get-quote-price-btn').addClass('d-none');
                    $('#get_quote_btn').css('display', 'block');

                    show_components(['#get_quote_btn']);
                }
            });






            $('#CutAndSizeQuotePrice').click(function() {
                var isCheckConditions = false;
                var is_sale_rep = "{{ isset(Auth::user()->is_sale_rep) ? Auth::user()->is_sale_rep : '' }}";
                var is_customer = "{{ isset(Auth::user()->is_customer) ? Auth::user()->is_customer : '' }}";

                if(is_sale_rep == 1 && is_customer != 1){
                    if($('#grid_item_customer_quote').val() == '' || $('#grid_item_customer_quote').val() == 0){
                        toastr.error('Kindly select customer', {
                            hideDuration: 10000,
                            closeButton: true,
                        });
                        isCheckConditions = true;
                    }
                }
                var cutomeSizeItemId = $('#custom_size_item_id').val();
                var feet_width_cut= $('#feet_width_cut').val();
                var inches_width_cut= $('#inches_width_cut').val();
                var Total_width=(feet_width_cut*12)+(inches_width_cut*1);
                var feet_length_cut= $('#feet_length_cut').val();
                var inches_length_cut= $('#inches_length_cut').val();
                var Total_length=(feet_length_cut*12)+(inches_length_cut*1);
                let path = window.location.pathname;  // "/item/1/A01101"
                let parts = path.split("/");          // ["", "item", "1", "A01101"]
                let DesignID = parts.pop();
                

                console.log(`value of customesieItemId is ${cutomeSizeItemId}`);
                if(!isCheckConditions){
                    if(is_sale_rep != 1 && is_customer == 1){
                        console.log('this is if condtions');
                        var customSizeCustomerVal = "{{ isset(Auth::user()->customer_id) ? Auth::user()->customer_id : '' }}";
                        $.post('{{ route('frontend.item.CutAndSizeQuote_Price') }}', {
                            _token: '{{ csrf_token() }}',
                            item_id: DesignID,
                            customer_id: customSizeCustomerVal,
                            Total_width:Total_width,
                            Total_length:Total_length,

                        }, function (response) {
                            console.log(response);
                           
                            if (response.OutPut.Success) {
                                console.log('Get', response.OutPut.IsAlllowPlaceOrder);
                                if(response.OutPut.IsAlllowPlaceOrder!=='0'){
                                    console.log('condition is ture');
                                    $('#get_quote_btn_placeOrder').css('display', 'block');
                                    show_components(['#get_quote_btn_placeOrder']);
                                    
                                    $('.priceItemDiv').removeClass("d-none");
                                    console.log(response.OutPut.Price);
                                    var price=parseFloat(response.OutPut.Price).toFixed(2);
                                    console.log(price);
                                    $('#priceOfItem').text(parseFloat(response.OutPut.Price).toFixed(2));
                                    $("#cust_rug_item_id").val(response.OutPut.ItemID);
                                    
                                }
                                else{
                                    // document.getElementById('size_para').style.display = 'none';
                                    // $('#size_para').addClass('d-none');
                                    // hide_components(['#size_para']);
                                    $('#user_message').removeClass(`d-none`);
                                    $('#user_message').html(`<b>Note: </b>${response.OutPut.UserMessage}`);
                                }

                            } else {
                                console.error('Error:', response.OutPut.Message);
                                
                            }
                            
                        });
                    }else{
                                                console.log('this is else condtions');

                        var customSizeCustomerVal = $('#grid_item_customer_quote option:selected').val() ? $('#grid_item_customer_quote option:selected').val().split(' :: ')[1] : '';
                       console.log('route is ','{{ route('frontend.item.CutAndSizeQuote_Price') }}');
                        $.post('{{ route('frontend.item.CutAndSizeQuote_Price') }}', {
                            _token: '{{ csrf_token() }}',
                             item_id: DesignID,
                            customer_id: customSizeCustomerVal,
                            Total_width:Total_width,
                            Total_length:Total_length,
                        }, function (response) {
                            console.log(response);
                            
                            if (response.OutPut.Success) {
                                console.log('Get', response.OutPut.IsAlllowPlaceOrder);
                                if(response.OutPut.IsAlllowPlaceOrder!=='0'){
                                    console.log('condition is ture');
                                    $('#get_quote_btn_placeOrder').css('display', 'block');
                                    show_components(['#get_quote_btn_placeOrder']);
                                    
                                    $('.priceItemDiv').removeClass("d-none");
                                    console.log(response.OutPut.Price);
                                    var price=parseFloat(response.OutPut.Price).toFixed(2);
                                    console.log(price);
                                    $('#priceOfItem').text(parseFloat(response.OutPut.Price).toFixed(2));
                                    $("#cust_rug_item_id").val(response.OutPut.ItemID);
                                    
                                }
                                else{

                                    $('#user_message').removeClass(`d-none`);
                                    $('#user_message').html(`<b>Note: </b>${response.OutPut.UserMessage}`);
                                }

                            } else {
                                console.error('Error:', response.OutPut.Message);
                                
                            }
                            
                        });
                    }

                    // $('#main-express-service-box, #main-regular-service-box').removeClass('d-none');
                    // $('#regular-service-input').prop('checked', true);
                    // $('#main-regular-service-box').addClass('checked-styles');
                    // $('#get-quote-price-btn').addClass('d-none');
                    $('#CutAndSizeQuotePrice').addClass('d-none');
                    // $('#get_quote_btn').css('display', 'block');

                    //  show_components(['#get_quote_btn']);
                }
            });

            $('#feet_width').on('change', function() {
                var value = $(this).val();
                var fwm =  $('#feet_width').attr('max');
                if(value == fwm){
                    $('#inches_width').val(0);
                    $('.service-wi').text(0);
                }
                $('.service-wf').text(value);
            });
            $('#inches_width').on('change', function() {
                var fw = $('#feet_width').val();
                var fwm =  $('#feet_width').attr('max');
                if(fw == fwm){
                    $(this).val(0);
                    $('.service-wi').text(0);
                }else{
                    var value = $(this).val();
                    $('.service-wi').text(value);
                }
            });
            $('#feet_length').on('change', function() {
                var value = $(this).val();
                var fm =  $('#feet_length').attr('max');
                if(value == fm){
                    $('#inches_length').val(0);
                    $('.service-li').text(0);
                }
                $('.service-lf').text(value);
            });
            $('#inches_length').on('change', function() {
                var fl = $('#feet_length').val();
                var fm =  $('#feet_length').attr('max');
                if(fl == fm){
                    $(this).val(0);
                    $('.service-li').text(0);
                }else{
                    var value = $(this).val();
                    $('.service-li').text(value);
                }
            });

            // var is_sale_rep = "{{ isset(Auth::user()->is_sale_rep) ? Auth::user()->is_sale_rep : '' }}";
            // var is_customer = "{{ isset(Auth::user()->is_customer) ? Auth::user()->is_customer : '' }}";
            // $('#get_quote_btn').css('display', 'none');
            // $('#feet_width, #feet_length').on('keyup', checkCustomSizeInputs);
            // $('#shape').on('change', checkCustomSizeInputs);
            // if(is_sale_rep == 1 && is_customer != 1){
            //     $('#grid_item_customer_quote').on('change', checkCustomSizeInputs);
            // }
            // function checkCustomSizeInputs() {
            //     var feet_width = $('#feet_width').val();
            //     var feet_length = $('#feet_length').val();
            //     var shape = $('#shape').val();
            //     var custom_input = $('#grid_item_customer_quote').val();
            //     var parent_div = $('.item_customer_parent_quote');
            //     if(is_sale_rep == 1 && is_customer != 1 && feet_width && feet_length && shape && custom_input && custom_input != 0){
            //         $('#get-quote-price-btn').removeClass('d-none');
            //     }

            //     if (is_sale_rep != 1 && is_customer == 1 && feet_width && feet_length && shape) {
            //         $('#get-quote-price-btn').removeClass('d-none');
            //     }
            // }

            $('#feet_width_cut, #feet_length_cut, #inches_width_cut, #inches_length_cut, #grid_item_customer_quote').on('change', function(){
                    // $('#size_para').removeClass("d-none");
                    $('.service-wf').text($('#feet_width_cut').val());
                    $('.service-wi').text($('#inches_width_cut').val());
                    $('.service-lf').text($('#feet_length_cut').val());
                    $('.service-li').text($('#inches_length_cut').val());
                if($('#get-quote-price-btn').hasClass('d-none')){
                    
                    // $('#size_para').removeClass("d-none");
                    // document.getElementById('size_para').style.display = 'block';
                    $('#get-quote-price-btn').removeClass('d-none');
                    $('#main-regular-service-box').addClass('d-none');
                    $('#main-express-service-box').addClass('d-none');
                    hide_components(['#get_quote_btn']);
                }
            });
            $('#feet_width_cut, #feet_length_cut, #inches_width_cut, #inches_length_cut, #grid_item_customer_quote').on('change', function(){
                if($('#CutAndSizeQuotePrice').hasClass('d-none')){
                    $('#user_message').addClass(`d-none`);
                    $('#CutAndSizeQuotePrice').removeClass('d-none');
                    hide_components(['#get_quote_btn_placeOrder']);
                }
            });

            $('.mobile-tearsheet-btn').on('click', function() {
                $('.tearsheet-btn').closest('form').submit();
            });
            $('.mobile-design-add-wishlist').on('click', function() {
                $('.design-add-wishlist').trigger('click');
            });

            $('.btn-close').click(function() {
                $('.chart_item_qty').val('');
            });

        });
    </script>
    <script>
        // Select the element to observe
const targetNode = document.getElementById('user_message');

// Ensure the element exists before trying to observe it
if (targetNode) {
    // Create a new MutationObserver instance
    const observer = new MutationObserver((mutationsList) => {
        for (const mutation of mutationsList) {
            // Check if the change was to the 'class' attribute
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                // Your core logic
                if ($('#user_message').hasClass('d-none')) {
                    $('#size_para').removeClass('d-none');
                } else {
                    $('#size_para').addClass('d-none');
                }
            }
        }
    });

    // Configure the observer to watch for attribute changes
    const config = { attributes: true };

    // Start observing the target node with the specified configuration
    observer.observe(targetNode, config);
}
    </script>
@endsection


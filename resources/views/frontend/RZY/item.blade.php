@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp

@section('title', 'Item Detail Page' )
@extends('frontend.' . $active_theme -> theme_abrv . '.layouts.app')

@section('content')
<div class="wrapper">
    @include('frontend.'.$active_theme -> theme_abrv.'.components.header')
    <main class="main-content">
        <input type="hidden" id="item_json" value="{{$items_json}}">
        <section class="collection-section">
            <div class="container">
                <div class="d-flex flex-sm-column justify-content-lg-between flex-dir-col singleProduct">
                    <div class="col-lg-6 col-sm-12 col-12 d-flex responsive-mb">
                        <div class="col-md-2 products-thumbnails d-flex flex-column ">
                            <div class="overflow-pipe">
                                @php $i = 0 @endphp
                                @foreach($items['Items'][0]['ImageNameArray'] as $image)
                                <a href="javascript:void(0);">
                                    <img
                                        class="xzoom-gallery"
                                        id="thumbnail_{{$i++}}"
                                        onclick="setMainImage(this)"
                                        src="{{$image}}"
                                        xoriginal="{{$image}}"
                                        onerror="this.onerror=null; this.src='{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'"
                                        alt="{{$items['Items'][0]['ItemName']}}"
                                    />
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @if(isset($items['Items'][0]['ImageNameArray'][0]))
                        <div class="col-md-9 products-picture easyzoom-style" id="product-main-image">
                            <div class="easyzoom easyzoom--overlay">
                                <a href="{{isset($items['Items'][0]['ImageNameArray']) && $items['Items'][0]['ImageNameArray'] ? $items['Items'][0]['ImageNameArray'][0] : url('/').ConstantsController::IMAGE_PLACEHOLDER}}">
                                    <img
                                        class="xzoom"
                                        id="image_0"
                                        data-orig-src="{{isset($items['Items'][0]['ImageNameArray']) && $items['Items'][0]['ImageNameArray'] ? $items['Items'][0]['ImageNameArray'][0] : url('/').ConstantsController::IMAGE_PLACEHOLDER}}"
                                        src="{{isset($items['Items'][0]['ImageNameArray']) && $items['Items'][0]['ImageNameArray'] ? $items['Items'][0]['ImageNameArray'][0] : url('/').ConstantsController::IMAGE_PLACEHOLDER}}"
                                        xoriginal="{{isset($items['Items'][0]['ImageNameArray']) && $items['Items'][0]['ImageNameArray'] ? $items['Items'][0]['ImageNameArray'][0] : url('/').ConstantsController::IMAGE_PLACEHOLDER}}"
                                        alt="{{$items['Items'][0]['ItemName']}}"
                                        onerror="this.onerror=null; this.src='{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'"
                                    />
                                </a>
                            </div>
                        </div>
                        @else
                        <div class="col-md-9 products-picture easyzoom-style" id="product-main-image">
                            <div class="easyzoom easyzoom--overlay">
                                <a href="{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}">
                                    <img
                                        class="xzoom"
                                        id="image_0"
                                        src="{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}"
                                        xoriginal="{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}"
                                        alt="{{$items['Items'][0]['ItemName']}}"
                                        onerror="this.onerror=null; this.src='{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'"
                                    />
                                </a>
                            </div>
                        </div>
                        @endif

                    </div>
                    <div class="col-lg-6 col-sm-12 col-12 product-desc">
                        <h2 class="font-ropa product-heading" id="product-heading">{{$items['Items'][0]['DesignID']}}</h2>
                        <p class="product-description" id="product-description">{{$items['Items'][0]['ProductDescription']}}</p>
                        <div class="product-specs d-flex flex-row">
                            <div class="col-md-5 col-6 d-flex flex-column">
                                <p class="specs mb-2 UDField-template">
                                    <strong class="font-crimson FieldName"></strong> <span class="font-ropa FieldValue"> </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-10 col-12 d-flex flex-wrap flex item-udf-fields" id="item-udf-fields">
                            @foreach($items['Items'][0]['UDFFields'] as $field)
                            @if ($field['FieldName'] == 'Size')
                            @continue
                            @endif
                            <p class="specs mb-2 UDField">
                                <strong class="font-crimson FieldName"> {{$field['FieldName']}} :</strong> <span class="font-ropa FieldValue"> {{$field['Value']}} </span>
                            </p>
                            @endforeach
                            @foreach(['Country', 'Thickness'] as $key)
                            @if (isset($items['Items'][0][$key]) && $items['Items'][0][$key])
                            <p class="specs mb-2 UDField">
                                <strong class="font-crimson FieldName"> {{$key}} :</strong> <span class="font-ropa FieldValue"> {{$items['Items'][0][$key]}} </span>
                            </p>
                            @endif
                            @endforeach
                        </div>
                        <input type="hidden" id="cart_item_id" name="cart_item_id" value="">
                        <input type="hidden" id="cart_customer_id" name="cart_customer_id" value="">
                        <input type="hidden" id="cart_item_name" name="cart_item_name" value="">
                        <input type="hidden" id="cart_item_quantity" name="cart_item_quantity" value="">
                        <input type="hidden" id="cart_item_price" name="cart_item_price" value="">
                        <input type="hidden" id="cart_item_color" name="cart_item_color" value="">
                        <input type="hidden" id="cart_item_size" name="cart_item_size" value="">
                        <input type="hidden" id="cart_item_currency" name="cart_item_currency" value="">
                        <input type="hidden" id="cart_item_image" name="cart_item_image" value="">
                        <input type="hidden" id="cart_item_eta" name="cart_item_eta" value="">

                        <div class="col-sm-12 col-lg-12 d-flex flex-row product-actions mt-2 flex-wrap">
                            <div class="action-item-lg col-md-4 p-2 ps-0" id="item_variant_parent">
                                <label class="form-label font-crimson">Collections</label>
                                <select class="form-select" id="item_variant" aria-label="Default select example" name="variant">
                                    <option selected value="0">Select Collection</option>
                                </select>
                            </div>
                            <div class="action-item-lg col-md-4 d-none p-2 ps-0" id="item_color_parent">
                                <label class="form-label font-crimson">Colors</label>
                                <select class="form-select" id="item_color" aria-label="Default select example" name="color">
                                    <option selected value="0">Select Color</option>
                                </select>
                            </div>
                            <div class="action-item-lg col-md-4 p-2 ps-0 d-none sizes-main" id="item_size_parent">
                                <label class="form-label font-crimson">Size</label>
                                <select class="form-select" aria-label="Default select example" id="item_size" name="size">
                                    <option selected value="0">Select Size</option>
                                </select>
                            </div>
                            <input type="hidden" value="{{$items['Items'][0]['UserCustomerInfo']['IsSaleRep']}}" name="sale_rep">
                            <div class="action-item-lg col-md-4 p-2 ps-0 d-none customers-main flex-row position-relative" id="item_customer_parent">
                                <div class="d-flex flex-row">
                                    <label class="form-label font-crimson me-2">Customers</label>
                                    <div class="ps-0 pe-0 d-flex" id="active_customer_select">
                                        <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-html="true" data-placement="left" title="Please select the customer for whom the order needs to be created. Please note once selected than the customer cannot be changed till the order is placed or cart is emptied."></i>
                                    </div>
                                    <div class="ps-0 pe-0 d-flex" id="disabled_customer_select">
                                        <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-html="true" data-placement="left" title="The item will be added in the cart for the selected customer. If the customer needs to be changed then either checkout the order or empty the cart."></i>
                                    </div>
                                </div>
                                <select name="customer" class="form-select me-2" aria-label="Default select customer" id="item_customer">
                                    <option selected value="0">Select Customer</option>
                                </select>
                            </div>
                            <div class="action-item-lg col-md-4 p-2 ps-0 pe-0 d-none" id="qty-main">
                                <label class="form-label font-crimson">Qty</label>
                                <input id="item_qty" autocomplete="off" class="form-control" onkeydown="if(this.key==='.'){this.preventDefault();}" type="number" name="quantity" maxlength="4" value="" min="1" required>
                                <span class="form-label font-crimson" id="qty_msg"></span>
                            </div>
                            <div class="m-5 spinner-border qty-loader d-none" role="status">
                                <span class="sr-only" style="opacity:0;">Loading...</span>
                            </div>
                        </div>
                        <div class="d-flex flex-row col-md-12 justify-content-between d-none cart_main_custom col-sm-12 col-12" id="cart_main">
                            <div class="d-flex flex-row align-items-center">
                                <label class="base_price me-2">Price : </label>
                                <!-- <span class="base_price prefix d-none"> $ </span> -->
                                <span class="base_price" id="base_price"></span>
                            </div>
                            @auth()
                            <button type="submit" class="btn btn-dark add-to-cart d-none" id="add_to_cart">
                                <span class="label-text">Add to Cart</span>
                                <div class="spinner-border" role="status" style="margin: 0 auto;">
                                    <span class="sr-only" style="opacity:0;">Loading...</span>
                                </div>
                            </button>
                            @endauth
                            @guest()
                            <button type="button" class="log-in-popup-button btn btn-dark d-none" id="login_by_popup">
                                Log In
                            </button>
                            @endguest
                        </div>
                    </div>
                </div>
                @if ( isset($items['ItemsETA']) && $items['ItemsETA'] && Auth::check() )
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 m-auto mt-5 p-0 text-center product_chart">
                    <div id="prodAvlChart" class="prodAvlChart" style="display: block;">
                        <div class="mb-4">
                            <p class="heading-PAChart">&nbsp;Product Availability Chart</p>
                            <!-- <p class="heading-Date-PAChart">(Updated Daily:<span class="heading-DateTime-Color">{{date('m/d/Y')}}</span>)</p> -->
                        </div>
                        <table id="tblProductSizes" class="table" border="0" cellpadding="3" cellspacing="2" width="100%">
                            <tbody>
                                <tr>
                                    <td width="20%" align="center" class="PAChart-Size PAChart-text-Heading">Size</td>
                                    <td width="20%" align="center" class="PAChart-InStock PAChart-text-Heading">Quantity In-Stock</td>
                                    <td width="20%" align="center" class="PAChart-Within30Days PAChart-text-Heading">Quantity Within 30 Days</td>
                                </tr>
                                @foreach( $items['ItemsETA'] as $itemETA )
                                <tr class="">
                                    <td width="20%" align="center" class="PAChart-Size">{{$itemETA['Size']}}</td>
                                    <td width="20%" align="center" class="PAChart-InStock">{{$itemETA['QtyInStock']}}</td>
                                    <td width="20%" align="center" class="PAChart-Within30Days PAChart-text-Within30Days">{{$itemETA['QtyThirtyDay']}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </section>
        <section>
            @if(isset($related_designs) && !empty($related_designs) && isset($related_designs['Designs']) && count($related_designs['Designs']))
            <div class="container">
                <h1 class="section-title text-center mb-5 font-ropa"> Our Products</h1>
                <div class="col-md-12">
                    <ul class="owl-carousel owl-products">
                        @if(!empty($related_designs))
                        @foreach($related_designs['Designs'] as $design)
                        <li class="slider-item">
                            <a href="{{ $design['LinkUrl'] }}" class="d-flex flex-column text-decoration-none">
                                <figure class="align-items-center d-flex m-0 overflow-hidden" style="height: 400px;">
                                    <img src="{{CommonController::getApiFullImage($design['ImageName'])}}" onerror="this.onerror=null; this.src='{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'" />
                                    @php
                                    $badges = [
                                        [
                                            'condition'     => $design['SpecialBuy'],
                                            'background'    => 'special-buy',
                                            'label'         => 'Special Buy'
                                        ],
                                        [
                                            'condition'     => $design['Clearence'],
                                            'background'    => 'clearance',
                                            'label'         => 'Clearence'
                                        ],
                                        [
                                            'condition'     => $design['NewArrivalExpiry'],
                                            'background'    => 'new-arrival',
                                            'label'         => 'New Arrival'
                                        ],
                                        [
                                            'condition'     => $design['TopSeller'],
                                            'background'    => 'top-seller',
                                            'label'         => 'Top Seller'
                                        ],
                                    ];
                                    $count      = 0;
                                    foreach($badges as $badge)
                                        if(strtolower($badge['condition']) != 'false' && strtolower($badge['condition']) !== '') {
                                            echo '<div style="background: url(/RZY/images/labels/'.$badge['background'].'.png)" class="position-absolute handles-position"></div>';
                                        }
                                    @endphp
                                </figure>
                                <span class="product-lable">{{ $design['CollectionID']}} - {{ $design['DesignID']}}</span>
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
    @include('frontend.'.$active_theme -> theme_abrv.'.components.footer')
</div>
@include('frontend.'.$active_theme -> theme_abrv.'.components.login-modal')
@endsection
@section('styles')
<!-- <link rel="stylesheet" type="text/css" href="https://raw.githubusercontent.com/bbbootstrap/libraries/main/xzoom.css" media="all" /> -->
<link rel="stylesheet" type="text/css" href="{{asset('/RZY/vendor/easyzoom/easyzoom.css')}}?v=0.02" media="all" />
@endsection
@section('head_scripts')
<script type="text/javascript" src="{{asset('/RZY/vendor/easyzoom/easyzoom.js')}}"></script>
@endsection
@section('scripts')
<script>
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
                    '_token': '{{csrf_token()}}',
                    'refresh': 'true',
                },
                success: function(response) {
                    var new_html = $($.parseHTML(response));
                    $('#item_json').val(new_html.find('#item_json').val());

                    item_object = JSON.parse($('#item_json').val());
                    if($('.product_chart').length < 1)
                    {
                        if ( item_object.ItemsETA != '' && item_object.ItemsETA) {
                            var tableData = '';
                            item_object.ItemsETA.forEach(function(item, index){
                                tableData += '<tr><td class="PAChart-Size">'+ item['Size'] + '</td><td class="PAChart-InStock">'+ item['QtyInStock'] + '</td><td class="PAChart-Within30Days PAChart-text-Within30Days">'+ item['QtyThirtyDay'] + '</td></tr>';
                            });
                            var product_chart_html = '<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 m-auto mt-5 p-0 text-center product_chart"><div id="prodAvlChart" class="prodAvlChart" style="display: block;"><div class="mb-4"><p class="heading-PAChart">&nbsp;Product Availability Chart</p></div><table id="tblProductSizes" class="table" border="0" cellpadding="3" cellspacing="2" width="100%"><tbody><tr><td width="20%" align="center" class="PAChart-Size PAChart-text-Heading">Size</td><td width="20%" align="center" class="PAChart-InStock PAChart-text-Heading">Quantity In-Stock</td><td width="20%" align="center" class="PAChart-Within30Days PAChart-text-Heading">Quantity Within 30 Days</td></tr>' + tableData + '</tbody></table></div></div>';
                            $(product_chart_html).insertAfter('.singleProduct');
                        }
                    }
                    
                    //TODO : for some reason cart-parent is not working here - need to see why
                    $('#quick_cart').html(new_html.find('#quick_cart').html());
                    $('#profile-parent').html(new_html.find('#profile-parent').html());

                    $('#cart_main').append(new_html.find('#add_to_cart'));
                    $('#cart_main').find('#add_to_cart').removeClass('d-none');
                    $('#cart_main').find('#login_by_popup').remove();

                    $('#add_to_cart').off('click');
                    $('#add_to_cart').on('click', function(e) {
                        pushToCart();
                    });
                    if (callback) {
                        callback();
                    }
                    getQuantity($("#item_size option:selected").val().trim());
                }
            });
        }
    }

    function refresh_product(ItemID) {
        item_object.Items.forEach(function(item, index) {
            if (item.ItemID == ItemID) {
                $('#product-main-image').fadeOut(400, function() {
                    $("#image_0").attr('src', item.ImageNameArray[0]).attr('onerror', "this.src='{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'");
                    setMainImage({src: item.ImageNameArray[0]});
                }).fadeIn(400);

                $('#image_0').attr('alt', item.ItemName);

                $('.overflow-pipe').html('');
                for (var i = 0; i < item.ImageNameArray.length; i++) {
                    var anchor = $('<a href="javascript:void(0);"> </a>');
                    anchor.append($('<img />', {
                        id: 'thumbnail_' + i,
                        src: item.ImageNameArray[i],
                        'data-orig-src': item.ImageNameArray[i],
                        onclick: "setMainImage(this)",
                        onerror: "this.src='{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'"
                    }));
                    $('.overflow-pipe').append(anchor);
                    //$('#thumbnail_'+i).attr('src',item.ImageNameArray[i]).attr('onerror', "this.src='{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'");
                }
                $('#product-heading').text(item.DesignID);

                if (item.ProductDescription == null) {
                    item.ProductDescription = '';
                }
                var description = item.ProductDescription.toString().trim();
                if (description.length == 0) {
                    description = ''; // as per recommendation of Shahid Sb
                }
                $('#product-description').text(description);

                $('#item-udf-fields').html("");
                item.UDFFields.forEach(function(field, f_index) {
                    if (field.FieldName.trim() != 'Size') {
                        var field_clone = $('.UDField-template').clone(true);
                        field_clone.find('.FieldName').html(field.FieldName.trim() + " : ");
                        field_clone.find('.FieldValue').html(field.Value.trim());
                        field_clone.removeClass('UDField-template');
                        field_clone.addClass('UDField');
                        $('#item-udf-fields').append(field_clone);
                    }
                });

                if (item.Country && item.Country != '') {
                    $('#item-udf-fields').append('<p class="specs mb-2 UDField"><strong class="font-crimson FieldName">Country : </strong> <span class="font-ropa FieldValue">' + item.Country + '</span></p>');
                }
                if (item.Thickness && item.Thickness != '') {
                    $('#item-udf-fields').append('<p class="specs mb-2 UDField"><strong class="font-crimson FieldName">Thickness : </strong> <span class="font-ropa FieldValue">' + item.Thickness + '</span></p>');
                }
            }
        });
    }

    function hide_components(class_arr) {
        class_arr.forEach(function(component) {
            $(component).removeClass('d-none');
            $(component).addClass('d-none');
        });
    }

    function show_components(class_arr) {
        class_arr.forEach(function(component) {
            $(component).removeClass('d-none');
        });
    }

    function init() {
        item_object = JSON.parse($('#item_json').val());
        var counter = 0;

        $('#item_variant').html('');
        item_object.Items.forEach(function(item, index) {
            if(item.ItemName == '') return;
            if (!$('#item_variant option:contains(' + item.ItemName + ')').length) {
                $('#item_variant').append($('<option>', {
                    value: item.ItemID,
                    text: item.ItemName
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
    }

    function getColors(ItemName) {
        var counter = 0;
        show_components(['#item_color_parent']);
        hide_components(['#item_size_parent', '#item_customer_parent', '#qty-main', '#cart_main', '.base_price', '#add_to_cart', '#login_by_popup']);
        $('#item_color').html('<option value="0">Select Color</option>');
        item_object.Items.forEach(function(item, index) {
            if (item.ItemName.trim() == ItemName.trim()) {
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

    function getSizes(ItemName, ItemColor, ItemValue) {
        if (ItemValue == '0') {
            hide_components(['#item_size_parent']);
        } else {
            show_components(['#item_size_parent']);
        }
        hide_components(['#item_customer_parent', '#qty-main', '#cart_main', '.base_price', '#add_to_cart', '#login_by_popup']);
        $('#item_size').html('<option value="0">Select Size</option>');
        item_object.Items.forEach(function(item, index) {
	    if ((item.ItemName.trim() == ItemName.trim()) && (item.ItemColor.trim() == ItemColor.trim())) {
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
    }

    function getQuantity(ItemID) {
        if (ItemID == '0') {
            hide_components(['#item_customer_parent']);
            hide_components(['#qty-main', '#cart_main', '.base_price', '#add_to_cart']);
            return;
        }
        item_object.Items.forEach(function(item, index) {
            if ((item.ItemID == ItemID)) {
                $('#item_customer').prop('disabled', false);
                if (item.UserCustomerInfo.IsSaleRep == 1) {
                    getCustomers(item);
                    if (item.UserCustomerInfo.CustomerSet) {
                        $('#item_customer').prop('disabled', 'disabled');
                        var split_arr = $('#item_customer').val().split(' :: ');
                        var customer_id = split_arr[1].trim();
                        $('#qty-main, .base_price').addClass('muted');
                        if (!$('#qty-main').is(':visible'))
                            show_components(['.qty-loader']);
                        $.post('{{route("frontend.item.ats")}}', {
                            _token: '{{csrf_token()}}',
                            item_id: ItemID,
                            customer_id: customer_id
                        }, function(response) {
                            startBuying(item.ItemID, customer_id, response.data);
                        });
                    }
                } else {
                    $('#qty-main, .base_price').addClass('muted');
                    if (!$('#qty-main').is(':visible'))
                        show_components(['.qty-loader']);
                    $.post('{{route("frontend.item.ats")}}', {
                        _token: '{{csrf_token()}}',
                        item_id: ItemID,
                        customer_id: item.UserCustomerInfo.Customers[0].CustomerID
                    }, function(response) {
                        startBuying(item.ItemID, item.UserCustomerInfo.Customers[0].CustomerID, response.data);
                    });
                }
                if ($('#checkOut_popup').is(':visible'))
                    $('#checkOut_popup').hide();
            }
        });
    }

    function getCustomers(item) {
        show_components(['#item_customer_parent']);
        hide_components(['#qty-main', '#cart_main', '.base_price', '#add_to_cart', '#login_by_popup']);
        $('#item_customer').html('<option value="0">Select Customer</option>');
        item.UserCustomerInfo.Customers.forEach(function(Customer, index) {
            if (item.UserCustomerInfo.CustomerSet) {
                $('#active_customer_select').addClass('d-none');
                $('#disabled_customer_select').removeClass('d-none');
                if (Customer.CustomerID == item.UserCustomerInfo.CustomerSet) {
                    $('#item_customer').append($('<option>', {
                        value: item.ItemID + ' :: ' + Customer.CustomerID,
                        text: Customer.CompanyName + ' (' + Customer.CustomerID + ')',
                        selected: 'selected'
                    }));
                }
            } else {
                $('#active_customer_select').removeClass('d-none');
                $('#disabled_customer_select').addClass('d-none');
                $('#item_customer').append($('<option>', {
                    value: item.ItemID + ' :: ' + Customer.CustomerID,
                    text: Customer.CompanyName + ' (' + Customer.CustomerID + ')'
                }));
            }
        });
    }

    function getCartReady(item_customer_id) {
        $('#qty-main, .base_price').addClass('muted');
        var split_arr = item_customer_id.split(' :: ');
        var item_id = split_arr[0].trim();
        var customer_id = split_arr[1].trim();
        item_object.Items.forEach(function(item, index) {
            if ((item.ItemID == item_id)) {
                item.UserCustomerInfo.Customers.forEach(function(Customer, index) {
                    if (Customer.CustomerID == customer_id) {
                        if (!$('#qty-main').is(':visible'))
                            show_components(['.qty-loader']);
                        $.post('{{route("frontend.item.ats")}}', {
                            _token: '{{csrf_token()}}',
                            item_id: item_id,
                            customer_id: customer_id
                        }, function(response) {
                            startBuying(item_id, customer_id, response.data);
                        });
                    }
                });
            }
        });
    }

    function startBuying(ItemID, CustomerID, ATSInfo) {
        if ($('#login_by_popup').length) {
            show_components(['#login_by_popup', '#cart_main']);
            hide_components(['#qty-main', '.base_price', '.qty-loader']);
            $('#qty-main, .base_price').removeClass('muted');
        } else {
            hide_components(['.qty-loader']);
            show_components(['#qty-main', '#cart_main', '.base_price', '#add_to_cart']);
            $('#qty-main, .base_price').removeClass('muted');
        }
        $('#qty_msg').text(getQuantityMessage(ATSInfo));
        $('#item_qty').attr('max', ATSInfo.OnlyMaxQuantity ? ATSInfo.ATSQty : 9999);
        $('#base_price').html((ATSInfo.Price).toLocaleString('en-US', {
            style: 'currency',
            currency: 'USD',
        }));
        item_object.Items.forEach(function(item, index) {
            if ((item.ItemID == ItemID)) {
                $('#cart_item_id').val(item.ItemID);
                $('#cart_customer_id').val(CustomerID);
                $('#cart_item_name').val(item.ItemName);
                $('#cart_item_quantity').val($('#item_qty').val(0));
                $('#cart_item_price').val(ATSInfo.Price);
                $('#cart_item_color').val($("#item_color option:selected").text());
                $('#cart_item_size').val($("#item_size option:selected").text());
                $('#cart_item_currency').val('$');
                $('#cart_item_image').val(item.ImageNameArray[0]);
                $('#cart_item_eta').val(ATSInfo.ETADate);
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

    function pushToCart() {
        $('#add_to_cart').addClass('btn-muted');
        $('#cart_item_quantity').val($('#item_qty').val());
        if ((/^\+?[1-9]\d*/).test(parseInt($('#item_qty').val()))) {
            $.ajax({
                method: 'POST',
                url: '{{route("frontend.cart.add")}}',
                data: {
                    '_token': '{{csrf_token()}}',
                    'cart_item_id': $('#cart_item_id').val(),
                    'cart_customer_id': $('#cart_customer_id').val(),
                    'cart_item_name': $('#cart_item_name').val(),
                    'cart_item_quantity': $('#cart_item_quantity').val(),
                    'cart_item_price': $('#cart_item_price').val(),
                    'cart_item_color': $('#cart_item_color').val(),
                    'cart_item_size': $('#cart_item_size').val(),
                    'cart_item_currency': $('#cart_item_currency').val(),
                    'cart_item_image': $('#cart_item_image').val(),
                    'cart_item_data': $('#item_json').val(),
                    'cart_item_eta': $('#cart_item_eta').val()
                },
                success: function(response) {
                    if (response.success) {
                        if ($('#item_json').length) {
                            refreshItemJson(function() {
                                toastr.success(response.message, {
                                    hideDuration: 10000,
                                    closeButton: true,
                                });
                                $('#add_to_cart').removeClass('btn-muted');
                            });
                        } else {
                            refreshUser('quick-cart', function() {
                                refreshUser('profile', function() {
                                    $("#quick_cart").removeClass('d-none');
                                    toastr.success(response.message, {
                                        hideDuration: 10000,
                                        closeButton: true,
                                    });
                                    $('#add_to_cart').removeClass('btn-muted');
                                });
                            });
                        }
                    } else {
                        toastr.warning(response.message, {
                            hideDuration: 10000,
                            closeButton: true,
                        });
                        $('#add_to_cart').removeClass('btn-muted');
                    }
                },
                error: function(response) {
                    toastr.warning(response.message, {
                        hideDuration: 10000,
                        closeButton: true,
                    });
                    $('#add_to_cart').removeClass('btn-muted');
                }
            });
        } else {
            toastr.warning("Please enter a valid value", {
                hideDuration: 10000,
                closeButton: true,
            });
            $('#add_to_cart').removeClass('btn-muted');
        }
    }

    function bind_events() {

        $("#item_variant").off('change').on('change', function() {
            refresh_product($(this).val());
            getColors($("#item_variant option:selected").text().trim());
        });
        
        $("#item_color").off('change').on('change', function() {
            refresh_product($(this).val());
            getSizes($("#item_variant option:selected").text().trim(), $('#item_color option:selected').text().trim(), $(this).val());
        });
        
        $("#item_size").off('change').on('change', function() {
            refresh_product($(this).val());
            getQuantity($(this).val());
        });

        $("#item_customer").off('change').on('change', function() {
            var split_arr = $(this).val().split(' :: ');
            var item_id = split_arr[0].trim();
            refresh_product(item_id);
            getCartReady($(this).val());
        });

        $("#add_to_cart").off('click').on('click', function(e) {
            pushToCart();
        });
    }

    $(document).ready(function() {
        init();
        bind_events();
        $('.owl-carousel').owlCarousel({
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
                    loop: false
                }
            }
        }).trigger('stop.owl.autoplay');
    });
</script>
@endsection

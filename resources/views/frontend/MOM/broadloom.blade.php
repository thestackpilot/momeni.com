@php
    // $active_theme object is available containing the theme developer json loaded.
    // This is for the theme developers who want to load further view assets

    use App\Http\Controllers\ConstantsController;
    use App\Http\Controllers\CommonController;

@endphp

@section('title', 'Item Detail Page')
@extends('frontend.' . $active_theme->theme_abrv . '.layouts.app')

@section('content')
    <div class="wrapper">
        @include('frontend.' . $active_theme->theme_abrv . '.components.header')
        <main class="main-content">
            @include('frontend.' . $active_theme->theme_abrv . '.components.breadcrumbs')
            <input type="hidden" id="customer_id" value="{{ $customer_id }}"></input>
            <input type="hidden" id="item_json" value="{{ $item_json }}"></input>
            <input type="hidden" name="" id="item_id" value="{{ $item['ItemID'] }}">
            <input type="hidden" name="" id="cut_pieces_json" value="">
            {{-- <input type="hidden" name="" id="item_id" value="{{$roll_pieces['OutPut']["RollsAndCutPieces"][0]['ItemName']}}"> --}}
            <input type="hidden" name="" id="roll_id" value="">
            <input type="hidden" name="" id="cutpiece_id" value="">
            <input type="hidden" name="" id="atslength" value="">
            <input type="hidden" name="" id="totalwidth" value="">
            <input type="hidden" name="" id="totalsqft" value="">
            <input type="hidden" name="" id="cuttype" value="">
            <input type="hidden" name="" id="locationid" value="">
            <input type="hidden" name="" id="sergingtypeno" value="">
            <input type="hidden" name="" id="charges" value="">
            <input type="hidden" name="" id="desc" value="">
            <input type="hidden" name="" id="TempSalesOrderNo" value="">
            <input type="hidden" id="cart_item_id" name="cart_item_id" value="">
            <input type="hidden" id="cart_item_name" name="cart_item_name" value="">
            <input type="hidden" id="cart_item_quantity" name="cart_item_quantity" value="">
            <input type="hidden" id="cart_item_price" name="cart_item_price" value="">
            <input type="hidden" id="cart_item_color" name="cart_item_color" value="">
            <input type="hidden" id="cart_item_size" name="cart_item_size" value="">
            <input type="hidden" id="cart_item_currency" name="cart_item_currency" value="">
            <input type="hidden" id="cart_item_image" name="cart_item_image" value="">
            <input type="hidden" id="cart_item_eta" name="cart_item_eta" value="">
            {{-- <input type="hidden" id="cart_item_oak" name="cart_item_oak" value="{{isset($active_theme_json->general->oak_items->enabled) && $active_theme_json->general->oak_items->title == strtoupper($collection_id) ? '{"oak": 1}' : '{"oak": 0}'}}"> --}}

            <div class="site-wrapper-reveal">
                <div class="broadloom-wrapper">
                    <div style="font-size: 28px;"><strong>{{$item['ItemName']}}</strong></div>
                    <div class="card">
                        <div class="card-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="broadloom-hearder"
                                             style="background-image: url('{{ $item['ImageNameArray'][0] }}')"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="broadloom-form">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <label for=""><strong> Roll ID / Cut Piece
                                                                        ID</strong></label>
                                                            </div>
                                                            <div class="col-6 text-right">
                                                                <label for=""><strong> Length:
                                                                        <span style="color: #660000" id="lenght-width">0' - 0'' /
                                                                    </strong> <strong><span id="max-width"
                                                                                            style="color: #660000">0' - 0''</span></strong></label>
                                                            </div>
                                                        </div>
                                                        <select name="" id="roll_pieces" class="form-control">
                                                            <option value="" width="" length="">Select
                                                                Option
                                                            </option>
                                                            @foreach ($roll_pieces['OutPut']['RollsAndCutPieces'] as $row)
                                                                @php
                                                                    $lenght_f = (int) floor($row['ATSLength'] / 12);
                                                                    $lenght_i = (int)($row['ATSLength'] % 12);
                                                                    $available_l = $lenght_f . "'" . " - " . $lenght_i . "''";
                                                                @endphp
                                                                <option value="{{$row['RollID']}}"
                                                                        width="{{$row['TotalWidth']}}"
                                                                        length="{{$row['ATSLength']}}"
                                                                        SQFT="{{$row['TotalSQFT']}}"
                                                                        cutpieceID="{{$row['CutPieceID']}}"
                                                                        cutType="{{$row['CutType']}}"
                                                                        location="{{$row['LocationID']}}">
                                                                    {{$row['RollID']}} @if(isset($row['CutPieceID']) && !empty($row['CutPieceID']))
                                                                        ( {{$row['CutPieceID']}} )
                                                                    @endif - ( {{$available_l}} )
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for=""><strong> Cut Length </strong></label>
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control Tlength"
                                                                           id="Tlength" placeholder=""
                                                                           style="text-align:right;" min="0">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text"><strong>
                                                                                Ft</strong></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="input-group">
                                                                    {{-- <select name="" id="TlengthInch"
                                                                        class="form-control">
                                                                        <option value="0">0</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                        <option value="5">5</option>
                                                                        <option value="6">6</option>
                                                                        <option value="7">7</option>
                                                                        <option value="8">8</option>
                                                                        <option value="9">9</option>
                                                                        <option value="10">10</option>
                                                                        <option value="11">11</option>
                                                                    </select> --}}
                                                                    <input type="number"
                                                                           class="form-control TlengthInch"
                                                                           id="TlengthInch" placeholder=""
                                                                           style="text-align:right;" min="0" max="11">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            <strong>In</strong></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for=""><strong>Cut Width</strong></label>
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control Twidth"
                                                                           id="Twidth" placeholder=""
                                                                           style="text-align:right;" min="0">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            <strong>Ft</strong></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="input-group">
                                                                    {{-- <select name="" id="TwidthInch"
                                                                        class="form-control">
                                                                        <option value="0">0</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                        <option value="5">5</option>
                                                                        <option value="6">6</option>
                                                                        <option value="7">7</option>
                                                                        <option value="8">8</option>
                                                                        <option value="9">9</option>
                                                                        <option value="10">10</option>
                                                                        <option value="11">11</option>
                                                                    </select> --}}
                                                                    <input type="number" class="form-control TwidthInch"
                                                                           id="TwidthInch" placeholder=""
                                                                           style="text-align:right;" min="0" max="11">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            <strong>In</strong></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for=""><strong> SQ-FT Price ($)</strong></label>
                                                        <input type="text" class="form-control" id="sq-ft"
                                                               value=""
                                                               disabled style="text-align:right;">
                                                        <input type="hidden" class="form-control" id="ats-qty"
                                                               value=""
                                                               disabled style="text-align:right;">
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for=""><strong> SQ-YRD Price ($)</strong></label>
                                                        <input type="text" class="form-control" id="sq-yrd"
                                                               value=""
                                                               disabled style="text-align:right;">
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for=""><strong> EXT Price ($)</strong></label>
                                                        <input type="text" class="form-control" id="sq-ext"
                                                               value=""
                                                               disabled style="text-align:right;">
                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-sm-12">
                                                    <div class="form-group">
                                                        <label for=""><input type="checkbox" name="" id="surging_check">
                                                            <strong> With Serging </strong></label>
                                                        <select name="" id="surging_options" class="form-control"
                                                                disabled="disabled">
                                                            <option value="0" charges="">Select Option</option>
                                                            @foreach ($surging_types['OutPut']['SurgingTypesList'] as $row)
                                                                <option value="{{$row['SergingTypeNo']}}"
                                                                        charges="{{$row['Charges']}}"
                                                                        desc="{{$row['Description']}}">{{$row['Description']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for=""><strong> Serging Charges ($)</strong></label>
                                                        <input class="form-control" type="text" name=""
                                                               id="surging_charges" value="" disabled
                                                               style="text-align:right;">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for=""><strong>Customer Instructions</strong> </label>
                                                        <textarea name="" id="cust-inst" class="form-control"
                                                                  rows="5"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12" id="cut_piece_parent">
                                                    {{-- <div class="badge badge-primary broadloom-badge">
                                                        10' - 0" x 10' - 0"
                                                        <a class="bg-primary" href="javascript:void(0)"><i class="fa fa-times"></i></a>
                                                    </div> <div class="badge badge-primary broadloom-badge">
                                                        10' - 0" x 10' - 0"
                                                        <a class="bg-primary" href="javascript:void(0)"><i class="fa fa-times"></i></a>
                                                    </div> <div class="badge badge-primary broadloom-badge">
                                                        10' - 0" x 10' - 0"
                                                        <a class="bg-primary" href="javascript:void(0)"><i class="fa fa-times"></i></a>
                                                    </div> <div class="badge badge-primary broadloom-badge">
                                                        10' - 0" x 10' - 0"
                                                        <a class="bg-primary" href="javascript:void(0)"><i class="fa fa-times"></i></a>
                                                    </div> <div class="badge badge-primary broadloom-badge">
                                                        10' - 0" x 10' - 0"
                                                        <a class="bg-primary" href="javascript:void(0)"><i class="fa fa-times"></i></a>
                                                    </div> <div class="badge badge-primary broadloom-badge">
                                                        10' - 0" x 10' - 0"
                                                        <a class="bg-primary" href="javascript:void(0)"><i class="fa fa-times"></i></a>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="cut-pieces-wrapper">
                                            <div style="font-size: 22px;"><strong>Show Cut</strong></div>
                                            <div class="cut-pieces" id="cut-pieces">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mt-5 text-center">
                                        <button class="add-piece-btn broadloom-btns add-cut-piece-btn"
                                                id="cut_piece_btn">Add Cut Piece <i class="fa fa-long-arrow-right"></i>
                                        </button>
                                        <button class="show-piece-btn broadloom-btns d-none" id="show-cut-piece-btn">
                                            Show Cut Piece <i class="fa fa-long-arrow-right"></i></button>
                                        <button class="add-to-cart-broadloom-btn broadloom-btns" id="add_to_cart">Add to
                                            Cart <i class="fa fa-long-arrow-right"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        @include('frontend.' . $active_theme->theme_abrv . '.components.footer')
    </div>
    @include('frontend.' . $active_theme->theme_abrv . '.components.login-modal')
@endsection

@section('scripts')
    <script src="{{ asset('MOM/js/CutPiece.js') }}"></script>
    <script>
        var ATS_ROLL_LENGHT = "";
        var ATS_ROLL_WIDTH = "";
        let originalWidthInFeet = 0;
        let originalWidthInInches = 0;
        let originalHeightInFeet = 0;
        let originalHeightInInches = 0;
        var item_object = ""; //get the json decoded object
        var customerID = $('input[name="sale_rep"]').val() == 1 ? '' : 1;
        // Instantiate EasyZoom instances
        var $easyzoom = $('.easyzoom').easyZoom({
            loadingNotice: '',
            errorNotice: ''
        });

        // TODO : Please add taoster library as it is needed - Asfand needs to do it
        function init_sliders() {
            $('.product-details-thumbs-2').each(function () {
                var $this = $(this);
                var $details = $this.siblings('.product-details-images-2');
                $this.slick({
                    arrows: true,
                    slidesToShow: 5,
                    slidesToScroll: 1,
                    autoplay: false,
                    autoplaySpeed: 3000,
                    vertical: true,
                    verticalSwiping: true,
                    dots: false,
                    infinite: false,
                    focusOnSelect: false,
                    centerMode: false,
                    centerPadding: 0,
                    prevArrow: '<i class="icon-arrow-up arrow-up"></i>',
                    nextArrow: '<i class="icon-arrow-down arrow-down"></i>',
                    // asNavFor: $details,
                    responsive: [{
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                        {
                            breakpoint: 991,
                            settings: {
                                slidesToShow: 3,
                            }
                        },
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: 3,
                                vertical: false,
                                prevArrow: '<i class="icon-arrow-left arrow-prv"></i>',
                                nextArrow: '<i class="icon-arrow-right arrow-next"></i>'
                            }
                        },
                        {
                            breakpoint: 479,
                            settings: {
                                slidesToShow: 3,
                                vertical: false,
                                prevArrow: '<i class="icon-arrow-left arrow-prv"></i>',
                                nextArrow: '<i class="icon-arrow-right arrow-next"></i>'
                            }
                        }
                    ]
                });
            });
        }

        function setMainImage(img) {
            $("#image_0").attr("src", img.src);
            $("#image_0").closest('a').attr("href", img.src);
            $easyzoom.data('easyZoom').swap(img.src, img.src);
        }

        function refreshItemJson(callback) {
            console.log("In refreshItemJson");
            if ($('#item_json').length) {
                $.ajax({
                    method: 'GET',
                    url: window.location.href,
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'refresh': true,
                    },
                    success: function (response) {
                        var new_html = $($.parseHTML(response));
                        console.log('Length: ', new_html.find('#item_json').length);
                        console.log('Value: ', new_html.find('#item_json').val());
                        $('#item_json').html(new_html.find('#item_json').html());

                        item_object = JSON.parse($('#item_json').val());

                        $('#cart-parent').html(new_html.find('#cart-parent').html());
                        $('#quickCart').html(new_html.find('#quickCart').html());
                        $('#profile-parent').html(new_html.find('#profile-parent').html());

                        $('#cart_main').html(new_html.find('#cart_main').html());
                        $('#cart_main').find('#add_to_cart').removeClass('d-none');
                        $('#cart_main').find('#login_by_popup').remove();

                        $('#add_to_cart').off('click');
                        $('#add_to_cart').on('click', function (e) {
                            if (
                                $('input[name="sale_rep"]').val() == 1 &&
                                customerID.length == 0
                            )
                                toastr.warning('Please select a customer...', {
                                    hideDuration: 10000,
                                    closeButton: true,
                                });
                            else
                                pushToCart();
                        });
                        if (callback) {
                            callback();
                        }
                        // getQuantity($("#item_size input:radio[name=size]:checked").val().trim());
                    }
                });
            }
        }

        function refresh_product(ItemID) {
            item_object.Items.forEach(function (item, index) {
                if (item.ItemID == ItemID) {
                    $('#product-main-image').fadeOut(400, function () {
                        $("#image_0").attr('src', item.ImageNameArray[0]).attr('onerror',
                            "this.src='{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}'");
                    }).fadeIn(400);
                    setMainImage({
                        src: item.ImageNameArray[0]
                    });

                    $('#image_0').attr('alt', item.ItemName);
                    $('#product_thumbnails').html('');
                    $('.product-details-thumbs-2').slick('unslick');
                    $('.product-details-thumbs-2').html('');
                    for (var i = 0; i <= item.ImageNameArray.length; i++) {
                        var div = $('<div class="sm-image"> </div>');
                        div.append($('<img />', {
                            id: 'thumbnail_' + i,
                            src: item.ImageNameArray[i],
                            onclick: "setMainImage(this)",
                            onerror: "this.src='{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}'"
                        }));
                        $('#product_thumbnails').append(div);
                        //$('#thumbnail_'+i).attr('src',item.ImageNameArray[i]).attr('onerror', "this.src='{{ url('/') . ConstantsController::IMAGE_PLACEHOLDER }}'");
                    }
                    init_sliders();
                    // console.log(`${item.ItemName}${($("label", $("input[name='color']:checked").parent()).attr('data-color')).replace(/^0+$/, '').replace(/0+$/, '')}`);
                    $('#product-heading').html(
                        `${item.ItemName}<b>${($(`label[for="color_${$("input[name='color']:checked").val()}"]`).attr('data-color')).replace(/^0+$/, '').replace(/0+$/, '')}</b>`
                    );

                    if (item.ProductDescription == null) {
                        item.ProductDescription = '';
                    }
                    var description = item.ProductDescription.toString().trim();
                    if (description.length == 0) {
                        // description = 'Not Available';
                    }
                    $('#product-description').html(description);


                    $('#item-udf-fields').html("");
                    if (item.UDFFields.length > 0) {
                        item.UDFFields.forEach(function (field, f_index) {
                            if (field.FieldName.trim() != 'Color' && field.FieldName.trim() != 'Size') {
                                var tr = $("<tr class = 'UDField'> </tr>");

                                tr.append($('<td>', {
                                    text: field.FieldName.trim() + " : "
                                }));

                                tr.append($('<td>', {
                                    text: field.Value.trim()
                                }));

                                $('#item-udf-fields').append(tr);
                            }
                        });
                    } else {
                        if (!$('.not-available').length) {
                            $('.quickview-peragraph .detiel-heading').append(
                                '<span class="not-available">: N/A</span>');
                        }
                    }
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
                $(component).removeClass('d-none');
            });
        }

        function init() {
            var counter = 0;
            console.log("item_object: ", item_object);
            $('#item_variant').html('');
            item_object.Items.forEach(function (item, index) {
                if (!$('#item_variant input[name=variant]:contains(' + item.ItemName + ')').length) {
                    $('#item_variant').append($('<input>', {
                        value: item.ItemID,
                        text: item.ItemName,
                        class: 'checkbox-tools',
                        type: 'radio',
                        name: 'variant',
                        id: 'variant_' + item.ItemID
                    }));

                    $('#item_variant').append($('<label>', {
                        text: item.ItemName,
                        class: 'for-checkbox-tools variant',
                        for: 'variant_' + item.ItemID
                    }));
                    counter++;
                }
            });
            if (counter > 1) {
                $("#item_variant input:radio[name=variant]:first").attr('checked', true);
            } else if (counter == 1) {
                $("#item_variant input:radio[name=variant]:first").attr('checked', true);
                hide_components(['#item_variant_parent']);
            } else {
                hide_components(['#item_variant_parent']);
            }
            getColors($("#item_variant input:radio[name=variant]:checked").text());
        }

        function getColors(ItemName) {
            show_components(['#item_color_parent']);
            hide_components(['#item_size_parent', '#item_customer_parent', '#qty-main', '#cart_main', '#add_to_cart',
                '#login_by_popup'
            ]);
            $('#item_color').html('');
            item_object.Items.forEach(function (item, index) {
                if (item.ItemName.trim() == ItemName.trim()) {
                    if (!$('#item_color input[name=color]:contains(' + item.ItemColor + ')').length) {
                        $('#item_color').append($('<input>', {
                            value: item.ItemID,
                            text: item.ItemColor,
                            class: 'checkbox-tools',
                            type: 'radio',
                            name: 'color',
                            id: 'color_' + item.ItemID,
                        }));

                        $('#item_color').append($('<label>', {
                            title: item.ItemColor,
                            'data-toggle': "tooltip",
                            'data-color': item.ColorID,
                            class: 'for-checkbox-tools',
                            for: 'color_' + item.ItemID
                        }).append(
                            $('<img>', {
                                src: item.ItemColorImage,
                                alt: item.ItemColor
                            })
                        ));
                    }
                }
            });

            bindClicks();

            var color = `{{ isset($color) && $color ? $color : '' }}`;
            var color_node = color.length ? `[data-color="${color}"]` : '#item_color label:first';
            $(color_node).click();
            setTimeout(function () {
                $(`${color_node}, #${$(color_node).attr('for')}`).click();
            }, 1500);
        }

        function getSizes(ItemName, ItemColor, ItemValue) {
            show_components(['#item_size_parent']);
            hide_components(['#item_cover_parent', '#item_customer_parent', '#qty-main', '#cart_main', '#add_to_cart',
                '#login_by_popup'
            ]);
            $('#item_size').html('');
            item_object.Items.forEach(function (item, index) {
                if ((item.ItemName.trim() == ItemName.trim()) && (item.ItemColor.trim() == ItemColor.trim())) {
                    if (!$('#item_size input[name=size]:contains(' + item.ItemSize + ')').length) {
                        $('#item_size').append($('<input>', {
                            value: item.ItemID,
                            text: item.ItemSize,
                            class: 'checkbox-tools',
                            type: 'radio',
                            name: 'size',
                            id: 'size_' + item.ItemID
                        }));

                        $('#item_size').append($('<label>', {
                            text: item.ItemSize,
                            class: 'for-checkbox-tools',
                            for: 'size_' + item.ItemID
                        }));
                    }
                }
            });
            bindClicks();

            $(`#item_size label:first, #item_size input[name=size]:first`).click();
            setTimeout(function () {
                $(`#item_size label:first, #item_size input[name=size]:first`).click();
            }, 1500);
        }

        function getCovers(Item) {
            hide_components(['#item_customer_parent', '#qty-main', '#cart_main', '#add_to_cart', '#login_by_popup']);
            $('#item_cover').html('');
            var ItemID = Item.val();
            var covers_available = false;
            item_object.PillowCovers.forEach(function (cover, index) {
                if (cover.ParentItemID.trim() == ItemID.trim()) {
                    if (!$('#item_cover input[name=cover]:contains(' + cover.Description + ')').length) {
                        $('#item_cover').append($('<input>', {
                            value: cover.ItemID,
                            text: cover.Description,
                            class: 'checkbox-tools',
                            type: 'radio',
                            name: 'cover',
                            id: 'cover_' + cover.ItemID
                        }));

                        $('#item_cover').append($('<label>', {
                            text: cover.Description,
                            class: 'for-checkbox-tools',
                            for: 'cover_' + cover.ItemID
                        }));
                        covers_available = true;
                    }
                }
            });
            if (covers_available) {
                show_components(['#item_cover_parent']);
                bindClicks();
                if (!$('input[name=cover]').is(':checked'))
                    $(`#item_cover label:first, #item_cover input[name=cover]:first`).click();
            } else {
                hide_components(['#item_cover_parent']);
                pupolateDimensions(Item.parent('#item_size_parent'), Item.val());
                getQuantity(Item.val());
            }
        }

        function getQuantity(ItemID) {
            // TODO : The radio button is working but not getting highlighted - Adil needs to fix this
            if (ItemID == '0') {
                hide_components(['#item_customer_parent']);
                hide_components(['#qty-main', '#cart_main', '#add_to_cart']);
                return true;
            }
            item_object.Items.forEach(function (item, index) {
                if ((item.ItemID == ItemID)) {
                    $('#item_customer input[name=customer]').prop('disabled', false);
                    if (item.UserCustomerInfo.IsSaleRep == 1) {
                        getCustomers(item);
                        var customer_id = item.UserCustomerInfo.CustomerSet ? item.UserCustomerInfo.CustomerSet :
                            '';

                        // $('#item_customer input[name=customer]').prop('disabled', 'disabled');
                        $('#qty-main, .base_price').addClass('muted');
                        $('#qty_msg').css('opacity', '0.4');
                        if (!$('#qty-main').is(':visible'))
                            show_components(['.qty-loader']);
                        $.post('{{ route('frontend.item.ats') }}', {
                            _token: '{{ csrf_token() }}',
                            item_id: item.ItemID,
                            customer_id: customer_id
                        }, function (response) {
                            startBuying(item.ItemID, customer_id, response.data);
                        });

                        if (item.UserCustomerInfo.CustomerSet) {
                            // startBuying(item.ItemID, item.UserCustomerInfo.Customers[0].CustomerID , item.UserCustomerInfo.Customers[0].ATSInfo);
                        }
                    } else {
                        $('#qty-main, .base_price').addClass('muted');
                        $('#qty_msg').css('opacity', '0.4');
                        if (!$('#qty-main').is(':visible'))
                            show_components(['.qty-loader']);
                        $.post('{{ route('frontend.item.ats') }}', {
                            _token: '{{ csrf_token() }}',
                            item_id: item.ItemID,
                            customer_id: typeof item.UserCustomerInfo.Customers[0].CustomerID !==
                            "undefined" ? item.UserCustomerInfo.Customers[0].CustomerID : ''
                        }, function (response) {
                            startBuying(item.ItemID, item.UserCustomerInfo.Customers[0].CustomerID, response
                                .data);
                        });
                        // startBuying(item.ItemID, item.UserCustomerInfo.Customers[0].CustomerID , item.UserCustomerInfo.Customers[0].ATSInfo);
                    }
                    if ($('#checkOut_popup').is(':visible'))
                        $('#checkOut_popup').hide();
                }
            });

            bindClicks();
        }

        function getCustomers(item) {
            show_components(['#item_customer_parent']);
            hide_components(['#qty-main', '#cart_main', '#add_to_cart', '#login_by_popup']);
            $('#item_customer').html('');
            item.UserCustomerInfo.Customers.forEach(function (Customer, index) {
                if (item.UserCustomerInfo.CustomerSet) {
                    $('#active_customer_select').addClass('d-none');
                    $('#disabled_customer_select').removeClass('d-none');
                    if (Customer.CustomerID == item.UserCustomerInfo.CustomerSet) {
                        customerID = Customer.CustomerID;
                        $('#item_customer').append($('<input>', {
                            value: item.ItemID + ' :: ' + Customer.CustomerID,
                            text: Customer.CompanyName,
                            class: 'checkbox-tools',
                            type: 'radio',
                            name: 'customer',
                            id: 'customer_' + item.ItemID + '_' + Customer.CustomerID,
                            checked: 'checked'
                        }));

                        $('#item_customer').append($('<label>', {
                            text: Customer.CompanyName,
                            class: 'for-checkbox-tools',
                            for: 'customer_' + item.ItemID + '-' + Customer.CustomerID
                        }));
                    }
                } else {
                    $('#active_customer_select').removeClass('d-none');
                    $('#disabled_customer_select').addClass('d-none');
                    $('#item_customer').append($('<input>', {
                        value: item.ItemID + ' :: ' + Customer.CustomerID,
                        text: Customer.CompanyName,
                        class: 'checkbox-tools',
                        type: 'radio',
                        name: 'customer',
                        id: item.ItemID + ' :: ' + Customer.CustomerID
                    }));

                    $('#item_customer').append($('<label>', {
                        text: Customer.CompanyName,
                        class: 'for-checkbox-tools',
                        for: item.ItemID + ' :: ' + Customer.CustomerID
                    }));
                    customerID = '';
                }
            });
            bindClicks();
        }

        function pupolateDimensions(parent, itemID) {
            item_object.Items.forEach(function (item, index) {
                if ((item.ItemID == itemID)) {
                    if (
                        typeof item.ItemSizeDimension !== 'undefined' &&
                        typeof item.ItemSizeDimension.DimentionalWeight !== 'undefined'
                    ) {
                        $('.item-size-dimension').removeClass('d-none');
                        $('#item-size-dimension').html(`
                        <tr>
                            <td>Weight (in lbs)</td>
                            <td>Dimensions (in inches)</td>
                        </tr>
                        <tr>
                            <td>${item.ItemSizeDimension.DimentionalWeight}</td>
                            <td>${item.ItemSizeDimension.ShippingDimension}</td>
                        </tr>
                    `);
                    } else {
                        $('.item-size-dimension').addClass('d-none');
                        $('#item-size-dimension').html('');
                    }
                }
            });
        }

        function getCartReady(item_customer_id) {
            $('#qty-main, .base_price').addClass('muted');
            var split_arr = item_customer_id.split(' :: ');
            var item_id = split_arr[0].trim();
            var customer_id = split_arr[1].trim();
            item_object.Items.forEach(function (item, index) {
                if ((item.ItemID == item_id)) {
                    item.UserCustomerInfo.Customers.forEach(function (Customer, index) {
                        if (Customer.CustomerID == customer_id) {
                            $('#qty_msg').css('opacity', '0.4');
                            if (!$('#qty-main').is(':visible'))
                                show_components(['.qty-loader']);
                            $.post('{{ route('frontend.item.ats') }}', {
                                _token: '{{ csrf_token() }}',
                                item_id: item_id,
                                customer_id: customer_id
                            }, function (response) {
                                startBuying(item_id, customer_id, response.data);
                            });
                            // startBuying(item_id, customer_id , Customer.ATSInfo);
                        }
                    });
                }
            });
        }

        function startBuying(ItemID, CustomerID, ATSInfo) {
            if ($('#login_by_popup').length) {
                show_components(['#login_by_popup', '#cart_main']);
                hide_components(['#qty-main', '.qty-loader']);
            } else {
                hide_components(['.qty-loader', '#login_by_popup']);
                show_components(['#qty-main', '#cart_main', '#add_to_cart']);
                if (customerID.length != 0) show_components(['.base_price']);
            }
            $('#qty-main, .base_price').removeClass('muted');
            if (customerID.length != 0) $('#add_to_cart').removeAttr('disabled');
            $('#base_price').text(ATSInfo.Price);
            $('#qty_msg').text(getQuantityMessage(ATSInfo)).css('opacity', '1').removeClass('bg-success').removeClass(
                'bg-warning').removeClass('bg-img');
            var qty_message = $('#qty_msg').text().toLowerCase();
            $('#qty_msg').addClass((qty_message.indexOf('in stock') > -1 || qty_message.indexOf('units available') > -1) ?
                'bg-success' : 'bg-warning');
            $('#item_qty').attr('max', ATSInfo.OnlyMaxQuantity ? ATSInfo.ATSQty : 9999);

            item_object.Items.forEach(function (item, index) {
                if ((item.ItemID == ItemID)) {
                    $('#cart_item_id').val(item.ItemID);
                    console.log('start buying', CustomerID);
                    $('#cart_customer_id').val(CustomerID);
                    $('#cart_item_name').val(item.ItemName);
                    $('#cart_item_quantity').val($('#item_qty').val(0));
                    $('#cart_item_price').val(ATSInfo.Price);
                    $('#cart_item_color').val($("#item_color input:radio[name=color]:checked").text());
                    $('#cart_item_size').val($("#item_size input:radio[name=size]:checked").text());
                    $('#cart_item_currency').val('$');
                    $('#cart_item_image').val(item.ImageNameArray[0]);
                    $('#cart_item_eta').val(ATSInfo.ETADate);
                }
            });
        }

        function getQuantityMessage(ATSInfo) {
            if ($('#login_by_popup').length) {
                if (ATSInfo.ATSQty == 0)
                    return `ETA: ${ATSInfo.ETADate}`;
                else if (ATSInfo.ATSQty > 30)
                    return `In stock, 30+`;
                else
                    return `In stock, ${ATSInfo.ATSQty}`;
            } else {
                return `In stock, ${ATSInfo.ATSQty}`;

                if (ATSInfo.ATSQty == 0)
                    return `Backorder. ETA: ${ATSInfo.ETADate}`;
                else if (ATSInfo.ATSQty <= 5)
                    return `Limited Quantity Available, please email to confirm`;
                else if (ATSInfo.ATSQty > 5 && ATSInfo.ATSQty <= 15)
                    return `${ATSInfo.ATSQty} Available`;
                else
                    return `15+ Available`;
            }
        }

        function getDimensionsInInches(sizeStr) {
            let [widthStr, lengthStr] = sizeStr.split(' x ');

            let [widthFeet, widthInches] = widthStr.split("'");
            let [lengthFeet, lengthInches] = lengthStr.split("'");

            widthFeet = parseInt(widthFeet);
            widthInches = parseInt(widthInches.replace('"', ''));
            lengthFeet = parseInt(lengthFeet);
            lengthInches = parseInt(lengthInches.replace('"', ''));

            let totalWidthInches = (widthFeet * 12) + widthInches;
            let totalLengthInches = (lengthFeet * 12) + lengthInches;

            return {
                width: totalWidthInches,
                length: totalLengthInches,
                originalSize: sizeStr
            };
        }

        function pushToCart() {
            $('#add_to_cart').addClass('btn-muted');
            $('#cart_item_quantity').val($('#item_qty').val());
            item = JSON.parse($('#item_json').val());
            console.log('PUHS TO CART item', item);
            let surging_type = $('#surging_options').val() ? $('#surging_options').val() : "0";
            item.SQFTPrice = $('#sq-ft').val();
            item.SQFTArea = $('#totalsqft').val();
            item.CutPieceID = $('#cutpiece_id').val();
            item.RollID = $("#roll_id").val();
            item.SergingCharges = $('#surging_charges').val();
            item.SergingType = surging_type;
            item.location_id = $('#locationid').val();
            item.cut_type = $('#cuttype').val();
            item.Serging = $('#surging_check').is(':checked') ? 'Y' : 'N'
            $('#item_json').val(JSON.stringify(item));

            let jsonString = $('#size_price').val();
            let sizesArray = JSON.parse(jsonString);

            let dimensionsInInches = sizesArray.map(item => getDimensionsInInches(item.size));
            // console.log('dimensionsInInches', dimensionsInInches);

            var bd_cutpiece_len = 0;
            var bd_cutpiece_wid = 0;
            for (var i = 0; i < dimensionsInInches.length; i++) {
                bd_cutpiece_len += dimensionsInInches[i].length;
                bd_cutpiece_wid += dimensionsInInches[i].width;
            }

            var payload = {
                itemId: item.ItemID,
                customerId: $('#customer_id').val(),
                rollid: $("#roll_id").val()
            };

            $.ajax({
                url: '/broad-loom-full-size',
                method: 'GET',
                data: payload,
                success: function (response) {
                    if (response.success) {
                        if ((parseInt(response.bd_cutpiece_len) + parseInt(bd_cutpiece_len)) > ATS_ROLL_LENGHT || (parseInt(response.bd_cutpiece_wid) + parseInt(bd_cutpiece_wid)) > ATS_ROLL_WIDTH) {
                            toastr.error('Roll selected lenght is greater than actual total lenght', {
                                hideDuration: 10000,
                                closeButton: true,
                            });
                            $('#add_to_cart').removeClass('btn-muted');
                            return false;
                        } else {
                            add_to_cart();
                        }
                    }

                    if (!response.success) {
                        add_to_cart();
                    }
                },
                error: function (error) {
                    console.error('Check Size:', error);
                }
            });


            // } else {
            //     toastr.warning("Please enter a valid value", {
            //         hideDuration: 10000,
            //         closeButton: true,
            //     });
            //     $('#add_to_cart').removeClass('btn-muted');
            // }
        }

        function add_to_cart() {
            item = JSON.parse($('#item_json').val());
            let surging_type = $('#surging_options').val() ? $('#surging_options').val() : "0";
            item.SQFTPrice = $('#sq-ft').val();
            item.SQFTArea = $('#totalsqft').val();
            item.CutPieceID = $('#cutpiece_id').val();
            item.RollID = $("#roll_id").val();
            item.SergingCharges = $('#surging_charges').val();
            item.SergingType = surging_type;
            item.location_id = $('#locationid').val();
            item.cut_type = $('#cuttype').val();
            item.Serging = $('#surging_check').is(':checked') ? 'Y' : 'N'
            $('#item_json').val(JSON.stringify(item));
            let jsonString = $('#size_price').val();
            let sizesArray = JSON.parse(jsonString);
            let customer_instruction = $('#cust-inst').val();

            let dimensionsInInches = sizesArray.map(item => getDimensionsInInches(item.size));
            let maxLengthObj = dimensionsInInches.reduce((maxObj, currentObj) => {
                return currentObj.length > maxObj.length ? currentObj : maxObj;
            }, dimensionsInInches[0]);
            let maxLengthFeet = Math.floor(maxLengthObj.length / 12);
            let maxLengthInches = maxLengthObj.length % 12;

            let maxWidthFeet = Math.floor(maxLengthObj.width / 12);
            let maxWidthInches = maxLengthObj.width % 12;

            var max_len_size = `${maxWidthFeet}'${maxWidthInches}" x ${maxLengthFeet}'${maxLengthInches}"`;

            var bd_cutpiece_len = 0;
            var bd_cutpiece_wid = 0;
            for (var i = 0; i < dimensionsInInches.length; i++) {
                bd_cutpiece_len += dimensionsInInches[i].length;
                bd_cutpiece_wid += dimensionsInInches[i].width;
            }

            $.ajax({
                url: "{{ route('check-cart-item') }}",
                type: "GET",
                success: function (response) {
                    if (response) {
                        if (confirm('Rugs item is already in the cart, adding this item will remove the previous Rugs item from your cart, are you sure you want to proceed ?')) {
                            $.ajax({
                                url: "{{ route('delete-cart-items') }}",
                                type: "GET",
                                success: function (response) {
                                    if (response) {
                                        //  ADD TO CART API HIT
                                        $.ajax({
                                            method: 'POST',
                                            url: '{{ route('frontend.cart.add') }}',
                                            data: {
                                                '_token': '{{ csrf_token() }}',
                                                'cart_item_id': item.ItemID,
                                                'cart_customer_id': $('#customer_id').val(),
                                                'cart_item_name': item.ItemName,
                                                'cart_item_quantity': 1,
                                                'cart_item_color': item.ItemColor,
                                                'cart_item_size': max_len_size,//$('#size_price').val(),
                                                'cart_item_price': $("#sq-ext").val(),
                                                'item_surging_price': $('#surging_charges').val(),
                                                'cart_item_currency': '$',
                                                'cart_item_image': item.ImageNameArray[0],
                                                'cart_item_data': $('#item_json').val(),
                                                //'cart_item_data': $('#cart_item_oak').val(),
                                                'cart_item_broadloom': 1,
                                                'logged_user_no': '{{ Auth::user()->spars_logged_user_no }}',
                                                'temp_sales_order_no': $('#TempSalesOrderNo').val(),
                                                'bd_roll_id': $("#roll_id").val(),
                                                'bd_cutpiece_len': bd_cutpiece_len,
                                                'bd_cutpiece_wid': bd_cutpiece_wid,
                                                'user_remarks': customer_instruction,
                                            },
                                            success: function (response) {
                                                if (response.success) {
                                                    console.log("new ", $('#item_json').length);
                                                    if ($('#item_json').length) {
                                                        refreshItemJson(function () {
                                                            toastr.success(response.message, {
                                                                hideDuration: 10000,
                                                                closeButton: true,
                                                            });
                                                            $('#add_to_cart').removeClass('btn-muted');

                                                            $('#cut_piece_parent .broadloom-badge').remove();
                                                            $('#roll_pieces').val('');
                                                            $('#Tlength').val('');
                                                            $('#TlengthInch').val('');
                                                            $('#Twidth').val('');
                                                            $('#TwidthInch').val('');
                                                            $('#sq-ft').val('');
                                                            $('#sq-yrd').val('');
                                                            $('#sq-ext').val('');
                                                            $('#surging_options').val('');
                                                            $('#surging_check').prop('checked', false);
                                                            $('#surging_charges').val('');
                                                            $('#cust-inst').val('');

                                                            $('#show-cut-piece-btn').addClass('d-none');
                                                            $('#add_to_cart').addClass('d-none');
                                                            $('#roll_pieces').removeAttr("disabled");
                                                            $('#cut-pieces').empty();
                                                            $.ajax({
                                                                url: "{{ route('broadloom.removeAllCutPiece') }}",
                                                                data: {
                                                                    _token: "{{ csrf_token() }}",
                                                                    TempSalesOrderNo: null,
                                                                    logged_user_no: '{{ isset(Auth::user()->spars_logged_user_no)? Auth::user()->spars_logged_user_no : '' }}',
                                                                },
                                                                type: 'POST',
                                                                success: function (response) {
                                                                    console.log('all cut response on change', response);
                                                                }
                                                            })

                                                            // $('.quickCart-opener').trigger('click');
                                                        });
                                                    } else {
                                                        refreshUser('quick-cart', function () {
                                                            refreshUser('profile', function () {
                                                                $("#quick_cart").removeClass('d-none');
                                                                toastr.success(response.message, {
                                                                    hideDuration: 10000,
                                                                    closeButton: true,
                                                                });
                                                                $('#add_to_cart').removeClass('btn-muted');
                                                                // $('.quickCart-opener').trigger('click');
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
                                            error: function (response) {
                                                toastr.warning(response.message, {
                                                    hideDuration: 10000,
                                                    closeButton: true,
                                                });
                                                $('#add_to_cart').removeClass('btn-muted');
                                            }
                                        });
                                    } else {
                                        toastr.error('Someting went wrong', {
                                            hideDuration: 10000,
                                            closeButton: true,
                                        });
                                    }
                                }
                            });
                        }
                    } else {
                        //  ADD TO CART API HIT
                        console.log($('#item_json').val())
                        $.ajax({
                            method: 'POST',
                            url: '{{ route('frontend.cart.add') }}',
                            data: {
                                '_token': '{{ csrf_token() }}',
                                'cart_item_id': item.ItemID,
                                'cart_customer_id': $('#customer_id').val(),
                                'cart_item_name': item.ItemName,
                                'cart_item_quantity': 1,
                                'cart_item_color': item.ItemColor,
                                'cart_item_size': max_len_size,//$('#size_price').val(),
                                'cart_item_price': $("#sq-ext").val(),
                                'item_surging_price': $('#surging_charges').val(),
                                'cart_item_currency': '$',
                                'cart_item_image': item.ImageNameArray[0],
                                'cart_item_data': $('#item_json').val(),
                                //'cart_item_data': $('#cart_item_oak').val(),
                                'cart_item_broadloom': 1,
                                'logged_user_no': '{{ Auth::user()->spars_logged_user_no }}',
                                'temp_sales_order_no': $('#TempSalesOrderNo').val(),
                                'bd_roll_id': $("#roll_id").val(),
                                'bd_cutpiece_len': bd_cutpiece_len,
                                'bd_cutpiece_wid': bd_cutpiece_wid,
                                'user_remarks': customer_instruction,
                            },
                            success: function (response) {
                                if (response.success) {
                                    console.log("new ", $('#item_json').length);
                                    if ($('#item_json').length) {
                                        refreshItemJson(function () {
                                            toastr.success(response.message, {
                                                hideDuration: 10000,
                                                closeButton: true,
                                            });
                                            $('#add_to_cart').removeClass('btn-muted');

                                            $('#cut_piece_parent .broadloom-badge').remove();
                                            $('#roll_pieces').val('');
                                            $('#Tlength').val('');
                                            $('#TlengthInch').val('');
                                            $('#Twidth').val('');
                                            $('#TwidthInch').val('');
                                            $('#sq-ft').val('');
                                            $('#sq-yrd').val('');
                                            $('#sq-ext').val('');
                                            $('#surging_options').val('');
                                            $('#surging_check').prop('checked', false);
                                            $('#surging_charges').val('');
                                            $('#cust-inst').val('');

                                            $('#show-cut-piece-btn').addClass('d-none');
                                            $('#add_to_cart').addClass('d-none');
                                            $('#roll_pieces').removeAttr("disabled");
                                            $('#cut-pieces').empty();

                                            // $('.quickCart-opener').trigger('click');
                                        });
                                    } else {
                                        refreshUser('quick-cart', function () {
                                            refreshUser('profile', function () {
                                                $("#quick_cart").removeClass('d-none');
                                                toastr.success(response.message, {
                                                    hideDuration: 10000,
                                                    closeButton: true,
                                                });
                                                $('#add_to_cart').removeClass('btn-muted');
                                                // $('.quickCart-opener').trigger('click');
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
                            error: function (response) {
                                toastr.warning(response.message, {
                                    hideDuration: 10000,
                                    closeButton: true,
                                });
                                $('#add_to_cart').removeClass('btn-muted');
                            }
                        });
                    }
                }
            });
        }

        function removeCutPiece(id, cut_piece_id, roll_id, line_no, lenghtStatus, lengthfeet, widthfeet) {
            console.log(cut_piece_id);
            console.log(roll_id);
            console.log(line_no);
            console.log(lenghtStatus);
            console.log(lengthfeet);
            console.log(widthfeet);
            input_lenght_ats -= lengthfeet;
            if (lenghtStatus != 'F') {
                toastr.error('Remnant cannot be removed', {
                    hideDuration: 10000,
                    closeButton: true,
                });
            } else {
                $.ajax({
                    url: "{{ route('broadloom.removeCutPiece') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        TempSalesOrderNo: $('#TempSalesOrderNo').val(),
                        RollID: roll_id,
                        CutPieceID: cut_piece_id,
                        line_no: line_no,
                        logged_user_no: '{{ Auth::user()->spars_logged_user_no }}',
                    },
                    type: 'POST',
                    success: function (response) {
                        var cutpieceLen = response['OutPut']['AddCutPieces'];
                        if (cutpieceLen.length == 0) {
                            $('#show-cut-piece-btn').addClass('d-none');
                            $('#add_to_cart').addClass('d-none');
                            $('#roll_pieces').prop("disabled", false);
                        }
                        if (response['OutPut']['Success']) {
                            $('#cut_piece_parent').empty();
                            var divContent = '<input type="hidden" id="size_price" name="size_price[]" value=""></input<div>';
                            var sizes = [];
                            var line_no = 1;
                            let totalLen = 1;
                            let totalWid = 1;
                            let totalSqftPrice = 0;
                            let totalMaxLen = 0;
                            let lenInchCal = 0;
                            let widInchCal = 0;
                            let totalAddWid = 0;
                            let lenghtWithInches = 0;
                            let widthWithInches = 0;
                            let mxlenf = 0;
                            let mxlen = 0;

                            $.each(response['OutPut']['AddCutPieces'], function (index, item) {
                                // console.log('item.CPTempLine_No', item.CPTempLine_No);
                                // console.log('add cut res', item);
                                let lengthFeet = Math.floor(item.ATSLength / 12);
                                let lengthInches = item.ATSLength % 12;
                                let widthFeet = Math.floor(item.ATSWidth / 12);
                                let widthInches = item.ATSWidth % 12;

                                mxlenf = mxlenf + lengthFeet;
                                mxlen = mxlen + lengthInches;


                                lenInchCal = (lengthInches * 0.0833333);
                                widInchCal = (widthInches * 0.0833333);
                                lenghtWithInches = parseFloat((lengthFeet + lenInchCal));
                                widthWithInches = parseFloat((widthFeet + widInchCal));

                                if (lenghtWithInches > totalMaxLen) {
                                    totalMaxLen = lenghtWithInches;
                                }

                                totalLen *= lengthFeet;
                                totalWid *= widthFeet;
                                totalAddWid += widthWithInches;

                                var color = item.LengthStatus == 'F' ? 'Blue' : '#660000';
                                // var item_id = item.ItemID.replace('-', '_');
                                var item_id = $('#item_id').val();
                                divContent +=
                                    '<div class="badge badge-default broadloom-badge" id="' + item_id + '_' + item.CutPieceID + '_' + item.RollID + '_' + item.CPTempLine_No + '" style="background-color:' +
                                    color + '">';
                                var size = {};
                                // size.size = lengthFeet + `'` + lengthInches + `" x ` + widthFeet + `'` +
                                //     widthInches + `"`;
                                size.size = widthFeet + `'` + widthInches + `" x ` + lengthFeet + `'` +
                                    lengthInches + `"`;
                                divContent += size.size;
                                divContent += '<a  href="javascript:void(0)" onclick="removeCutPiece(`' + item_id + '_' + item.CutPieceID + '_' + item.RollID + '_' + item.CPTempLine_No + '`, `' + item.CutPieceID + '`, `' + item.RollID + '`, `' + item.CPTempLine_No + '`,  `' + item.LengthStatus + '`,  `' + lengthFeet + '`,  `' + widthFeet + '`)" style="background: ' + color + '"><i class="fa fa-times"></i></a></div>';
                                let totalLengthInInches = lengthFeet * 12 + lengthInches;
                                let totalWidthInInches = widthFeet * 12 + widthInches;

                                // Calculate total area in square inches
                                let totalAreaInSquareInches = totalLengthInInches * totalWidthInInches;

                                // Convert square inches to square feet
                                let totalAreaInSquareFeet = totalAreaInSquareInches /
                                    144; // 1 square foot = 144 square inches

                                // Convert square feet to square yards
                                let totalAreaInSquareYards = totalAreaInSquareFeet /
                                    9; // 1 square yard = 9 square feet

                                // Calculate the SQ-YRD Price ($) and EXT Price ($)
                                let sqYrdPrice = $("#sq-ft").val() / 9; // Price per square yard
                                let extPrice = totalAreaInSquareYards * sqYrdPrice;
                                // size.price = extPrice.toFixed(2);

                                if (item.LengthStatus == 'F') {
                                    console.log('in size');
                                    sizes.push(size);
                                }
                                line_no = line_no + 1;
                            });

                            totalSqftPrice = (totalMaxLen * totalAddWid);

                            $("#ats-qty").val(totalSqftPrice);
                            $('#max-width').text(`${mxlenf}'-${mxlen % 12}'`);
                            updatePrices();

                            divContent += `</div>`;

                            $('#cut_piece_parent').html(divContent);
                            $('#size_price').val(JSON.stringify(sizes));
                            item_object.CutPieces = response['OutPut']['AddCutPieces'];
                            $('#cut_pieces_json').val(JSON.stringify(response['OutPut']['AddCutPieces']));
                            $('#item_json').val(JSON.stringify(item_object));

                            toastr.success('Cut Piece Removed', {
                                hideDuration: 10000,
                                closeButton: true,
                            });
                        } else {
                            toastr.error('Remnant cannot be removed', {
                                hideDuration: 10000,
                                closeButton: true,
                            });
                        }
                    }
                })
            }
        }

        let input_lenght_ats = 0;
        let added_cut_pieces = [];

        function add_cut_pieces() {
            let actual_length = parseInt($("#Tlength").val());
            let actual_width = parseInt($("#Twidth").val());
            var length_inch = parseInt($("#TlengthInch").val());
            var width_inch = parseInt($("#TwidthInch").val());
            let length = actual_length * 12 + parseInt($("#TlengthInch").val());
            let width = actual_width * 12 + parseInt($("#TwidthInch").val());
            let sqtft = parseFloat(actual_length + "." + $("#TlengthInch").val()) * parseFloat(actual_width + "." + $("#TwidthInch").val());

            console.log('actual_length', actual_length);
            console.log('actual_width', actual_width);
            console.log('inch l', parseInt($("#TlengthInch").val()));
            console.log('inch w', parseInt($("#TwidthInch").val()));

            if (isNaN(actual_length) || isNaN(actual_width) || isNaN(length_inch) || isNaN(width_inch) ||
                    $("#Tlength").val().trim() === '' || $("#Twidth").val().trim() === '' ||
                    $("#TlengthInch").val().trim() === '' || $("#TwidthInch").val().trim() === '') {
                    toastr.error('Lenght/Width  (feet/inches)  are required', {
                        hideDuration: 10000,
                        closeButton: true,
                    });
                    return true;
            }

            let roll_ats_lenght = sessionStorage.getItem('roll_ats_lenght');
            console.log('actual_length', actual_length);
            console.log('input_lenght_ats before', input_lenght_ats);
            input_lenght_ats += actual_length;
            console.log('input_lenght_ats after', input_lenght_ats);
            console.log('roll_ats_lenght', roll_ats_lenght);
            if (input_lenght_ats > roll_ats_lenght) {
                input_lenght_ats -= actual_length;
                console.log('actual_length in', actual_length);
                console.log('input_lenght_ats before in', input_lenght_ats);
                toastr.error('Roll ATS not available', {
                    hideDuration: 10000,
                    closeButton: true,
                });
                return true;
            }

            $data= {
                    '_token': '{{ csrf_token() }}',
                    'roll_id': $("#roll_id").val(),
                    'tempsalesorderno': $("#TempSalesOrderNo").val(),
                    'item_id': $("#item_id").val(),
                    'cutpiece_id': $("#cutpiece_id").val(),
                    'atslength': length,
                    'totalwidth': width,
                    'totalsqft': sqtft,
                    'cuttype': $("#cuttype").val(),
                    'locationid': $("#locationid").val(),
                    'charges': $("#charges").val(),
                    'desc': "",
                    'waste': "N",
                    'Remnant': "N",
                    'AvailableForSale': "",
                    'IsremnantShipable': "",
                    'LineNo': "1",
                    'UserRemarks': /*"Setting Data"*/ $("#cust-inst").val(),
                    'sergingtypeno': $("#sergingtypeno").val(),
                    'logged_user_no': '{{ Auth::user()->spars_logged_user_no }}'
                };
            console.log('add cut piece data', $data);
            $("#cust-inst").val('');
            $.ajax({
                url: "{{ route('broadloom.cutPiece') }}",
                method: 'POST',
                data: $data,
                success: function (data) {
                    if (data.cut_piece.OutPut.Success) {
                        console.log(data['cut_piece']['OutPut']['AddCutPieces']);
                        $("#TempSalesOrderNo").val(data['cut_piece']['OutPut']['AddCutPieces'][0]['TempSalesOrderNo'])
                        var divContent = '<input type="hidden" id="size_price" name="size_price[]" value=""></input<div>';
                        var sizes = [];
                        var line_no = 1;
                        let totalLen = 1;
                        let totalWid = 1;
                        let totalSqftPrice = 0;
                        let totalMaxLen = 0;
                        let totalMaxLenSerg = 0;
                        let lenInchCal = 0;
                        let widInchCal = 0;
                        let lenInchSergCal = 0;
                        let widInchSergCal = 0;
                        let totalAddWidSerg = 0;
                        let totalAddWid = 0;
                        let totalAddLen = 0;
                        let totalAddLenSerg = 0;
                        let lenghtWithInches = 0;
                        let widthWithInches = 0;
                        let lenghtWithInchesSerg = 0;
                        let widthWithInchesSerg = 0;
                        let mxlenf = 0;
                        let mxlen = 0;

                        $.each(data['cut_piece']['OutPut']['AddCutPieces'], function (index, item) {
                            let lengthFeet = Math.floor(item.ATSLength / 12);
                            let lengthInches = item.ATSLength % 12;
                            let widthFeet = Math.floor(item.ATSWidth / 12);
                            let widthInches = item.ATSWidth % 12;

                            console.log(item)

                            let surging = '';
                            if (item.SergingType != "0") {
                                surging = ' Serging';
                            }

                            mxlenf = mxlenf + lengthFeet;
                            mxlen = mxlen + lengthInches;

                            lenInchCal = (lengthInches * 0.0833333);
                            widInchCal = (widthInches * 0.0833333);
                            lenghtWithInches = parseFloat((lengthFeet + lenInchCal));
                            widthWithInches = parseFloat((widthFeet + widInchCal));

                            if (lenghtWithInches > totalMaxLen) {
                                totalMaxLen = lenghtWithInches;
                            }

                            totalLen *= lengthFeet;
                            totalWid *= widthFeet;
                            totalAddWid += widthWithInches;

                            var color = item.LengthStatus == 'F' ? 'Blue' : '#660000';
                            // var item_id = item.ItemID.replace('-', '_');
                            var item_id = $('#item_id').val();
                            divContent +=
                                '<div class="badge badge-default broadloom-badge" id="' + item_id + '_' + item.CutPieceID + '_' + item.RollID + '_' + item.CPTempLine_No + '" style="background-color:' +
                                color + '">';
                            var size = {};
                            // size.size = lengthFeet + `'` + lengthInches + `" x ` + widthFeet + `'` +
                            //     widthInches + `"`;
                            size.size = widthFeet + `'` + widthInches + `" x ` + lengthFeet + `'` +
                                lengthInches + `"` + surging;
                            divContent += size.size;
                            divContent += '<a  href="javascript:void(0)" onclick="removeCutPiece(`' + item_id + '_' + item.CutPieceID + '_' + item.RollID + '_' + item.CPTempLine_No + '`, `' + item.CutPieceID + '`, `' + item.RollID + '`, `' + item.CPTempLine_No + '`,  `' + item.LengthStatus + '`,  `' + lengthFeet + '`,  `' + widthFeet + '`)" style="background: ' + color + '"><i class="fa fa-times"></i></a></div>';
                            let totalLengthInInches = lengthFeet * 12 + lengthInches;
                            let totalWidthInInches = widthFeet * 12 + widthInches;

                            // Calculate total area in square inches
                            let totalAreaInSquareInches = totalLengthInInches * totalWidthInInches;

                            // Convert square inches to square feet
                            let totalAreaInSquareFeet = totalAreaInSquareInches /
                                144; // 1 square foot = 144 square inches

                            // Convert square feet to square yards
                            let totalAreaInSquareYards = totalAreaInSquareFeet /
                                9; // 1 square yard = 9 square feet

                            // Calculate the SQ-YRD Price ($) and EXT Price ($)
                            let sqYrdPrice = $("#sq-ft").val() / 9; // Price per square yard
                            let extPrice = totalAreaInSquareYards * sqYrdPrice;
                            // size.price = extPrice.toFixed(2);

                            if (item.LengthStatus == 'F') {
                                sizes.push(size);
                            }
                            line_no = line_no + 1;
                        });

                        added_cut_pieces.forEach(item => {

                        })

                        $('#surging_check').prop('checked', false);
                        $('#surging_options').val('0');
                        $('#surging_charges').val('');
                        $("#sergingtypeno").val('');

                        totalSqftPrice = (totalMaxLen * totalAddWid);
                        $("#ats-qty").val(totalSqftPrice);
                        $('#max-width').text(`${mxlenf}'-${mxlen % 12}''`);
                        updatePrices();

                        divContent += `</div>`;

                        $('#cut_piece_parent').html(divContent);
                        $('#size_price').val(JSON.stringify(sizes));

                        item_object.CutPieces = data['cut_piece']['OutPut']['AddCutPieces'];
                        $('#cut_pieces_json').val(JSON.stringify(data['cut_piece']['OutPut']['AddCutPieces']));
                        $('#item_json').val(JSON.stringify(item_object));
                        toastr.success(data.cut_piece.OutPut.Message, {
                            hideDuration: 10000,
                            closeButton: true,
                        });
                        $('#show-cut-piece-btn').removeClass('d-none');
                        $('#roll_pieces').prop('disabled', true);
                        $('#add_to_cart').removeClass('d-none');
                    } else {
                        console.log('data.cut_piece.OutPut.Message', data.cut_piece.OutPut.Message);
                        toastr.error(data.cut_piece.OutPut.Message, {
                            hideDuration: 10000,
                            closeButton: true,
                        });
                        var cutpieces = data['cut_piece']['OutPut']['AddCutPieces'];
                        input_lenght_ats  = 0;
                    }

                },
                error: function (xhr, status, error) {
                    console.error("Error occurred:", status, error);
                }
            });
        }

        const showCutPieceObj = new ShowCutPiece();
        $("#show-cut-piece-btn").click(function (event) {
            let screen_coordinates = {
                x: event.target.getBoundingClientRect().left + (event.target.getBoundingClientRect().width / 2),
                y: event.target.getBoundingClientRect().top - event.target.getBoundingClientRect().height - 7
            };
            let payload = {
                temp_sales_order_no: $('#TempSalesOrderNo').val(),
                logged_user_no: '{{ Auth::user()->spars_logged_user_no }}'
            }

            showCutPieceObj.cutPiecesInitilize(screen_coordinates, payload)
        });

        //updated
        function updatePrices() {
            // Assuming perSquareFeetPrice is the price per square foot
            let perSquareFeetPrice = $("#sq-ft").val(); // Example value

            // Retrieve length and width in feet and inches
            let lengthFeet = parseInt($("#Tlength").val());
            let lengthInches = parseInt($("#TlengthInch").val());
            let widthFeet = parseInt($("#Twidth").val());
            let widthInches = parseInt($("#TwidthInch").val());

            // Convert length and width to total inches
            let totalLengthInInches = lengthFeet * 12 + lengthInches;
            let totalWidthInInches = widthFeet * 12 + widthInches;

            // Calculate total area in square inches
            let totalAreaInSquareInches = totalLengthInInches * totalWidthInInches;

            // Convert square inches to square feet
            let totalAreaInSquareFeet = totalAreaInSquareInches / 144; // 1 square foot = 144 square inches

            // Convert square feet to square yards
            let totalAreaInSquareYards = totalAreaInSquareFeet / 9; // 1 square yard = 9 square feet

            // Calculate the SQ-YRD Price ($) and EXT Price ($)
            //let sqYrdPrice = perSquareFeetPrice / 9; // Price per square yard
            // let extPrice = totalAreaInSquareYards * sqYrdPrice;

            let sqYrdPrice = perSquareFeetPrice * 9;

            let extPrice = $("#sq-ft").val() * $("#ats-qty").val();

            // Update the SQ-YRD Price ($) and EXT Price ($) fields
            $("#sq-yrd").val(sqYrdPrice.toFixed(2)); // Set SQ-YRD Price with two decimal places
            $("#sq-ext").val(extPrice.toFixed(2)); // Set EXT Price with two decimal places
        }

        //updated
        function bindClicks() {
            console.log("test");
            $('#add_to_cart')
                .off('click')
                .on('click', function (e) {
                    console.log("in cart");
                    if (
                        $('input[name="sale_rep"]').val() == 1 &&
                        customerID.length == 0
                    )
                        toastr.warning('Please select a customer...', {
                            hideDuration: 10000,
                            closeButton: true,
                        });
                    else
                        console.log("push");
                    pushToCart();
                });

            $(".Tlength").on("input", function () {
                if ($(this).val() < originalHeightInFeet) {
                    $("#TlengthInch").attr("max", 11);
                } else {
                  //  $("#TlengthInch").attr("max", originalHeightInInches).val(originalHeightInInches);
                }
                updatePrices();
            });

            $(".Twidth").on("input", function () {
                if ($(this).val() < originalWidthInFeet) {
                    $("#TwidthInch").attr("max", 11);
                } else {
                  //  $("#TwidthInch").attr("max", originalWidthInInches).val(originalWidthInInches);
                }
                updatePrices();
            });

            $("#TlengthInch, #TwidthInch").on("change", function () {
                updatePrices();
            });

            $('#surging_check').change(function () {
                if ($(this).is(':checked')) {
                    $('#surging_options').prop('disabled', false);
                } else {
                    $('#surging_options').prop('disabled', true);
                    $('#surging_options').val($('#surging_options').val());
                    $('#surging_charges').val("");
                }
            });

            $('#cut_piece_btn').click(function () {
                add_cut_pieces();
            });

        }

        var charges = 0;
        $(document).ready(function () {
            item_object = JSON.parse($('#item_json').val());

            $('#add_to_cart').addClass('d-none');
            $('#surging_options').change(function () {
                var selectedOption = $(this).find('option:selected');
                charges = selectedOption.attr('charges');
                console.log('Charges:', charges);
                // console.log('itemid:', $('#item_id').val());
                $('#surging_charges').val(charges);
                $('#charges').val(charges);
                $('#sergingtypeno').val(selectedOption.attr('value'));
                $('#desc').val(selectedOption.attr('desc'));
            });

            $('#roll_pieces').change(function () {
                sessionStorage.removeItem('roll_ats_lenght');
                input_lenght_ats = 0;
                $('#sq-ext').val('');
                // console.log($('#customer_id').val());
                $.ajax({
                    method: 'POST',
                    url: '{{ route('frontend.item.ats') }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'item_id': $("#item_id").val(),
                        'customer_id': $('#customer_id').val()
                    },
                    success: function (response) {
                        $("#sq-ft").val(response.data['Price']);
                        updatePrices();
                        $.ajax({
                            url: "{{ route('broadloom.removeAllCutPiece') }}",
                            data: {
                                _token: "{{ csrf_token() }}",
                                TempSalesOrderNo: null,
                                logged_user_no: '{{ isset(Auth::user()->spars_logged_user_no)? Auth::user()->spars_logged_user_no : '' }}',
                            },
                            type: 'POST',
                            success: function (response) {
                                console.log('all cut response on change', response);
                            }
                        })
                    },
                    error: function (response) {
                        console.log('error res', response);
                    }
                });
                var selectedOption = $(this).find('option:selected');
                var width = selectedOption.attr('width');
                var length = selectedOption.attr('length');
                originalHeight = selectedOption.attr('length');
                ATS_ROLL_LENGHT = length;
                ATS_ROLL_WIDTH = width;
                let lengthfeet = Math.floor(length / 12);
                originalHeightInFeet = lengthfeet;
                let lengthinches = length % 12;
                originalHeightInInches = lengthinches;
                let widthfeet = Math.floor(width / 12);
                originalWidthInFeet = widthfeet;
                let widthinches = width % 12;
                originalWidthInInches = widthinches;

                $('.Twidth').val(widthfeet);
                $('.Tlength').val(lengthfeet);
                sessionStorage.setItem('roll_ats_lenght', lengthfeet);
                $('#TlengthInch').val(lengthinches);
                $('#TwidthInch').val(widthinches);
                $('.Tlength').val(lengthfeet);
                $('#lenght-width').text(`${lengthfeet}'-${lengthinches}''/`);
                $('.Twidth').attr('max', widthfeet);
                $('.Tlength').attr('max', lengthfeet);
                // $('#TlengthInch').attr('max', lengthinches);
                // $('#TwidthInch').attr('max', widthinches);
                $('#roll_id').val(selectedOption.attr('value'));
                $('#cutpiece_id').val(selectedOption.attr('cutpieceID'));
                $('#atslength').val(selectedOption.attr('length'));
                $('#totalwidth').val(selectedOption.attr('width'));
                $('#totalsqft').val(selectedOption.attr('SQFT'));
                $('#cuttype').val(selectedOption.attr('cutType'));
                $('#locationid').val(selectedOption.attr('location'));

            });

            bindClicks();
        });

        $(window).on('beforeunload', function () {
            $.ajax({
                url: "{{ route('broadloom.removeAllCutPiece') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    TempSalesOrderNo: null,
                    logged_user_no: '{{ isset(Auth::user()->spars_logged_user_no)? Auth::user()->spars_logged_user_no : '' }}',
                },
                type: 'POST',
                success: function (response) {
                    console.log('all cut response', response);
                }
            })
        });
    </script>
@endsection

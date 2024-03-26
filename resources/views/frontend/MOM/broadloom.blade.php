@php
    // $active_theme object is available containing the theme developer json loaded.
    // This is for the theme developers who want to load further view assets

    use App\Http\Controllers\ConstantsController;
    use App\Http\Controllers\CommonController;
    // echo $cust_id;
    //     die();
@endphp

@section('title', 'Item Detail Page' )
@extends('frontend.' . $active_theme -> theme_abrv . '.layouts.app')

@section('content')
    <div class="wrapper">
        @include('frontend.'.$active_theme -> theme_abrv.'.components.header')
        <main class="main-content">
            @include('frontend.'.$active_theme -> theme_abrv.'.components.breadcrumbs')
            <input type="hidden" id="customer_id" value="{{$cust_id}}"></input>
            <input type="hidden" name="" id="item_id" value="{{$roll_pieces['OutPut']["RollsAndCutPieces"][0]['ItemID']}}">
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
            <div class="site-wrapper-reveal">
                <div class="broadloom-wrapper">
                    <h3>DIA-B Black</h3>
                    <div class="card">
                        <div class="card-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="broadloom-hearder"
                                            style="background-image: url('{{ asset("/MOM/images/landing-img/rug/3.png") }}')"></div>
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
                                                                <label for="">Roll ID / Cut Piece ID</label>
                                                            </div>
                                                            <div class="col-6 text-right">
                                                                <label for="">Length: <span style="color: #660000">0' - 0' / 0' - 0'</span></label>
                                                            </div>
                                                        </div>
                                                        <select name="" id="roll_pieces" class="form-control">
                                                            <option value="" width="" length="">Select Option</option>
                                                            @foreach ($roll_pieces['OutPut']['RollsAndCutPieces'] as $row)
                                                            <option value="{{$row['RollID']}}" width="{{$row['TotalWidth']}}" length="{{$row['ATSLength']}}" SQFT="{{$row['TotalSQFT']}}" cutpieceID="{{$row['CutPieceID']}}" cutType="{{$row['CutType']}}" location="{{$row['LocationID']}}" >{{$row['RollID']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="">Cut Length</label>
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control Tlength" id="Tlength" placeholder="">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">Ft</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="input-group">
                                                                    <select name="" id="TlengthInch"
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
                                                                        <option value="12">12</option>
                                                                    </select>
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">In</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="">Cut Width</label>
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control Twidth" id="Twidth" placeholder="">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">Ft</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="input-group">
                                                                    <select name="" id="TwidthInch"
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
                                                                            <option value="12">12</option>
                                                                    </select>
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">In</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="">SQ-FT Price ($)</label>
                                                        <input type="text" class="form-control" id="sq-ft" value="" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="">SQ-YRD Price ($)</label>
                                                        <input type="text" class="form-control" id="sq-yrd" value="" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="">EXT Price ($)</label>
                                                        <input type="text" class="form-control" id="sq-ext" value="" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for=""><input type="checkbox" name="" id="surging_check"> With Serging</label>
                                                        <select name="" id="surging_options" class="form-control" disabled="disabled">
                                                            <option value="0" charges="" >Select Option</option>
                                                            @foreach ($surging_types['OutPut']['SurgingTypesList'] as $row)
                                                            <option value="{{$row["SergingTypeNo"]}}" charges="{{$row["Charges"]}}" desc="{{$row['Description']}}" >{{$row['Description']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="">Serging Charges ($)</label>
                                                        <input class="form-control" type="text" name="" id="surging_charges" value="" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">Customer Instructions</label>
                                                        <textarea name="" id="" class="form-control" rows="5"></textarea>
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
                                            <h4>Show Cut</h4>
                                            <div class="cut-pieces">
                                                <div class="length">
                                                    13' -0 (Length)
                                                </div>
                                                <div class="pieces" style="height: 10cm">
                                                    <div class="width">
                                                        10' -0 (Width)
                                                    </div>
                                                    <div class="picese-wrapper">
                                                        <div class="piece" style="height: 10cm; width: 8cm;">
                                                            8' -0' X 10' -0'
                                                        </div>
                                                        <div class="piece" style="height: 10cm; width: 5cm;">
                                                            5' -0' X 10' -0'
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mt-5 text-center">
                                        <button class="show-piece-btn broadloom-btns" >Show Cut Piece <i class="fa fa-long-arrow-right"></i></button>
                                        <button class="show-piece-btn broadloom-btns" id="cut_piece_btn" >Add Cut Piece <i class="fa fa-long-arrow-right"></i></button>
                                        <button class="add-to-cart-broadloom-btn broadloom-btns">Add to Cart <i class="fa fa-long-arrow-right"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        @include('frontend.'.$active_theme -> theme_abrv.'.components.footer')
    </div>
    @include('frontend.'.$active_theme -> theme_abrv.'.components.login-modal')
@endsection

@section('scripts')
    <script>
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
                        '_token': '{{csrf_token()}}',
                        'refresh': true,
                    },
                    success: function (response) {
                        var new_html = $($.parseHTML(response));
                        console.log('Length: ', new_html.find('#item_json').length);
                        console.log('Value: ', new_html.find('#item_json').val());
                        $('#item_json').html(new_html.find('#item_json').html());

                        item_object = JSON.parse($('#item_json').html());
                        console.log(item_object);
                        $('#cart-parent').html(new_html.find('#cart-parent').html());
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
                        getQuantity($("#item_size input:radio[name=size]:checked").val().trim());
                    }
                });
            }
        }

        function refresh_product(ItemID) {
            item_object.Items.forEach(function (item, index) {
                if (item.ItemID == ItemID) {
                    $('#product-main-image').fadeOut(400, function () {
                        $("#image_0").attr('src', item.ImageNameArray[0]).attr('onerror', "this.src='{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'");
                    }).fadeIn(400);
                    setMainImage({src: item.ImageNameArray[0]});

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
                            onerror: "this.src='{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'"
                        }));
                        $('#product_thumbnails').append(div);
                        //$('#thumbnail_'+i).attr('src',item.ImageNameArray[i]).attr('onerror', "this.src='{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'");
                    }
                    init_sliders();
                    // console.log(`${item.ItemName}${($("label", $("input[name='color']:checked").parent()).attr('data-color')).replace(/^0+$/, '').replace(/0+$/, '')}`);
                    $('#product-heading').html(`${item.ItemName}<b>${($(`label[for="color_${$("input[name='color']:checked").val()}"]`).attr('data-color')).replace(/^0+$/, '').replace(/0+$/, '')}</b>`);

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
                            $('.quickview-peragraph .detiel-heading').append('<span class="not-available">: N/A</span>');
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
            item_object = JSON.parse($('#item_json').html());
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
            hide_components(['#item_size_parent', '#item_customer_parent', '#qty-main', '#cart_main', '#add_to_cart', '#login_by_popup']);
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

            var color = `{{isset($color) && $color ? $color : ''}}`;
            var color_node = color.length ? `[data-color="${color}"]` : '#item_color label:first';
            $(color_node).click();
            setTimeout(function () {
                $(`${color_node}, #${$(color_node).attr('for')}`).click();
            }, 1500);
        }

        function getSizes(ItemName, ItemColor, ItemValue) {
            show_components(['#item_size_parent']);
            hide_components(['#item_cover_parent', '#item_customer_parent', '#qty-main', '#cart_main', '#add_to_cart', '#login_by_popup']);
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
                        var customer_id = item.UserCustomerInfo.CustomerSet ? item.UserCustomerInfo.CustomerSet : '';

                        // $('#item_customer input[name=customer]').prop('disabled', 'disabled');
                        $('#qty-main, .base_price').addClass('muted');
                        $('#qty_msg').css('opacity', '0.4');
                        if (!$('#qty-main').is(':visible'))
                            show_components(['.qty-loader']);
                        $.post('{{route("frontend.item.ats")}}', {
                            _token: '{{csrf_token()}}',
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
                        $.post('{{route("frontend.item.ats")}}', {
                            _token: '{{csrf_token()}}',
                            item_id: item.ItemID,
                            customer_id: typeof item.UserCustomerInfo.Customers[0].CustomerID !== "undefined" ? item.UserCustomerInfo.Customers[0].CustomerID : ''
                        }, function (response) {
                            startBuying(item.ItemID, item.UserCustomerInfo.Customers[0].CustomerID, response.data);
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
                            $.post('{{route("frontend.item.ats")}}', {
                                _token: '{{csrf_token()}}',
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
            $('#qty_msg').text(getQuantityMessage(ATSInfo)).css('opacity', '1').removeClass('bg-success').removeClass('bg-warning').removeClass('bg-img');
            var qty_message = $('#qty_msg').text().toLowerCase();
            $('#qty_msg').addClass((qty_message.indexOf('in stock') > -1 || qty_message.indexOf('units available') > -1) ? 'bg-success' : 'bg-warning');
            $('#item_qty').attr('max', ATSInfo.OnlyMaxQuantity ? ATSInfo.ATSQty : 9999);

            item_object.Items.forEach(function (item, index) {
                if ((item.ItemID == ItemID)) {
                    $('#cart_item_id').val(item.ItemID);
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

        function pushToCart() {
            $('#add_to_cart').addClass('btn-muted');
            $('#cart_item_quantity').val($('#item_qty').val());
            console.log("cart_customer_id: ", $('#cart_customer_id').val());
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
                        'cart_item_data': $('#item_json').html(),
                        // 'cart_item_data': $('#cart_item_oak').val(),
                        'cart_item_eta': $('#cart_item_eta').val()
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
                toastr.warning("Please enter a valid value", {
                    hideDuration: 10000,
                    closeButton: true,
                });
                $('#add_to_cart').removeClass('btn-muted');
            }
        }

        function bindClicks() {

            $("#item_variant input[name='variant']")
                .off('click')
                .on('click', function () {
                    refresh_product($(this).val());
                    getColors($("#item_variant input[name='variant']:checked").text().trim());
                });

            $("#item_color input[name='color']")
                .off('click')
                .on('click', function () {
                    refresh_product($(this).val());
                    $('#color_name').html(`: ${$("#item_color input[name='color']:checked").text().trim()}`);
                    getSizes($("#item_variant input:radio[name='variant']:checked").text().trim(), $("#item_color input[name='color']:checked").text().trim(), $(this).val());
                });

            $("#item_size input[name='size']")
                .off('click')
                .on('click', function () {
                    refresh_product($(this).val());
                    $(this).attr('checked', true);
                    $('#size_name').html(`: ${$("#item_size input[name='size']:checked").text().trim()}`);
                    if (typeof $('#item_cover_parent').length !== "undefined" && $('#item_cover_parent').length) {
                        refresh_product($(this).val());
                        getCovers($(this));
                    } else {
                        pupolateDimensions($(this).parent('#item_size_parent'), $(this).val());
                        getQuantity($(this).val());
                    }
                });

            $("#item_cover input[name='cover']")
                .off('click')
                .on('click', function () {
                    $(this).attr('checked', true);
                    $('#cover_name').html(`: ${$("#item_cover input[name='cover']:checked").text().trim()}`);
                    getQuantity($(this).val());
                });

            $("#item_customer input[name='customer']")
                .off('click')
                .on('click', function () {
                    var split_arr = $(this).val().split(' :: ');
                    var item_id = split_arr[0].trim();
                    refresh_product(item_id);
                    customerID = $(this).val();
                    getCartReady($(this).val());
                });

            if (customerID.length == 0) $('#add_to_cart').attr('disabled', true);

            $('#add_to_cart')
                .off('click')
                .on('click', function (e) {
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
        }

        function updatePrices() {
            // Assuming perSquareFeetPrice is the price per square foot
            let perSquareFeetPrice = parseInt($("#sq-ft").val()); // Example value

            // Retrieve length and width in feet and inches
            let lengthFeet = parseInt($("#Tlength").val());
            let lengthInches = parseInt($("#TlengthInch").val());
            let widthFeet = parseInt($("#Twidth").val());
            let widthInches = parseInt($("#TwidthInch").val());
            let totalLengthInInches = lengthFeet * 12 + lengthInches;
            let totalWidthInInches = widthFeet * 12 + widthInches;

            // Calculate total area in square inches
            let totalAreaInSquareInches = totalLengthInInches * totalWidthInInches;

            // Convert square inches to square feet
            let totalAreaInSquareFeet = totalAreaInSquareInches / 144; // 1 square foot = 144 square inches

            // Convert square feet to square yards
            let totalAreaInSquareYards = totalAreaInSquareFeet / 9; // 1 square yard = 9 square feet

            // Calculate the SQ-YRD Price ($) and EXT Price ($)
            let sqYrdPrice = perSquareFeetPrice / 9; // Price per square yard
            let extPrice = totalAreaInSquareYards * sqYrdPrice;

        // Update the SQ-YRD Price ($) and EXT Price ($) fields
        // $("#SQ-YRD-Price").val(sqYrdPrice.toFixed(2)); // Set SQ-YRD Price with two decimal places
        // $("#EXT-Price").val(extPrice.toFixed(2));
            $("#sq-yrd").val((sqYrdPrice ).toFixed(2)); // Divide per square foot price by 9 to get price per square yard
            $("#sq-ext").val(extPrice.toFixed(2));
        }

        $(document).ready(function () {
            // console.log({{ $cust_id }});

            hide_components(['#qty_msg', '.postfix', '#item_variant_parent', '#item_color_parent', '#item_size_parent', '#qty-main', '#cart_main h3']);

            $('.qty-add').on('click', function () {
                var value = $('input[type="number"]', $(this).parent()).val();
                $('input[type="number"]', $(this).parent()).val((parseInt(value) + 1) < 1001 ? parseInt(value) + 1 : 1000).change();
            });

            $('.qty-minus').on('click', function () {
                var value = $('input[type="number"]', $(this).parent()).val();
                $('input[type="number"]', $(this).parent()).val((parseInt(value) - 1) > 0 ? parseInt(value) - 1 : 0).change();
            });

            $('#surging_options').change(function() {
                var selectedOption = $(this).find('option:selected');
                var charges = selectedOption.attr('charges');
                console.log('Charges:', charges);
                // console.log('itemid:', $('#item_id').val());
                $('#surging_charges').val(charges);
                $('#charges').val(charges);
                $('#sergingtypeno').val(selectedOption.attr('value'));
                $('#desc').val(selectedOption.attr('desc'));
            });

            var defaultOption1 = $('#roll_pieces').val();
            $('#roll_pieces').change(function() {
                // console.log($('#customer_id').val());
                $.ajax({
                    method: 'POST',
                    url: '{{route("frontend.item.ats")}}',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'item_id': $("#item_id").val(),
                        'customer_id': $('#customer_id').val()
                    },
                    success: function (response) {
                        console.log(response.data['Price']);
                        $("#sq-ft").val(response.data['Price']);
                        updatePrices();

                        // $("#sq-yrd").val(response.data['Price']);
                        // $("#sq-ext").val(response.data['Price']);
                    },
                    error: function (response) {
                        console.log(response);
                    }
                });
                var selectedOption = $(this).find('option:selected');
                var width = selectedOption.attr('width');
                var length = selectedOption.attr('length');
                var lengthfeet = Math.floor(length / 12);

                var lengthinches = length % 12;

                var widthfeet = Math.floor(width / 12);

                var widthinches = width % 12;
               
                $('.Twidth').val(widthfeet);
                $('#TwidthInch').val(widthinches);
                $('.Tlength').val(lengthfeet);
                $('#TlengthInch').val(lengthinches);
                $('.Twidth').attr('max', widthfeet);
                $('.Tlength').attr('max', lengthfeet);
                $('#roll_id').val(selectedOption.attr('value'));
                $('#cutpiece_id').val(selectedOption.attr('cutpieceID'));
                $('#atslength').val(selectedOption.attr('length'));
                $('#totalwidth').val(selectedOption.attr('width'));
                $('#totalsqft').val(selectedOption.attr('SQFT'));
                $('#cuttype').val(selectedOption.attr('cutType'));
                $('#locationid').val(selectedOption.attr('location'));
            });

            $(".Tlength, .TlengthInch, .Twidth, .TwidthInch").on("input", function() {
                // Call the updatePrices function whenever the input fields change
                updatePrices();
            });

            var defaultOption2 = $('#surging_options').val();
            $('#surging_check').change(function() {
                if ($(this).is(':checked')) {
                    $('#surging_options').prop('disabled', false);
                } else {
                    $('#surging_options').prop('disabled', true);
                    $('#surging_options').val(defaultOption);
                    $('#surging_charges').val("");
                }
            });

            $('#cut_piece_btn').click(function(){
                var itemId= $("#item_id").val();
                let length = parseInt($("#Tlength").val())*12 +  parseInt($("#TlengthInch").val());
                // let lengthInches =;
                // totalLength = length + lengthInches;
                console.log(length);
                let width = parseInt($("#Twidth").val())*12 + parseInt($("#TwidthInch").val());
                console.log(width);

                let sqtft = length * width;
                $.ajax({
                    url: "{{route('broadloom.cutPiece')}}",
                    method: 'POST',
                    data: { '_token': '{{csrf_token()}}',
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
                        'serging': "Y",
                        'LineNo': "1",
                        'UserRemarks': "Setting Data",
                        'sergingtypeno': $("#sergingtypeno").val(),

                    },
                    success: function(data){
                        if (data.cut_piece.OutPut.Success) {
                            $("#TempSalesOrderNo").val(data['cut_piece']['OutPut']['AddCutPieces'][0]['TempSalesOrderNo'])
                            var divContent = '<div>';
                            $.each(data['cut_piece']['OutPut']['AddCutPieces'], function(index, item) {
                                
                                let lengthfeet = Math.floor(item.ATSLength / 12);

                                let lengthinches = item.ATSLength % 12;

                                let widthfeet = Math.floor(item.ATSWidth / 12);

                                let widthinches = item.ATSWidth % 12;

                                console.log(lengthfeet + " ft " + lengthinches + " inches");
                                console.log(widthfeet + " ft " + widthinches + " inches");
                                let color = item.LengthStatus == 'F' ? 'blue' : 'red';
                                divContent += `<div class="badge badge-default broadloom-badge" style= "background-color: ${color}">`;
                                // divContent += item.ATSLength + `'-0" x ` + item.ATSWidth + `'-0"` + '<a class="bg-primary" href="javascript:void(0)"><i class="fa fa-times"></i></a>';
                                divContent += lengthfeet + `'`+ lengthinches +`" x ` + widthfeet + `'`+ widthinches + `"`;
                                divContent += '</div>';

                                console.log(divContent);
                            });
                            divContent += '</div>';
                            console.log(divContent)
                            $('#cut_piece_parent').html(divContent);

                            toastr.success(data.cut_piece.OutPut.Message, {
                                hideDuration: 10000,
                                closeButton: true,
                            });
                        } else {
                            toastr.error(data.cut_piece.OutPut.Message, {
                                hideDuration: 10000,
                                closeButton: true,
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error occurred:", status, error);
                    }
                });
            });

            $('.product-slider-active').slick({
                dots: false,
                infinite: true,
                slidesToShow: 5,
                slidesToScroll: 1,
                autoplay: false,
                prevArrow: '<i class="icon-arrow-left arrow-prv"></i>',
                nextArrow: '<i class="icon-arrow-right arrow-next"></i>',
                responsive: [{
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 3,
                    }
                }, {
                    breakpoint: 762,
                    settings: {
                        slidesToShow: 2,
                    }
                }, {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1,
                    }
                }]
            });
            init();
            bindClicks();
            init_sliders();

            // $('.owl-carousel').owlCarousel(
            // {
            //     loop: true,
            //     margin: 20,
            //     responsiveClass: true,
            //     responsive:
            //     {
            //         0:
            //         {
            //             items: 2,
            //             nav: true,
            //             dots: true
            //         },
            //         600:
            //         {
            //             items: 3,
            //             dots: true,
            //             nav: false
            //         },
            //         1000:
            //         {
            //             items: 4,
            //             autoplay: true,
            //             autoplaySpeed: 1000,
            //             nav: true,
            //             dots: true,
            //             loop: true
            //         }
            //     }
            // });

        });
    </script>
@endsection

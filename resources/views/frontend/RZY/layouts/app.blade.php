@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ url('images/logo-dark.svg') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Ropa+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:ital@0;1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('/RZY/css/all.css')}}?v={{time()}}">
    <link rel="stylesheet" href="{{asset('/RZY/css/toastr.css')}}">
    <link rel="stylesheet" href="{{asset('/RZY/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('/RZY/css/jquery.jqZoom.css')}}">
    <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css" integrity="sha512-TQQ3J4WkE/rwojNFo6OJdyu6G8Xe9z8rMrlF9y7xpFbQfW5g8aSWcygCQ4vqRiJqFsDsE1T6MoAOMJkFXlrI9A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @yield('styles')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js" integrity="sha512-aUhL2xOCrpLEuGD5f6tgHbLYEXRpYZ8G5yD+WlFrXrPy2IrWBlu6bih5C9H6qGsgqnU6mgx6KtU8TreHpASprw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @yield('head_scripts')

    <title>RizzyHome - @yield('title')</title>
</head>

<body>
@yield('content')
@if(!str_contains(url()->current(),route('frontend.checkout')))
    <div id="cart-parent" class="cart-parent-class">
    @include('frontend.'.$active_theme -> theme_abrv.'.components.quick-cart')
    </div>
@endif
<script>
$(document).ready(function()
{
    // hide alert message on cross btn click
    $(document).on('click','.btn-close',function(){
        $('.alert').hide();
    });
    $('.datepicker').datepicker({
        format: "{{isset($active_theme_json->general->date_format) && $active_theme_json->general->date_format ? 'mm/dd/yyyy' : 'yyyy-mm-dd'}}",
        startDate: "today",
        endDate: "+2Y",
        maxViewMode: 3,
        todayBtn: "linked",
        clearBtn: false,
        keyboardNavigation: false,
        autoclose: true,
        todayHighlight: true,
        toggleActive: true
    });

    toastr.options = {
        "closeButton": true,
        "preventDuplicates": true,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
    }

    $("body").on("click","#cart-icon", function()
    {
        $( "#quick_cart" ).removeClass('d-none');
    });

    $("body").on("click",".right-side-menu", function()
    {
        $("#avatar-pop-up").toggle();

    });

    $("body").on("click","#close-cart", function()
    {
        $( "#quick_cart" ).addClass('d-none');
    });

    $("body").on("click",".search_text_button", function()
    {
        var searchText = $("#search_text_container input[name=searchText]").val();
        var trimedText = $.trim(searchText);
        var trimedText = btoa(trimedText);
        if (trimedText !== '')
        {
            window.location.href=window.location.origin+"/search/"+trimedText;
        }
    });

    $( "#search_text_container" ).on('keyup', function (e)
    {
        if (e.key === 'Enter' || e.keyCode === 13)
        {
            var searchText = $("#search_text_container input[name=searchText]").val();
            var trimedText = $.trim(searchText);
            var trimedText = btoa(trimedText);
            if (trimedText !== '')
            {
                window.location.href=window.location.origin+"/search/"+trimedText;
            }
        }
    });

    $(document).on('click', 'input[type="number"]', function(e) {
        if (typeof $(this).attr('max') !== 'undefined' && $(this).attr('max') <= 0) {
            $(this).val(0);
        }
    });

    $(document).on('keyup', 'input[type="number"]', function(e) {
        if ( typeof $(this).attr('data-double') !== 'undefined' && $(this).attr('data-double') ) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57) && (event.which < 96 || event.which > 106)) {
                event.preventDefault();
            }
        } else {
            if ( !checkNumberInput(e) )
            {
                $(this).val(this.value.replace(/\D/g, ''));
                return false;
            }
        }

        if ( typeof $(this).attr('maxlength') !== "undefined" && this.value.length > $(this).attr('maxlength')) {
            $(this).val(this.value.slice(0, (this.value.indexOf('.') > -1) ? parseFloat($(this).attr('maxlength')) + 1 : $(this).attr('maxlength')));
        }

        if ( typeof $(this).attr('max') !== 'undefined' && parseFloat($(this).attr('max')) < parseFloat(this.value) ) {
            $(this).val($(this).attr('max') >= 0 ? $(this).attr('max') : 0);
        }
    });

    // hide alert message on cross btn click
    $(document).on('click','.btn-close',function(){
        $('.alert').hide();
    });
});

function checkNumberInput(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57) && (charCode < 96 || charCode > 106))
        return false;
    return true;
}

function refreshUser(type,callback) //cart,profile
{
    var url = '';
    if(type == 'quick-cart')
    {
        url = '{{route('frontend.cart.refresh',['quick-cart'])}}';
    }
    else if(type == 'profile')
    {
        url = '{{route('frontend.cart.refresh',['profile'])}}';
    }
    else
    {
        return;
    }
    $.ajax(
    {
        method:'POST',
        url:url,
        data:
        {
            '_token' : '{{csrf_token()}}',
        },
        success:function(response)
        {
            if(callback)
            {
                callback();
            }
            if(type == 'quick-cart')
            {
                $('#cart-parent').html(response);
            }
            else if(type == 'profile')
            {
                $('#profile-parent').html(response);
            }
            else
            {
                return;
            }
        }
    });

}

function showUpdateCartButton(itemId)
{
    $('#'+itemId+' .update-cart-button').show();
}

function updateCart(itemId,token,customerId)
{
    var quantity = $("#"+itemId+"__"+customerId+" input").val();

    $("#"+itemId+"__"+customerId+" .update-cart-button").hide();
    $("#"+itemId+"__"+customerId+" #updating-cart").removeClass('d-none');

    if( ! (/^\+?[1-9]\d*/).test(parseInt(quantity)) )
    {
        toastr.warning("Please enter a valid number.",
        {
            hideDuration: 10000,
            closeButton: true,
        });
        return;
    }

    var is_on_checkout = 0;
    if (window.location.origin+window.location.pathname == '{{route('frontend.checkout')}}')
    {
        is_on_checkout = 1;
    }

    var formData =
    {
        itemId:itemId,
        customerId:customerId,
        newQuantity:quantity,
        _token:token
    };

    $.ajax(
    {
        method: "POST",
        url: "{{route('frontend.cart.update')}}",
        data: formData
    })
    .done(function (response)
    {
        if(response.success == 1)
        {
            if (is_on_checkout)
            {
                // location.reload();
                $("#"+itemId+"__"+customerId+" #input-quantity").val(quantity).attr('value', quantity);
                $('.total-amount .cart_total_price').text(response.cart_currency + response.cart_total);
                $('#profile-parent .badge-on-cart').text(response.cart_count);

                Object.keys(response.cart_items_total).forEach(key => {
                    if ( key == itemId ) {
                        $("#"+itemId+"__"+customerId+" .price").text(response.cart_currency + parseFloat(response.cart_items_total[key]).toFixed(2));
                    }
                });
                $("#"+itemId+"__"+customerId+" #updating-cart").addClass('d-none');
            }
            else
            {
                if($('#item_json').length)
                {
                    refreshItemJson(function ()
                    {
                        $("#"+itemId+"__"+customerId+" #updating-cart").addClass('d-none');
                    });
                }
                else
                {
                    refreshUser('quick-cart', function ()
                    {
                        refreshUser('profile', function ()
                        {
                            $("#"+itemId+"__"+customerId+" #updating-cart").addClass('d-none');
                        });
                    });
                }
            }
        }
        else
        {
            toastr.error(response.message,
            {
                hideDuration: 10000,
                closeButton: true,
            });
        }
    });
}

function removeItemFromCart(itemId,token,customerId)
{
    if (confirm("Are you sure to remove this Item?"))
    {
        $("#"+itemId+"__"+customerId).addClass('all-muted');
        $("#"+itemId+"__"+customerId+" #updating-cart").removeClass('d-none');

        var is_on_checkout = 0;
        if (window.location.origin+window.location.pathname == '{{route('frontend.checkout')}}')
        {
            is_on_checkout = 1;
        }

        var formData =
        {
            itemId:itemId,
            customerId:customerId,
            _token:token
        };

        $.ajax(
        {
            method: "POST",
            url: "{{route('frontend.cart.remove')}}",
            data: formData
        })
        .done(function (response)
        {
            if(response.success == 1)
            {
                if (is_on_checkout)
                {
                    location.reload();
                }
                else
                {
                    if($('#item_json').length)
                    {
                        refreshItemJson(function ()
                        {
                            $("#"+itemId+"__"+customerId+" #updating-cart").addClass('d-none');
                            $("#"+itemId+"__"+customerId).next("hr").remove();
                            $("#"+itemId+"__"+customerId).remove();
                        });
                    }
                    else
                    {
                        refreshUser('quick-cart', function ()
                        {
                            refreshUser('profile', function ()
                            {
                                $("#"+itemId+"__"+customerId+" #updating-cart").addClass('d-none');
                                $("#"+itemId+"__"+customerId).next("hr").remove();
                                $("#"+itemId+"__"+customerId).remove();
                            });
                        });
                    }
                }
            }
            else
            {
                toastr.error(response.message,
                {
                    hideDuration: 10000,
                    closeButton: true,
                });
                $("#"+itemId+"__"+customerId).removeClass('all-muted');
            }
        });
    }
}
</script>
@yield('scripts')
</body>
</html>

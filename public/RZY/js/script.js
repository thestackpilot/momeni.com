$(document).ready(function() {
    /*
    $('.image_0').mouseover(function (){
        var image_src = $(this).attr('src');
        var image_container = document.getElementById('product-main-image');
        if (ImageZoom){

        }
        image_zoom(image_src,image_container);
    })
    alwaysReadyToRun();
    showAvatarIcon();*/

});

/*
function alwaysReadyToRun()
{
    
    $("#search-icon-mobile-version").click(function(){
        $("#search_text_container").toggle();
    });
    $(".catalog-nav-menu").click(function(){
        $(".dropdown-submenuu").toggle();
    });
    $(".catalog-nav-menu").click(function(){
        $(".dropdown-submenuu-home").toggle();
    });
    
    $( ".close-sidemenu" ).click(function() {
        $( "#sidemenu" ).hide();
    });
    
    $('#order_form').validate({
        rules: {
            FirstName:{
                required: true,
                maxlength:40
            },
            LastName:{
                required: true,
                maxlength:40
            },
            Address:{
                required: true,
                maxlength:35
            },
            Apartment:{
                maxlength:35
            },
            State:{
                required: true,
                maxlength:35
            },
            City:{
                required: true,
                maxlength:35
            },
            Country:{
                required: true,
                maxlength:15
            },
            PostalCode:{
                required: true,
                maxlength:10
            }

        }
    });
    
    $('#order_form').on('submit', function(e) {
        var isvalid = $("#order_form").valid();
        if (isvalid) {
            $(".order_form_button_outer button").prop('disabled', true);
            $(".order_form_button_outer").addClass("cursor_wait");
            $('.order_form_validation_error').addClass('d-none');
            e.preventDefault();
            var formData = {
                email:$('input[name=email]', '#order_form').val(),
                customerId:$('input[name=customer_id]', '#order_form').val(),
                shipping_method:$('input[name=ship-pickup]:checked', '#order_form').val(),
                firstName:$('input[name=FirstName]', '#order_form').val(),
                lastName:$('input[name=LastName]', '#order_form').val(),
                company:$('input[name=Company]', '#order_form').val(),
                address:$('input[name=Address]', '#order_form').val(),
                apartment:$('input[name=Apartment]', '#order_form').val(),
                state:$('input[name=State]', '#order_form').val(),
                postalCode:$('input[name=PostalCode]', '#order_form').val(),
                city:$('input[name=City]', '#order_form').val(),
                country:$('input[name=country]', '#order_form').val(),
                detail:$('input[name=detail]', '#order_form').val(),
                _token:$('input[name=_token]', '#order_form').val(),
            };
            $.ajax({
                type: "POST",
                url: "/checkout/place-order",
                data: formData,
                dataType: "json",
                encode: true,
            }).done(function (response) {
                $(".order_form_button_outer button").prop('disabled', false);
                $(".order_form_button_outer").removeClass("cursor_wait");
                if(response.success == 1){
                    $('.thanku-msg').html(response.msg);
                    $("#checkOut_popup").show();
                    $("#checkOut_popup").addClass("show");
                    $(".backdrop").show();
                    $(".backdrop").addClass("zindex-4");
                    // swal("Thank You!", response.msg, "success")
                    //     .then((value) => {
                    //         location.href = "/";
                    //     });
                    $("#success_order").html(response.msg);
                    $("#success_order").removeClass('d-none');
                    /*$('#success_order').slideDown(300).delay(5000).slideUp(300);
                    setTimeout(function(){ location.href = "/" }, 6000);
                }
                else{
                    swal("Error!", response.msg, "error");
                    $("#error_order").html(response.msg);
                    $("#error_order").removeClass('d-none');
                    $('#error_order').slideDown(300).delay(5000).slideUp(300);
                }
            });
        }
        else {
            $('.order_form_validation_error').html('One or more input fields have errors');
            $('.order_form_validation_error').removeClass('d-none');
            /*toastr.error('Error','One or more input fields have error');
            e.preventDefault();

        }
    });
    $('#contact_us').on('submit',function (e){
        var isvalid = $("#contact_us").valid();
        if (isvalid){

        }
        else{
            /*toastr.error('Error','One or more input fields have error');
        }

    })
    $('#contact_us').validate({
        rules: {
            fullname:{
                required: true
            },
            email:{
                required: true
            },
            phone:{
                required: true
            },
            company:{
                required: true
            },
            Inquiry:{
                required: true
            },
        },
        errorPlacement: function(error, element) {
            if (element.type == 'textarea'){
                error.insertBefore(element);
            }
            else{
                error.insertBefore(element);
            }

        }
    });
    $('#careers').on('submit',function (e){
        var isvalid = $("#careers").valid();
        if (isvalid){

        }
        else{
            /*toastr.error('Error','One or more input fields have error');
        }

    })
    $('#careers').validate({
        rules: {
            fullname:{
                required: true
            },
            email:{
                required: true
            },
            phone:{
                required: true
            },
            Inquiry:{
                required: true
            },
        },
        errorPlacement: function(error, element) {
            if (element.type == 'textarea'){
                error.insertBefore(element);
            }
            else{
                error.insertBefore(element);
            }

        }
    });
    $('#feedback').on('submit',function (e){
        var isvalid = $("#feedback").valid();
        if (isvalid){

        }
        else{
            /*toastr.error('Error','One or more input fields have error');
        }

    })
    $('#feedback').validate({
        rules: {
            fullname:{
                required: true
            },
            email:{
                required: true
            },
            phone:{
                required: true
            },
            Inquiry:{
                required: true
            },
        },
        errorPlacement: function(error, element) {
            if (element.type == 'textarea'){
                error.insertBefore(element);
            }
            else{
                error.insertBefore(element);
            }

        }
    });
    $( ".form-checkout input[name=ship-pickup]" ).on('change', function (e) {
        var formData = {
            shipping_method : $( ".form-checkout input[name=ship-pickup]:checked" ).val(),
            _token:$('input[name=_token]', '#order_form').val(),
        };
        $.ajax({
            type: "POST",
            url: "/shipping/shipping-rate",
            data: formData,
            dataType: "json",
            encode: true,
        }).done(function (response) {
            if(response.success == 1){
                var rate = response.data;
                var cart_sub_total = $('#cart_sub_total_hidden').val();
                cart_sub_total = ~~cart_sub_total;
                var total_bill = cart_sub_total + rate;
                $('.shipping_price_value').html('$'+rate);
                $('.cart_total_price').html('$'+total_bill);

            }
            else{
                toastr.error('Error',response.msg);
            }
        });
    });
}
*/

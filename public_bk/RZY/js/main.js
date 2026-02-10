(function($) {

    "use strict";

    var fullHeight = function() {

        $('.js-fullheight').css('height', $(window).height());
        $(window).resize(function() {
            $('.js-fullheight').css('height', $(window).height());
        });

    };
    fullHeight();

    var carousel = function() {
        $('.home-slider').owlCarousel({
            loop: true,
            autoplay: true,
            margin: 0,
            autoplaySpeed: 1000,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            nav: false,
            dots: true,
            autoplayHoverPause: false,
            items: 1,
            navText: ["<span class='ion-ios-arrow-back'></span>", "<span class='ion-ios-arrow-forward'></span>"],
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 1
                }
            }
        });

    };
    carousel();

})(jQuery);

$(document).mouseup(function(e) {

    var container = $(".dropdown-toggle, .right-side-menu");

    if (!container.is(e.target) && container.has(e.target).length === 0) {
        $(".dropdown-submenuu-home").hide();
        $(".avatar-pop-up").hide();
    }

});

$(function() {
    $('[data-toggle="tooltip"]').tooltip({
        container: 'body'
    });

    // Hidding Modals on overlay click
    $(".overlay").on("click", function() {
        $(".quick-cart").addClass("d-none");
        $(".sidebar-main").removeClass("show-sidebar-main");
    });

    $('.hover').hover(function() {
        $(this).addClass('flip');
    }, function() {
        $(this).removeClass('flip');
    });
    /*
    $(".show-megamenu").on( "click", function(e) {
        $(".mega-menu").toggleClass("mobile-mega-menu");
    });*/

    $(".mobile-nav-icon").on( "click", function(e) {
        $(".navbar-nav").slideToggle();
        $(".bi-caret-down").toggleClass("bi-caret-up");
    });

    $(".show-filters").on( "click", function(e) {
        $(".sidebar-main").toggleClass("show-sidebar-main");
        $(".overlay").show();
        $(".bi-chevron-double-right").toggleClass("bi-chevron-double-left");
    });

    $(".sidebar-close").on( "click", function(e) {
        $(".sidebar-main").removeClass("show-sidebar-main");
        $(".overlay").hide();
    });
});

//mobile search show hide
$('#search-icon-mobile-version').on('click',function(){
    if($('#search_text_container').css("display") == 'none') {
        $('#search_text_container').show();
    }
    else {
        $('#search_text_container').hide();
    }
});

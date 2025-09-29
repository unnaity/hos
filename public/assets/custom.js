$(function () {
    'use strict'

    // ______________ Page Loading
    $("#global-loader").fadeOut("slow");

    // ______________ Card
    const DIV_CARD = 'div.card';

    // ______________ Function for remove card
    $(document).on('click', '[data-bs-toggle="card-remove"]', function (e) {
        let $card = $(this).closest(DIV_CARD);
        $card.remove();
        e.preventDefault();
        return false;
    });

    // ______________ Functions for collapsed card
    $(document).on('click', '[data-bs-toggle="card-collapse"]', function (e) {
        let $card = $(this).closest(DIV_CARD);
        $card.toggleClass('card-collapsed');
        e.preventDefault();
        return false;
    });


    // ______________ Card full screen
    $(document).on('click', '[data-bs-toggle="card-fullscreen"]', function (e) {
        let $card = $(this).closest(DIV_CARD);
        $card.toggleClass('card-fullscreen').removeClass('card-collapsed');
        e.preventDefault();
        return false;
    });

    // ______________ COVER IMAGE
    $(".cover-image").each(function () {
        var attr = $(this).attr('data-bs-image-src');
        if (typeof attr !== typeof undefined && attr !== false) {
            $(this).css('background', 'url(' + attr + ') center center');
        }
    });

    // ______________Main-navbar
    if (window.matchMedia('(min-width: 992px)').matches) {
        $('.main-navbar .active').removeClass('show');
        $('.main-header-menu .active').removeClass('show');
    }
    $('.main-header .dropdown > a').on('click', function (e) {
        e.preventDefault();
        $(this).parent().toggleClass('show');
        $(this).parent().siblings().removeClass('show');
    });
    $('.main-navbar .with-sub').on('click', function (e) {
        e.preventDefault();
        $(this).parent().toggleClass('show');
        $(this).parent().siblings().removeClass('show');
    });
    $('.main-navbar .with-sub1').on('click', function (e) {
        e.preventDefault();
        $(this).parent().toggleClass('show');
        $(this).parent().siblings().removeClass('show');
    });
    $('.main-navbar .with-sub2').on('click', function (e) {
        e.preventDefault();
        $(this).parent().toggleClass('show');
        $(this).parent().siblings().removeClass('show');
    });
    $('.main-navbar .with-sub3').on('click', function (e) {
        e.preventDefault();
        $(this).parent().toggleClass('show');
        $(this).parent().siblings().removeClass('show');
    });
    $('.dropdown-menu .main-header-arrow').on('click', function (e) {
        e.preventDefault();
        $(this).closest('.dropdown').removeClass('show');
    });
    $('#mainNavShow, #azNavbarShow').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('main-navbar-show');
    });
    $('#mainContentLeftShow').on('click touch', function (e) {
        e.preventDefault();
        $('body').addClass('main-content-left-show');
    });
    $('#mainContentLeftHide').on('click touch', function (e) {
        e.preventDefault();
        $('body').removeClass('main-content-left-show');
    });
    $('#mainContentBodyHide').on('click touch', function (e) {
        e.preventDefault();
        $('body').removeClass('main-content-body-show');
    })

    // ______________Dropdown menu
    $(document).on('click touchstart', function (e) {
        e.stopPropagation();
        var dropTarg = $(e.target).closest('.main-header .dropdown').length;
        if (!dropTarg) {
            $('.main-header .dropdown').removeClass('show');
        }
        if (window.matchMedia('(min-width: 992px)').matches) {
            var navTarg = $(e.target).closest('.main-navbar .nav-item').length;
            if (!navTarg) {
                $('.main-navbar .show').removeClass('show');
            }
            var menuTarg = $(e.target).closest('.main-header-menu .nav-item').length;
            if (!menuTarg) {
                $('.main-header-menu .show').removeClass('show');
            }
            if ($(e.target).hasClass('main-menu-sub-mega')) {
                $('.main-header-menu .show').removeClass('show');
            }
        } else {
            if (!$(e.target).closest('#mainMenuShow').length) {
                var hm = $(e.target).closest('.main-header-menu').length;
                if (!hm) {
                    $('body').removeClass('main-header-menu-show');
                }
            }
        }
    });

    // ______________MainMenuShow
    $('#mainMenuShow').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('main-header-menu-show');
    })
    $('.main-header-menu .with-sub').on('click', function (e) {
        e.preventDefault();
        $(this).parent().toggleClass('show');
        $(this).parent().siblings().removeClass('show');
    })
    $('.main-header-menu-header .close').on('click', function (e) {
        e.preventDefault();
        $('body').removeClass('main-header-menu-show');
    })

    // ______________Tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
    // ______________Popover
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })
    // ______________Toast
    $(".toast").toast();
    // ______________ Toast
    var toastElList = [].slice.call(document.querySelectorAll('.toast'))
    var toastList = toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl)
    })


    // ______________ Page Loading
    $(window).on("load", function (e) {
        $("#global-loader").fadeOut("slow");
    })

    // ______________Back-top-button
    $(window).on("scroll", function (e) {
        if ($(this).scrollTop() > 0) {
            $('#back-to-top').fadeIn('slow');
        } else {
            $('#back-to-top').fadeOut('slow');
        }
    });
    $(document).on("click", "#back-to-top", function (e) {
        $("html, body").animate({
            scrollTop: 0
        }, 0);
        return false;
    });

    // ______________Full screen
    $(document).on("click", ".fullscreen-button", function toggleFullScreen() {
        $('html').addClass('fullscreenie');
        if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
            if (document.documentElement.requestFullScreen) {
                document.documentElement.requestFullScreen();
            } else if (document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen();
            } else if (document.documentElement.webkitRequestFullScreen) {
                document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
            } else if (document.documentElement.msRequestFullscreen) {
                document.documentElement.msRequestFullscreen();
            }
        } else {
            $('html').removeClass('fullscreenie');
            if (document.cancelFullScreen) {
                document.cancelFullScreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    })

    // ______________Cover Image
    $(".cover-image").each(function () {
        var attr = $(this).attr('data-image-src');
        if (typeof attr !== typeof undefined && attr !== false) {
            $(this).css('background', 'url(' + attr + ') center center');
        }
    });


    // OFF-CANVAS STYLE
    $('.off-canvas').on('click', function () {
        $('body').addClass('overflow-y-scroll');
        $('body').addClass('pe-0');
    });


    function replay() {
        let replayButtom = document.querySelectorAll('.reply a')
        // Creating Div
        let Div = document.createElement('div')
        Div.setAttribute('class', "comment mt-4 d-grid")
        // creating textarea
        let textArea = document.createElement('textarea')
        textArea.setAttribute('class', "form-control")
        textArea.setAttribute('rows', "5")
        textArea.innerText = "Your Comment";
        // creating Cancel buttons
        let cancelButton = document.createElement('button');
        cancelButton.setAttribute('class', "btn btn-danger");
        cancelButton.innerText = "Cancel";

        let buttonDiv = document.createElement('div')
        buttonDiv.setAttribute('class', "btn-list ms-auto mt-2")

        // Creating submit button
        let submitButton = document.createElement('button');
        submitButton.setAttribute('class', "btn btn-success");
        submitButton.innerText = "Submit";

        // appending text are to div
        Div.append(textArea)
        Div.append(buttonDiv);
        buttonDiv.append(cancelButton);
        buttonDiv.append(submitButton);

        replayButtom.forEach((element, index) => {

            element.addEventListener('click', () => {
                let replay = $(element).parent()
                replay.append(Div)

                cancelButton.addEventListener('click', () => {
                    Div.remove()
                })
            })
        })


    }
    replay()


    // ______________ SWITCHER-toggle ______________//

    $('.layout-setting').on("click", function (e) {
        if (!(document.querySelector('body').classList.contains('dark-theme'))) {
            $('body').addClass('dark-theme');
            $('body').removeClass('light-theme');
            $('body').removeClass('transparent-theme');

            $('#myonoffswitch5').prop('checked', true);
            $('#myonoffswitch8').prop('checked', true);

            localStorage.setItem('darkMode', true);
            localStorage.removeItem('lightMode');
            localStorage.removeItem('transparentMode');
            $('#myonoffswitch2').prop('checked', true);
        } else {
            $('body').removeClass('dark-theme');
            $('body').addClass('light-theme');
            $('#myonoffswitch3').prop('checked', true);
            $('#myonoffswitch6').prop('checked', true);

            localStorage.setItem('lightMode', true);
            localStorage.removeItem('transparentMode');
            localStorage.removeItem('darkMode');;
            $('#myonoffswitch1').prop('checked', true);
        }
    });
    /******* Theme Style ********/

    //---- Light theme ----- //
    $('body').addClass('light-theme');
    $('body').removeClass('transparent-theme');
    $('body').removeClass('dark-theme');

    //---- Dark theme ----- //
    // $('body').addClass('dark-theme');
    // $('body').removeClass('light-theme');
    // $('body').removeClass('transparent-theme');

    //---- Transparent theme ----//
    // $('body').addClass('transparent-theme');
    // $('body').removeClass('light-theme');
    // $('body').removeClass('dark-theme');

    /******* Transparent Bg-Image Style *******/

    // Bg-Image1 Style
    // $('body').addClass('bg-img1');
    // $('body').addClass('transparent-theme');
    // $('body').removeClass('light-theme');
    // $('body').removeClass('dark-theme');

    // Bg-Image2 Style
    // $('body').addClass('bg-img2');
    // $('body').addClass('transparent-theme');
    // $('body').removeClass('light-theme');
    // $('body').removeClass('dark-theme');

    // Bg-Image3 Style
    // $('body').addClass('bg-img3');
    // $('body').addClass('transparent-theme');
    // $('body').removeClass('light-theme');
    // $('body').removeClass('dark-theme');

    // Bg-Image4 Style
    // $('body').addClass('bg-img4');
    // $('body').addClass('transparent-theme');
    // $('body').removeClass('light-theme');
    // $('body').removeClass('dark-theme');

    /******* Header Styles ********/

    // $('body').addClass('light-header');
    // $('body').addClass('color-header');
    // $('body').addClass('dark-header');
    // $('body').addClass('gradient-header');


    /******* Menu Styles ********/

    // $('body').addClass('light-menu');	
    // $('body').addClass('color-menu');
    // $('body').addClass('dark-menu');
    // $('body').addClass('gradient-menu');


    /******* Full Width Layout Start ********/

    // $('body').addClass('layout-boxed'); 


    /******** *Header-Position Styles Start* ********/

    // $('body').addClass('scrollable-layout');

    // Sidemenu layout Styles //

    // ***** Icon with Text *****//
    // $('body').addClass('icontext-menu');
    // $('body').addClass('main-sidebar-hide');
    // if(document.querySelector('.icontext-menu').firstElementChild.classList.contains('login-img') !== true){
    // icontext();
    // }

    // ***** Icon Overlay ***** //
    // $('body').addClass('icon-overlay');
    // $('body').addClass('main-sidebar-hide');

    // ***** closed-menu ***** //
    // $('body').addClass('closed-menu');
    // $('body').addClass('main-sidebar-hide')

    // ***** hover-submenu ***** //
    // $('body').addClass('hover-submenu');
    // $('body').addClass('main-sidebar-hide')
    // if(document.querySelector('.hover-submenu').firstElementChild.classList.contains('login-img') !== true){
    // hovermenu();
    // }

    // ***** hover-submenu style 1 ***** //
    // $('body').addClass('hover-submenu1');
    // $('body').addClass('main-sidebar-hide')
    // if(document.querySelector('.hover-submenu1').firstElementChild.classList.contains('login-img') !== true){
    // hovermenu();
    // }

    // ACCORDION STYLE
    $(document).on("click", '[data-bs-toggle="collapse"]', function () {
        $(this).toggleClass('active').siblings().removeClass('active');
    });

});

/******* RTL VERSION *******/

// $('body').addClass('rtl');

let bodyRtl = $('body').hasClass('rtl');
if (bodyRtl) {
    $('body').addClass('rtl');
    $("html[lang=en]").attr("dir", "rtl");
    $('body').removeClass('ltr');
    $("head link#style").attr("href", $(this));
    (document.getElementById("style").setAttribute("href", "../assets/plugins/bootstrap/css/bootstrap.rtl.min.css"));
    var carousel = $('.owl-carousel');
    $.each(carousel, function (index, element) {
        // element == this
        var carouselData = $(element).data('owl.carousel');
        carouselData.settings.rtl = true; //don't know if both are necessary
        carouselData.options.rtl = true;
        $(element).trigger('refresh.owl.carousel');
    });
}
// RTL STYLE END

// ***** Horizontal Click Menu ***** //

//  $('body').addClass('horizontal');

let bodyhorizontal = $('body').hasClass('horizontal');
if (bodyhorizontal) {
    $('body').addClass('horizontal');
    $(".main-content").addClass("hor-content");
    $(".main-content").removeClass("app-content");
    $(".main-container").addClass("container");
    $(".main-container").removeClass("container-fluid");
    $(".main-sidebar").addClass("main-navbar");
    $(".main-sidebar").addClass("sticky");
    $(".main-navbar").removeClass("main-sidebar-sticky")
    $(".main-navbar").removeClass("main-sidebar");
    $(".main-navbar").removeClass("nav");
    $(".main-navbar").removeClass("main-sidebar");
    $(".side-header").addClass("hor-header");
    $(".hor-header").removeClass("side-header");
    $(".hor-header").removeClass("fixed-header");
    $(".app-sidebar").addClass("horizontal-main")
    $(".main-sidebar-body").addClass("container")
    $(".main-sidebar-body").addClass("main-sidemenu")
    $(".main-sidemenu").removeClass("main-sidebar-body")
    $('body').removeClass('sidebar-mini');
    $('body').removeClass('main-sidebar-hide');
    $('body').removeClass('horizontal-hover');
    $('body').removeClass('default-menu');
    $('body').removeClass('icontext-menu');
    $('body').removeClass('icon-overlay');
    $('body').removeClass('closed-menu');
    $('body').removeClass('hover-submenu');
    $('body').removeClass('hover-submenu1');
    $('#slide-left').removeClass('d-none');
    $('#slide-right').removeClass('d-none');
    // $('#slide-left').addClass('d-none');
    // $('#slide-right').addClass('d-none');
    // document.querySelector('.horizontal .nav').style.flexWrap = 'wrap'
    if (!document.querySelector('body').classList.contains('login-img')) {
        if (window.innerWidth >= 992) {
            removeActive()
        }
        else {
            ActiveSubmenu();
        }
        checkHoriMenu();
        responsive();
        document.querySelector('.horizontal .nav.hor-menu').style.flexWrap = 'nowrap'
    }
}

// HORIZONTAL-HOVER has class

//  $('body').addClass('horizontal-hover');

let bodyhorizontalHover = $('body').hasClass('horizontal-hover');
if (bodyhorizontalHover) {

    $('body').addClass('horizontal-hover');
    $('body').addClass('horizontal');
    $(".main-content").addClass("hor-content");
    $(".main-container").addClass("container");
    $(".main-container").removeClass("container-fluid");
    $(".main-sidebar").addClass("main-navbar");
    $(".main-sidebar").addClass("sticky");
    $(".main-navbar").removeClass("main-sidebar-sticky")
    $(".main-navbar").removeClass("main-sidebar");
    $(".main-navbar").removeClass("nav");
    // $(".main-navbar").removeClass(" ps ");
    $(".side-header").addClass("hor-header");
    $(".hor-header").removeClass("side-header");
    $(".hor-header").removeClass("fixed-header");
    $(".main-sidebar-body").addClass("container")
    $(".main-sidebar-body").addClass("main-sidemenu")
    $(".main-sidemenu").removeClass("main-sidebar-body")
    $('body').removeClass('sidebar-mini');
    $('body').removeClass('main-sidebar-hide');
    $('body').removeClass('default-menu');
    $('body').removeClass('icontext-menu');
    $('body').removeClass('icon-overlay');
    $('body').removeClass('closed-menu');
    $('body').removeClass('hover-submenu');
    $('body').removeClass('hover-submenu1');
    $('#slide-left').removeClass('d-none');
    $('#slide-right').removeClass('d-none');
    // document.querySelector('.horizontal .nav.hor-menu').style.flexWrap = 'nowrap'
    // $('#slide-left').addClass('d-none');
    // $('#slide-right').addClass('d-none');
    // document.querySelector('.horizontal .nav').style.flexWrap = 'wrap'
    if (!document.querySelector('body').classList.contains('login-img')) {
        if (window.innerWidth >= 992) {
            removeActive()
        }
        else {
            ActiveSubmenu();
        }
        checkHoriMenu();
        responsive();
        document.querySelector('.horizontal .nav.hor-menu').style.flexWrap = 'nowrap'
    }
}

function supplier_option() {
    $.ajax({
        type: "POST",
        url: base_url + 'supplier-option',
        data: $(this).attr('id'), //--> send id of checked checkbox on other page
        success: function (data) {
            console.log(data);
            $('.supplier-option').html(data);
        },
        error: function () {
        }
    });
}

$('#buy').click(function () {
    if (this.checked) {
        supplier_option();
    } else {
        var make_check = $("input[id='buy']:checked").length;
        if (!make_check.checked) {
            $("#make").prop("checked", true);
            console.log('make is checked');
        }
        $('.supplier-option').html("");
    }
});

$('#make').click(function () {
    if (!this.checked) {
        //var buy_check = $("#buy");
        var buy_check = $("input[id='buy']:checked").length;
        if (!buy_check) {
            $("#buy").prop("checked", true);
            supplier_option();
        }
        //$('.supplier-option').html("");
    }
});

$('#multiple_variant').click(function () {
    if (this.checked) {
        $("#variant_tbl").hide();
    } else {
        $(".no-border").val("");
        $("#variant_tbl").show();
    }
});

function confirm_modal(delete_url) {
    jQuery('#confirm-modal').modal('show', { backdrop: 'static' });
    document.getElementById('delete_link').setAttribute('href', delete_url);
}
var product_id_array = [];
var btn_click = 0;
$(document).ready(function ($) {
    $("#category_id").on('change', function () {
        var category_id = $(this).val();
        if (category_id) {
            $.ajax({
                type: 'POST',
                url: base_url + 'options/index/cateory-wise-product-list',
                data: { category_id: category_id },
                success: function (html) {
                    var items = [];
                    items.push(html);
                    $("#product_id").html(items.join(' '));
                },
                // success : function(htmlresponse) {
                //     $('#product_dropdown').html(htmlresponse);
                // }
            });
        }
    });

    $(".no_of_boxes").on('change', function () {
        var no_of_boxes = $(this).val();
        console.log(no_of_boxes);
        if (no_of_boxes != '') {
            $.ajax({
                type: 'POST',
                url: base_url + 'show-input-box',
                data: { no_of_boxes: no_of_boxes },
                success: function (htmlresponse) {
                    $('.rm-box').remove();
                    $('#box_no').after(htmlresponse);
                }
            });
        } else {
            $('.rm-box').remove();
        }
    });

    $("#create-product-grn").on('click', function () {
        btn_click = btn_click + 1;
        var category_name = $('#category_name').val();
        var category_id = $('#category_id').val();
        var product_name = $('#product_name').val();
        var product_id = $('#product_id').val();
        var no_of_boxes = $('#no_of_boxes').val();
        var no_of_items = $('#no_of_items').val();

        var mfg_date = $("#mfg_date").val();
        var expiry_date = $("#expiry_date").val();
        var supplier_name = $('#supplier_name').val();
        //po_no=0;
        /*if(po_no == ''){                
            $("#js-error-msg").text("Please enter purchase order no.");
            $("#h-id").show();
        }
        else*/
        console.log(mfg_date);
        console.log(expiry_date);
        if (category_id == '') {
            $("#js-error-msg").text("Please select category.");
            $("#h-id").show();
        } else if (product_id == '') {
            $("#js-error-msg").text("Please select product.");
            $("#h-id").show();
        } else if (no_of_items == '') {
            $("#js-error-msg").text("Please enter no. of items.");
            $("#h-id").show();
        } else if (no_of_items == 0) {
            $("#js-error-msg").text("No. of items should be greater than zero.");
            $("#h-id").show();
        } else if (mfg_date != '' && expiry_date != '' && mfg_date > expiry_date) {
            $("#js-error-msg").text("MFG date must be less than Expiry date.");
            $("#h-id").show();
        } else {
            if ($.inArray(product_id, product_id_array) != -1) {
                $("#js-error-msg").text("Product is already added.");
                $("#h-id").show();
            } else {
                $("#h-id").hide();
                $(".hid-tbl").show();
                var box_html = '';
                var total_box_val = 0;
                var unit_code = $('#product_id :selected').attr("data-unit");
                console.log(unit_code);
                //if(mfg_date == ''){mfg_date = ' ';}
                //if(expiry_date == ''){expiry_date = ' ';}

                $(".hid-tbl").show();
                var newRowContent = "<tr id=\"tr_" + product_id + "\"><td>" + supplier_name + "</td><td><input type=\"hidden\" name=\"category_id[]\" value=\"" + category_id + "\">" + category_name + "</td><td><input type=\"hidden\" name=\"product_id[]\" value=\"" + product_id + "\">" + product_name + "</td><td><input type=\"hidden\" name=\"mfg_date_array[]\" value=\"" + mfg_date + "\">" + mfg_date + "</td><td><input type=\"hidden\" name=\"expiry_date_array[]\" value=\"" + expiry_date + "\">" + expiry_date + "</td><td><input type=\"hidden\" name=\"no_of_items[]\" value=\"" + Math.floor(no_of_items) + "\">" + Math.floor(no_of_items) + " " + unit_code + "</td><td><button type=\"button\" name=\"remove\" onclick=\"remove_tr(" + product_id + ")\" class=\"btn  btn-sm btn-danger\"><span class=\"fe fe-trash-2\"> </span></button></td></tr>";
                $("#grn-tbl tbody").append(newRowContent);
                product_id_array.push(product_id);

                //$('#product_id').val("");                
                $('#no_of_items').val("");
                $('.rm-box').remove();
            }

            //$('#create-grn').trigger("reset");
            //$("#create-grn").load(location.href + " #create-grn");
        }
    });

    $("#add-product-grn").on('click', function () {
        var grn_type_id = $('#grn_type_id').val();
        var grn_type_name = $('#grn_type_id option:selected').text();
        var invoice_type = $("#invoice_type").val();
        var invoice_type_name = $('#invoice_type option:selected').text();
        var bill_no = $("#bill_no").val();
        var supplier = $("#supplier").val();
        var supplier_name = $('#supplier option:selected').text();

        if (grn_type_id == '') {
            $("#js-error-msg").text("Please select GRN type.");
            $("#h-id").show();
        } else if (invoice_type == '') {
            $("#js-error-msg").text("Please select invoice type.");
            $("#h-id").show();
        } else if (supplier == '') {
            $("#js-error-msg").text("Please select vendor.");
            $("#h-id").show();
        } else {
            $(".nxt-btn").hide();
            $(".nxt-btn").html("");
            $('#grn_type').attr('disabled', true);
            $("#grn_type_id").val(grn_type_id);

            $('#invoice_type').attr('disabled', true);
            $("#invoice_type_id").val(invoice_type);

            $('#bill_no').prop('readonly', true);

            $('#supplier').attr('disabled', true);
            $("#supplier_id").val(supplier);
            $("#supplier_name").val(supplier_name);
            $(".hid-row").show();
        }
    });

    $("#create-sales-order").on('click', function () {
        btn_click = btn_click + 1;
        var new_prod_name = '';
        var customer_id = $('#customer_id').val();
        var category_name = $('#category_name').val();
        var category_id = $('#category_id').val();
        var product_name = $('#product_name').val();
        var product_id = $('#product_id').val();
        var quantity = $('#quantity').val();
        var price = $('#price').val();
        var product_alias = $('#product_alias').val();
        console.log(product_alias);

        if (customer_id == '') {
            $("#h-id").show();
            $("#js-error-msg").text("Please select customer");
        } else if (category_id == '') {
            $("#h-id").show();
            $("#js-error-msg").text("Please select category");
        } else if (product_id == '') {
            $("#h-id").show();
            $("#js-error-msg").text("Please select product");
        } else if (quantity == '') {
            $("#h-id").show();
            $("#js-error-msg").text("Please enter quantity");
        } else if (price == '') {
            $("#h-id").show();
            $("#js-error-msg").text("Please enter price");
        } else {
            if ($.inArray(product_id, product_id_array) != -1) {
                $("#h-id").show();
                $("#js-error-msg").text("Product is already added.");
            } else {
                $("#h-id").hide();
                $(".hid-tbl").show();
                var unit_code = $('#product_id :selected').attr("data-unit");
                if (product_alias != '') {
                    new_prod_name = $('#product_alias :selected').text();
                    console.log(new_prod_name);
                }
                var newRowContent = "<tr id=\"tr_" + product_id + "\"><td><input type=\"hidden\" name=\"category_id_array[]\" value=\"" + category_id + "\">" + category_name + "</td><td><input type=\"hidden\" name=\"product_id_array[]\" value=\"" + product_id + "\">" + product_name + "</td><td>" + product_alias + "</td><td><input style='width:60px;' type=\"number\" name=\"quantity_array[]\" value=\"" + quantity + "\"></td><td>" + unit_code + "</td><td><input style='width:60px;' type=\"hidden\" name=\"price_array[]\" value=\"" + price + "\">" + price + "</td><td><input type=\"button\" name=\"remove\" onclick=\"remove_tr(" + product_id + ")\" value=\"remove\"></td></tr>";

                // if(btn_click == 1){
                //     var newContent = "<input type=\"hidden\" name=\"customer_id\" value=\"" + customer_id + "\"><input type=\"hidden\" name=\"order_date\" value=\"" + order_date + "\"><input type=\"hidden\" name=\"delivery_date\" value=\"" + delivery_date + "\">";
                // }
                //$(".tx-center-f").append(newContent);
                $("#sales-order-tbl tbody").append(newRowContent);
                product_id_array.push(product_id);
            }

            //$('#product_id').val("");
            //$('#product_alias_id').val("");
            $('#quantity').val("");
            $('#price').val("");
        }
    });
    $("#sales_order_edit").on('click', function () {
        btn_click = btn_click + 1;
        var new_prod_name = '';
        var customer_id = $('#customer_id').val();
        var category_name = $('#category_name').val();
        var category_id = $('#category_id').val();
        var product_name = $('#product_name').val();
        var product_id = $('#product_id').val();
        var quantity = $('#quantity').val();
        var product_alias_id = $('#product_alias_id').val();
        var total_item = parseInt($("#total_item").val());
        console.log(product_alias_id);

        if (customer_id == '') {
            $("#h-id").show();
            $("#js-error-msg").text("Please select customer");
        } else if (category_id == '') {
            $("#h-id").show();
            $("#js-error-msg").text("Please select category");
        } else if (product_id == '') {
            $("#h-id").show();
            $("#js-error-msg").text("Please select product");
        } else if (quantity == '') {
            $("#h-id").show();
            $("#js-error-msg").text("Please enter quantity");
        } else if (quantity == 0) {
            $("#h-id").show();
            $("#js-error-msg").text("Quantity is required and must be greater than 0.");
        } else if (quantity > total_item) {
            $("#h-id").show();
            $("#js-error-msg").text("Quantity should be greater than total item.");
        } else {
            if ($.inArray(product_id, product_id_array) != -1) {
                $("#h-id").show();
                $("#js-error-msg").text("Product is already added.");
            } else {
                $("#h-id").hide();
                $(".hid-tbl").show();
                //var unit_code = $('#product_id :selected').attr("data-unit");
                if (product_alias_id != '') {
                    new_prod_name = $('#product_alias_id :selected').text();
                    console.log(new_prod_name);
                }
                var newRowContent = "<tr id=\"tr_" + product_id + "\"><td><input type=\"hidden\" name=\"category_id_array[]\" value=\"" + category_id + "\">" + category_name + "</td><td><input type=\"hidden\" name=\"product_id_array[]\" value=\"" + product_id + "\">" + product_name + "</td><td><input type=\"hidden\" name=\"product_alias_name_array[]\" value=\"" + new_prod_name + "\">" + new_prod_name + "</td><td><input style='width:60px;' type=\"number\" name=\"quantity_array[]\" value=\"" + quantity + "\"></td><td><input type=\"button\" name=\"remove\" onclick=\"remove_tr(" + product_id + ")\" value=\"remove\"></td></tr>";
                $("#sales-order-tbl tbody").append(newRowContent);
                product_id_array.push(product_id);
            }

            //$('#product_id').val("");
            //$('#product_alias_id').val("");
            $('#quantity').val("");
            $('#price').val("");
        }
    });

    $("#create-purchase-order").on('click', function () {
        btn_click = btn_click + 1;

        var customer_id = $('#customer_id').val();
        var category_name = $('#category_name').val();
        var category_id = $('#category_id').val();
        var product_name = $('#product_name').val();
        var product_id = $('#product_id').val();
        var quantity = $('#quantity').val();
        var price = $('#price').val();
        var po_date = $('#po_date').val();
        //var delivery_date = $('#delivery_date').val();

        if (po_date == '') {
            $("#h-id").show();
            $("#js-error-msg").text("Please select purchase order Date");
        } else if (customer_id == '') {
            $("#h-id").show();
            $("#js-error-msg").text("Please select customer");
        } else if (category_id == '') {
            $("#h-id").show();
            $("#js-error-msg").text("Please select category");
        } else if (product_id == '') {
            $("#h-id").show();
            $("#js-error-msg").text("Please select product");
        } else if (quantity == '') {
            $("#h-id").show();
            $("#js-error-msg").text("Please enter quantity");
        } else if (price == '') {
            $("#h-id").show();
            $("#js-error-msg").text("Please enter price");
        } else {
            if ($.inArray(product_id, product_id_array) != -1) {
                $("#h-id").show();
                $("#js-error-msg").text("Product is already added.");
            } else {
                $("#h-id").hide();
                $(".hid-tbl").show();
                var unit_code = $('#product_id :selected').attr("data-unit");
                var newRowContent = "<tr id=\"tr_" + product_id + "\"><td><input type=\"hidden\" name=\"category_id_array[]\" value=\"" + category_id + "\">" + category_name + "</td><td><input type=\"hidden\" name=\"product_id_array[]\" value=\"" + product_id + "\">" + product_name + "</td><td><input style='width:60px;' type=\"number\" name=\"quantity_array[]\" value=\"" + quantity + "\"></td><td>" + unit_code + "</td><td><input style='width:60px;' type=\"hidden\" name=\"price_array[]\" value=\"" + price + "\">" + price + "</td><td><input type=\"button\" name=\"remove\" onclick=\"remove_tr(" + product_id + ")\" value=\"remove\"></td></tr>";

                // if(btn_click == 1){
                //     var newContent = "<input type=\"hidden\" name=\"customer_id\" value=\"" + customer_id + "\"><input type=\"hidden\" name=\"order_date\" value=\"" + order_date + "\"><input type=\"hidden\" name=\"delivery_date\" value=\"" + delivery_date + "\">";
                // }
                //$(".tx-center-f").append(newContent);
                $("#purchase-order-tbl tbody").append(newRowContent);
                product_id_array.push(product_id);
            }

            //$('#product_id').val("");                
            //$('#quantity').val("");
        }
    });

    $(".on_change").on('change', function () {
        product_name();
    });

    $("#customer_id").on('change', function () {
        var act = $("#act").val();
        var customer_id = $(this).val();
        if (act == 'get_order') {
            get_order_list(customer_id);
        }
    });

    $("#order_id").on('change', function () {
        var sales_order_id = $(this).val();
        get_sales_order_product_list(sales_order_id);
    });

    $(".show-hsn").on('change', function () {
        var category_id = $(this).val();
        if (category_id > 0) {
            window.location.href = base_url + 'add-product/' + category_id;
        }
        //var hsn_code = $('option:selected', this).attr('data-hsn');
        //$("#hsn_code").val(hsn_code);

    });

    $(".show_location").on('change', function () {
        show_location();
    });

    $("#invoice_type").on('change', function () {
        var invoice_type = $(this).val();

        if (invoice_type == '1') {
            $("#bill_id").html('Bill No.: <span class="tx-danger">*</span>');
        } else if (invoice_type == '2') {
            $("#bill_id").html('Delivery Challan No.: <span class="tx-danger">*</span>');
        } else if (invoice_type == '3') {
            $("#bill_id").html('PO No.: <span class="tx-danger">*</span>');
        }
    });

    /*** Quality Check */

    $("#product_grn_id").on('change', function () {
        var product_grn_id = $(this).val();
        $.ajax({
            cache: false,
            url: base_url + 'product-grn-detail',
            type: "POST",
            data: { product_grn_id: parseInt(product_grn_id) },
            success: function (html) {
                //var items = [];
                //items.push(html);
                $("#grn_list").html(html);
                $(".hid-tbl").show();
            },
        });
    });
});

function check_quality_form() {

    var product_grn_id = $('#product_grn_id').val();
    var no_of_items = $('.no_of_items').val();
    var no_of_boxes = $('.no_of_boxes').val();
    var quality_checked_item = $('.quality_checked_item').val();
    var supplier_id = $("#supplier_id").val();
    var mfg_date = $("#mfg_date").val();
    var expiry_date = $("#expiry_date").val();
    var error = 0;
    var supplier_name = $('#supplier_id option:selected').text();

    var product_grn_detail_id = [];
    $.each($("input[name='product_grn_detail_id[]']"), function () {
        product_grn_detail_id.push($(this).val());
    });

    var no_of_items = [];
    $.each($("input[name='no_of_items[]']"), function () {
        no_of_items.push($(this).val());
    });

    var no_of_boxes = [];
    $.each($("input[name='no_of_boxes[]']"), function () {
        no_of_boxes.push($(this).val());
    });

    var quality_checked_item = [];
    $.each($("input[name='quality_checked_item[]']"), function () {
        quality_checked_item.push($(this).val());
    });

    var total_items = 0;
    $.each(quality_checked_item, function () { total_items += parseFloat(this) || 0; });

    for (var i = 0; i < quality_checked_item.length; i++) {
        if (quality_checked_item[i] > no_of_items[i]) {
            error = 1;
        }
    }
    if (error) {
        $("#js-error-msg").text("No. of quality checked item must be less than or equal to no. of GRN items.");
        $("#h-id").show();
        return false;
    } else {
        $("#js-error-msg").text("");
        $("#h-id").hide();
    }
    total_box_val = 0;
    for (var i = 0; i <= no_of_boxes.length; i++) {
        for (var j = 1; j <= no_of_boxes[i]; j++) {
            var box_val = $("#box_item_" + product_grn_detail_id[i] + "_" + j).val();
            if (box_val > 0) {
                total_box_val = parseInt(total_box_val) + parseInt(box_val);
                console.log(total_box_val);
            } else {
                $("#js-error-msg").text("Please enter no. of item in Box.");
                $("#h-id").show();
            }
        }
    }

    if (total_box_val != total_items) {
        $("#js-error-msg").text("Total no. of items and sum of boxes value does not match.");
        $("#h-id").show();
        error = 1;
    }
    console.log(error);
    if (error) {
        return false;
    } else {
        return true;
    }
}

function update_purchase_price(product_grn_detail_id) {
    var purchase_price_per_item = $("#purchase_price_per_item_" + product_grn_detail_id).val();
    if (purchase_price_per_item > 0) {
        $.ajax({
            type: 'POST',
            url: base_url + '/update-purchase-price',
            data: { purchase_price_per_item: purchase_price_per_item, product_grn_detail_id: product_grn_detail_id },
            success: function (html) {
                console.log('Purchase price updated successfully');
            }
        });
    }
    //alert(purchase_price);
}

function update_no_of_stickers(pick_list_id) {
    var no_of_stickers = $("#no_of_stickers_" + pick_list_id).val();
    if (no_of_stickers > 0) {
        $.ajax({
            type: 'POST',
            url: base_url + '/update-no-of-stickers',
            data: { pick_list_id: pick_list_id, no_of_stickers: no_of_stickers },
            success: function (html) {
                console.log('No. of stickers updated successfully');
            }
        });
    } else {
        alert('No. of Sticker must be greater than zero');
        $("#no_of_stickers_" + pick_list_id).focus();
    }
}

function product_name() {
    var oem = '';
    var model = '';
    var quality_id = '';
    var size = '';
    var materialquality = '';
    var oem_id = $('#oem_id').val();
    var model_id = $('#model_id').val();
    var quality_id = $('#quality_id').val();
    var size_id = $('#size_id').val();

    if (oem_id != '') {
        var oem = $('#oem_id option:selected').text();
    }

    if (model_id != '') {
        var model = $('#model_id option:selected').text();
    }
    if (quality_id != '') {
        var materialquality = $('#quality_id option:selected').text();
    }
    if (size_id != '') {
        var size = $('#size_id option:selected').text();
    }
    var sku = $('#product_sku').val();

    var new_product_name = oem + ' ' + model + ' ' + materialquality + ' ' + size + ' ' + sku;
    $("#product_name").val($.trim(new_product_name));
}

function show_location() {
    var floor_no = $('#floor_no option:selected').text();
    var room_no = $('#room_no option:selected').text();
    var rack_no = $('#rack_no option:selected').text();
    var shelf_no = $('#shelf_no option:selected').text();
    var bin_no = $('#bin_no option:selected').text();

    var location_name = $.trim(floor_no) + '/' + $.trim(room_no) + '/' + $.trim(rack_no) + '/' + $.trim(shelf_no) + '/' + $.trim(bin_no);
    $("#location_name").val($.trim(location_name));
}

function remove_tr(product_id) {
    product_id_array = $.grep(product_id_array, function (n) {
        return n != product_id;
    });
    $("#tr_" + product_id).remove();
}
function delete_sales_tr(sales_order_id) {
    console.log(sales_order_id);
    $("#tr_" + sales_order_id).remove();
}


function show_input(product_id) {
    var category_name = $('#category_id :selected').text();
    var product_name = $('#product_id :selected').text();
    show_alias_name(product_id)
    if (product_id) {
        $('.h-id').show();
        $('#category_name').val($.trim(category_name));
        $('#product_name').val(product_name);
    } else {
        $('.h-id').hide();
        $('.h-cls').val("");
    }
}

function show_alias_name(product_id) {
    if (product_id) {
        $.ajax({
            type: 'POST',
            url: base_url + 'options/index/product-alias-list',
            data: { product_id: product_id },
            success: function (html) {
                var items = [];
                items.push(html);
                $("#product_alias_id").html(items.join(' '));
            },
        });
    }
}

function change_branch(branch_id) {
    $.ajax({
        type: "POST",
        url: base_url + 'change-branch',
        data: { branch_id: branch_id },
        success: function (data) {
            location.reload(true);
        },
        error: function () {
        }
    });
}

function change_store(store_id) {
    $.ajax({
        type: "POST",
        url: base_url + 'change-store',
        data: { store_id: store_id },
        success: function (data) {
            location.reload(true);
        },
        error: function () {
        }
    });
}

function get_order_list(customer_id) {
    if (customer_id) {
        $.ajax({
            cache: false,
            url: base_url + 'get-order-list',
            type: "POST",
            data: { customer_id: parseInt(customer_id) },
            success: function (html) {
                var items = [];
                items.push(html);
                $("#order_id").html(items.join(' '));
            },
        });
    }
}

function get_sales_order_product_list(sales_order_id) {
    if (sales_order_id) {
        $.ajax({
            cache: false,
            url: base_url + 'sales-order-product-list',
            type: "POST",
            data: { sales_order_id: parseInt(sales_order_id) },
            success: function (html) {
                var items = [];
                items.push(html);
                $("#prod_list").html(items.join(' '));
                $(".hid-tbl").show();
                //get_pick_list_item(sales_order_id);
            },
        });
    }
}

function get_pick_list_item(sales_order_id) {
    if (sales_order_id) {
        $.ajax({
            cache: false,
            url: base_url + 'get-pick-list-item',
            type: "POST",
            data: { sales_order_id: parseInt(sales_order_id) },
            success: function (html) {
                console.log(html);
                var items = [];
                items.push(html);
                $("#pick_list").html(items.join(' '));
                $(".pick-list-tbl").show();
                //$("#pick_list").append(parse_data.data);
            },
        });
    }
}
/************************Create Put Away Page **********/
$(document).ready(function ($) {
    $("#location_no").focus();
    $("#location_no").on('change', function () {
        var location_no = $(this).val();
        if (location_no) {
            $.ajax({
                type: 'POST',
                url: base_url + 'show-location-detail',
                data: { location_no: location_no },
                success: function (response) {
                    if (response.length > 0) {
                        $("#error_msg").text("");
                        $("#error_msg").hide();
                        var parse_data = JSON.parse(response);
                        $("#location_name").val(parse_data.location_name);
                        $(".l_name").show();
                    } else {
                        $("#location_name").val("");
                        $(".l_name").hide();
                        $("#error_msg").text("No record found!");
                        $("#error_msg").show();
                    }
                }
            });
        }
    });

    $("#box_no").on('change', function () {
        var box_no = $(this).val();
        if (box_no) {
            $.ajax({
                type: 'POST',
                url: base_url + 'show-product-detail',
                data: { box_no: box_no },
                success: function (response) {
                    $("#p_detail").html(response);
                }
            });
        }
    });
});


/************************Create Pick List Page **********/
$(document).ready(function ($) {
    $("#pick_list_box_no").on('change', function () {
        var pick_list_box_no = $(this).val();
        var order_id = $("#order_id").val();
        var delivery_date = $("#delivery_date").val();
        if (pick_list_box_no) {
            $.ajax({
                type: 'POST',
                url: base_url + 'show-box-detail',
                data: { pick_list_box_no: pick_list_box_no, order_id: order_id, delivery_date: delivery_date },
                success: function (response) {
                    var parse_data = JSON.parse(response);
                    console.log(parse_data);
                    if (parse_data.status == 0) {
                        $(".rm_cls").remove();
                        $("#error_msg").text(parse_data.message);
                    } else {
                        $("#error_msg").text("");
                        $("#box_detail").html("");
                        $(".pick-list-tbl, .hid-btn").show();
                        $("#box_detail").append(parse_data.data);

                    }
                }
            });
        } else { $(".rm_cls").remove(); }
    });
});

function add_to_pick_list() {
    var remaining_item = parseInt($("#remaining_item").val());
    var qty = parseInt($("#qty").val());
    var pick_list_box_no = $("#pick_list_box_no").val();
    var order_id = $("#order_id").val();
    var delivery_date = $("#delivery_date").val();
    var order_qty = 0;
    console.log(remaining_item);
    console.log("teerra");
    console.log(qty);
    if (remaining_item == qty) {
        var order_qty = remaining_item;
    } else if (remaining_item > qty) {
        var order_qty = qty;
    } else if (remaining_item < qty) {
        $("#error_msg").text("Please enter less than or equal to in stock item.");
    }

    if (order_qty > 0) {
        $.ajax({
            type: 'POST',
            url: base_url + 'save-box-detail',
            data: { pick_list_box_no: pick_list_box_no, order_id: order_id, delivery_date: delivery_date, order_qty: order_qty, remaining_item: remaining_item },
            success: function (response) {
                var parse_data = JSON.parse(response);
                console.log(parse_data);
                if (parse_data.status == 0) {
                    $("#error_msg").text(parse_data.message);
                } else {
                    $("#error_msg").text("");
                    //$(".pick-list-tbl, .hid-btn").show();
                    $(".rm_cls").remove();
                    $("#pick_list_box_no").val("");
                    $("#pick_list").append(parse_data.data);
                }

            }
        });
    }
    //alert(order_qty);
}

function check_pick_list() {
    var sales_order_id = $("#order_id").val();
    var customer_id = $("#customer_id").val();
    var error = 0;
    $.ajax({
        type: 'POST',
        async: false,
        url: base_url + 'check-pick-list',
        data: { sales_order_id: sales_order_id, customer_id: customer_id },
        success: function (response) {
            var parse_data = JSON.parse(response);
            console.log(parse_data);
            if (parse_data.status == 0) {
                $("#js-error-msg").text(parse_data.message);
                $("#h-id").show();
                error = 1;
                //return false;
            } else {
                $("#js-error-msg").text("");
                $("#h-id").hide();
                error = 0;
                //return true;
            }
        }
    });

    if (error == 1) {
        return false;
    } else {
        return true;
    }
}

/*********** General Issues ****************************/

$(document).ready(function ($) {
    $("#gi_box_no").on('change', function () {
        var box_no = $(this).val();
        var purchase_order_id = $("#purchase_order_id").val();
        if (box_no) {
            $.ajax({
                type: 'POST',
                url: base_url + 'show-gi-box-detail',
                data: { box_no: box_no, purchase_order_id: purchase_order_id },
                success: function (response) {
                    var parse_data = JSON.parse(response);
                    console.log(parse_data);
                    if (parse_data.status == 0) {
                        $("#box_detail").html("");
                        $(".rm_cls").remove();
                        $("#error_msg").text(parse_data.message);
                    } else {
                        $("#box_detail").html("");
                        $("#error_msg").text("");
                        $(".pick-list-tbl, .hid-btn").show();
                        $("#box_detail").append(parse_data.data);
                    }
                }
            });
        } else {
            $("#error_msg").text("");
            $("#box_detail").html("");
        }
    });

    $("#purchase_order_id").on('change', function () {
        var po_id = $(this).val();
        get_po_product_list(po_id);
    });

    $("#department_id").on('change', function () {
        var department_id = $(this).val();
        if (department_id) {
            $.ajax({
                type: 'POST',
                url: base_url + 'options/index/department-wise-employee-list',
                data: { department_id: department_id },
                success: function (html) {
                    var items = [];
                    items.push(html);
                    $("#employee_id").html(items.join(' '));
                },
            });
        }
    });

    // $("#product_id").on('change', function() {
    //     $("#box_detail").html("");
    //     $("#gi_box_no").val("");
    // });
});

function get_po_product_list(po_id) {
    if (po_id) {
        $.ajax({
            cache: false,
            url: base_url + 'po-product-list',
            type: "POST",
            data: { po_id: parseInt(po_id) },
            success: function (html) {
                var items = [];
                items.push(html);
                $("#prod_list").html(items.join(' '));
                $(".hid-tbl").show();
                //get_pick_list_item(sales_order_id);
            },
        });
    }
}

function add_to_gi_list() {

    var department_id = $("#department_id").val();
    var department_name = $('#department_id option:selected').text();

    var employee_id = $('#employee_id').val();
    var employee_name = $('#employee_id option:selected').text();

    var box_detail_id = $('#box_detail_id').val();

    var unit_name = $('#unit_name').val();
    //var category_name = $('#category_id option:selected').text();

    var product_id = $('#p_name_id').val();
    var product_name = $('#p_name').val();

    var gi_box_no = $('#gi_box_no').val();

    var quantity = parseInt($('#qty').val());

    var remaining_item = parseInt($('#remaining_item').val());

    if (department_id == '') {
        $("#js-error-msg").text("Please select department.");
        $("#h-id").show();
    } else if (employee_id == '') {
        $("#js-error-msg").text("Please select employee.");
        $("#h-id").show();
    }
    // else if(category_id == ''){                
    //     $("#js-error-msg").text("Please select category.");
    //     $("#h-id").show();
    // }
    else if (product_id == '') {
        $("#js-error-msg").text("Please select product.");
        $("#h-id").show();
    }
    else if (quantity < 1) {
        $("#js-error-msg").text("Quantity should be greater than zero.");
        $("#h-id").show();
    }
    else if (quantity > 0 && remaining_item > 0 && remaining_item < quantity) {
        $("#js-error-msg").text("Quantity must be less than or equal to Qty in Stock.");
        $("#h-id").show();
    }
    else if (gi_box_no == '') {
        $("#js-error-msg").text("Please scan a box.");
        $("#h-id").show();
    } else {
        if ($.inArray(product_id, product_id_array) != -1) {
            $("#js-error-msg").text("Product is already added.");
            $("#h-id").show();
        } else {
            $("#h-id").hide();
            $(".hid-tbl").show();
            var newRowContent = "<tr id=\"tr_" + product_id + "\"><td><input type=\"hidden\" name=\"department_id_array[]\" value=\"" + department_id + "\">" + department_name + "</td><td><input type=\"hidden\" name=\"employee_id_array[]\" value=\"" + employee_id + "\">" + employee_name + "</td><td><input type=\"hidden\" name=\"product_id[]\" value=\"" + product_id + "\">" + product_name + "</td><td><input type=\"hidden\" name=\"quantity_array[]\" value=\"" + quantity + "\"><input type=\"hidden\" name=\"remaining_item_array[]\" value=\"" + remaining_item + "\">" + quantity + " " + unit_name + "</td><td><input type=\"hidden\" name=\"box_detail_id[]\" value=\"" + box_detail_id + "\">" + gi_box_no + "</td><td><button type=\"button\" name=\"remove\" onclick=\"remove_tr(" + product_id + ")\" class=\"btn  btn-sm btn-danger\"><span class=\"fe fe-trash-2\"> </span></button></td></tr>";
            console.log(newRowContent);
            $("#grn-tbl tbody").append(newRowContent);
            product_id_array.push(product_id);
            console.log(product_id_array);
            $("#box_detail").html("");
            $("#gi_box_no").val("");
        }

        //$('#create-grn').trigger("reset");
        //$("#create-grn").load(location.href + " #create-grn");
    }
}


$(window).on("load", function (e) {
    var total_value = $("#total_value").val();
    $("#t_value").text(total_value);
})
// $(document).ready(function($) { 
//     var total_value = $("#total_value").val();
//     $("#t_value").text(total_value);
// });

// scrap list

$(document).ready(function ($) {
    $("#scrap_box_no").on('change', function () {
        var scrap_box_no = $(this).val();
        if (scrap_box_no) {
            $.ajax({
                type: 'POST',
                url: base_url + 'get-box-detail',
                data: { scrap_box_no: scrap_box_no },
                success: function (response) {
                    var parse_data = JSON.parse(response);
                    console.log(parse_data);
                    if (parse_data.status == 0) {
                        $(".rm_cls").remove();
                        $("#error_msg").text(parse_data.message);
                    } else {
                        $("#error_msg").text("");
                        $("#box_detail").html("");
                        $(".scrap-list-tbl, .hid-btn").show();
                        $("#box_detail").append(parse_data.data);

                    }
                }
            });
        } else { $(".rm_cls").remove(); }
    });
});
function add_to_scrap_list() {
    var remaining_item = parseInt($("#remaining_item").val());
    var qty = parseInt($("#qty").val());
    var scrap_box_no = $("#scrap_box_no").val();
    var remark = ($("#remark").val());
    var scrap_qty = 0;
    if (remark === "") {
        $("#error_msg").text("Remark is required");
        return;
    }
    console.log(remaining_item);
    console.log(qty);
    if (isNaN(qty) || qty <= 0) {
        $("#error_msg").text("Scrap Quantity is required and must be greater than 0.");
        return;
    }
    if (remaining_item == qty) {
        scrap_qty = remaining_item;
    } else if (remaining_item > qty) {
        scrap_qty = qty;
    } else if (remaining_item < qty) {
        $("#error_msg").text("Please enter less than or equal to in stock item.");
    }

    if (scrap_qty > 0) {
        $.ajax({
            type: 'POST',
            url: base_url + 'save-scrap-detail',
            data: { scrap_box_no: scrap_box_no, scrap_qty: scrap_qty, remaining_item: remaining_item, remark: remark },
            success: function (response) {
                var parse_data = JSON.parse(response);
                console.log(parse_data);
                if (parse_data.status == 0) {
                    $("#error_msg").text(parse_data.message);
                } else {
                    $("#error_msg").text("");
                    //$(".pick-list-tbl, .hid-btn").show();
                    $(".rm_cls").remove();
                    $("#scrap_box_no").val("");
                    $("#scrap_list").append(parse_data.data);
                }

            }
        });
    }
    //alert(order_qty);
}
function delete_tr(scrap_id) {
    console.log(scrap_id);
    $("#tr_" + scrap_id).remove();
    if (scrap_id > 0) {
        $.ajax({
            type: 'POST',
            url: base_url + 'delete-scrap-detail',
            data: { scrap_id: scrap_id },
            success: function (response) {
                var parse_data = JSON.parse(response);
                console.log(parse_data);
                if (parse_data.status == 0) {
                    $("#error_msg").text(parse_data.message);
                } else {
                    $("#error_msg").text("");
                    $(".rm_cls").remove();
                    $("#scrap_id").val("");
                    $("#scrap_list").append(parse_data.data);
                }

            }
        });
    }
}
function show_grn_delete_popup(id, name) {
    jQuery('#edit-grn-type').modal('show', { backdrop: 'static' });
    $("#grn_type_id").val(id);
    $("#grn_type_name").val(name);
}
function show_sales_delete_popup(id, name) {
    jQuery('#edit-sales-type').modal('show', { backdrop: 'static' });
    $("#sales_type_id").val(id);
    $("#sales_type_name").val(name);
}
function show_invoice_delete_popup(id, name) {
    jQuery('#edit-invoice').modal('show', { backdrop: 'static' });
    $("#invoice_id").val(id);
    $("#invoice_name").val(name);
}
$(document).ready(function ($) {
    $("#product_id").on('change', function () {
        var product_id = $(this).val();
        var total_item = parseInt($("#total_item").val());
        console.log(total_item);
        if (product_id) {
            $.ajax({
                type: 'POST',
                url: base_url + 'get-product-qty',
                data: { product_id: product_id, total_item: total_item },
                success: function (response) {
                    var parse_data = JSON.parse(response);
                    console.log(parse_data);

                    if (parse_data.status == 0) {
                        $(".rm_cls").remove();
                        $("#error_msg").text(parse_data.message);
                    } else {
                        $("#total_item").val(parse_data.total_item);
                        $("#error_msg").text("");
                    }
                }
            });
        } else {
            $(".rm_cls").remove();
        }
    });
});
/*********** Stock Audit ****************************/

$(document).ready(function ($) {

    $("#stock_audit_location_no").on('change', function () {
        var location_no = $(this).val();
        if (location_no) {
            $.ajax({
                type: 'POST',
                url: base_url + 'location-detail',
                data: { location_no: location_no },
                success: function (response) {
                    var parse_data = JSON.parse(response);
                    console.log(parse_data);

                    if (parse_data.status == 0) {
                        $(".rm_cls").remove();
                        $("#error_msg").text(parse_data.message);
                        $("#prod_list").html('');
                    } else {
                        $("#error_msg").text("");
                        $(".hid-btn").show();
                        $("#prod_list").html(parse_data.data);
                    }
                }
            });
            $(".hid-tbl").show();
        } else {
            $("#error_msg").text("");
        }
    });


    $("#stock_audit_box_no").on('change', function () {
        var box_no = $(this).val();
        var location_no = $("#stock_audit_location_no").val();
        if (box_no && location_no) {
            $.ajax({
                type: 'POST',
                url: base_url + 'box-detail',
                data: {
                    box_no: box_no, location_no: location_no
                },
                success: function (response) {
                    var parse_data = JSON.parse(response);
                    console.log(parse_data);

                    if (parse_data.status == 0) {
                        $(".rm_cls").remove();
                        $("#error_msg").text(parse_data.message);
                        $("#Verified_list").html('');
                        $("#stock_audit_box_no").val('');
                    } else {
                        $("#error_msg").text("");
                        $(".hid-btn").show();
                        $("#Verified_list").append(parse_data.data);
                        $("#stock_audit_box_no").val('');
                        removeRow(box_no);
                    }
                }
            });
            $(".hid-tbl").show();
        } else {
            $("#error_msg").text("");
        }
    });


    function removeRow(box_no) {
        var rows = $("#prod_list tr");
        rows.each(function () {
            var rowBoxNo = $(this).find("td:nth-child(5)").text();
            if (rowBoxNo == box_no) {
                $(this).remove();
            }
        });
    }

});

$(document).ready(function () {
    $("#rm_id").on('change', function () {
        var raw_material_id = $(this).val();

        if (raw_material_id) {
            $.ajax({
                type: 'POST',
                url: base_url + 'get-rm-hsn-code',
                data: { raw_material_id: raw_material_id },
                success: function (response) {
                    var parse_data = JSON.parse(response);
                    console.log("HSN Code:", parse_data);

                    if (parse_data.status == 1) {
                        $("#hsn_code").val(parse_data.hsn_code);
                        $("#error_msg").text('');
                    } else {
                        $("#hsn_code").val('');
                        $("#error_msg").text(parse_data.message);
                    }
                },
                error: function () {
                    $("#hsn_code").val('');
                    $("#error_msg").text('An error occurred while fetching HSN code.');
                }
            });
            $.ajax({
                type: 'POST',
                url: base_url + 'get-rm-unit',
                data: { raw_material_id: raw_material_id },
                success: function (response) {
                    var parse_data = JSON.parse(response);
                    console.log("Unit:", parse_data);

                    if (parse_data.status == 1) {
                        $("#inward_unit_id").val(parse_data.inward_unit_id);
                    } else {
                        $("#inward_unit_id").val('');
                        if ($("#error_msg").text() === '') {
                            $("#error_msg").text(parse_data.message);
                        }
                    }
                },
                error: function () {
                    $("#inward_unit_id").val('');
                    if ($("#error_msg").text() === '') {
                        $("#error_msg").text('An error occurred while fetching unit.');
                    }
                }
            });

        } else {
            $("#inward_unit_id").val('');
            $("#inward_unit_id").val('');
            $("#error_msg").text('');
        }
    });
});

$("#add-rm-grn").on('click', function () {
    var supplier = $("#supplier").val();
    var supplier_name = $('#supplier option:selected').text();

    if (supplier == '') {
        $("#js-error-msg").text("Please select vendor.");
        $("#h-id").show();
    } else {
        $(".nxt-btn").hide();
        $(".nxt-btn").html("");

        $('#supplier').attr('disabled', true);
        $("#supplier_id").val(supplier);
        $("#supplier_name").val(supplier_name);
        $(".hid-row").show();
    }
});
$(document).ready(function () {
    function calculateAmount() {
        var quantity = parseFloat($("#quantity").val()) || 0;
        var rate = parseFloat($("#rate").val()) || 0;
        var amount = quantity * rate;
        $("#amount").val(amount.toFixed(2));

        calculateAfterTax(); // Recalculate after tax too
    }

    function calculateAfterTax() {
        var amount = parseFloat($("#amount").val()) || 0;
        var taxRate = parseFloat($("#tax_id option:selected").text()) || 0; // Get text, which is the tax rate
        var afterTax = amount + (amount * taxRate / 100);
        $("#after_tax").val(afterTax.toFixed(2));
    }

    // On input change for quantity or rate
    $("#quantity, #rate").on('input', function () {
        calculateAmount();
    });

    // On tax dropdown change
    $("#tax_id").on('change', function () {
        calculateAfterTax();
    });
});

var raw_material_id_array = [];

$("#create-rm-grn").on('click', function () {
    var raw_material_name = $('#rm_id option:selected').text();
    var raw_material_id = $('#rm_id').val();
    var quantity = $('#quantity').val();
    var amount = $('#amount').val();
    var no_of_boxes = $('#no_of_boxes').val();
    var supplier_name = $('#supplier option:selected').text();
    var po_date = $('#po_date').val();

    if (raw_material_id === "") {
        $("#js-error-msg").text("Please select raw material");
        $(".h-id").show();
        return;
    }

    if (raw_material_id_array.includes(raw_material_id)) {
        $("#js-error-msg").text("Raw material is already added.");
        $(".h-id").show();
        return;
    }

    $(".h-id").hide();
    $(".hid-tbl").show();


    var newRowContent = `
            <tr id="tr_${raw_material_id}">
                <td>${supplier_name}</td>
                <td>
                    <input type="hidden" name="raw_material_id[]" value="${raw_material_id}">
                    ${raw_material_name}
                </td>
                <td>
                    <input type="hidden" name="quantity[]" value="${quantity}">
                    ${quantity}
                </td>
                <td>
                    <input type="hidden" name="amount[]" value="${amount}">
                    ${amount}
                </td>
                <td>
                    <input type="hidden" name="po_date[]" value="${po_date}">
                    ${po_date}
                </td>
                <td>
                    <input type="hidden" name="no_of_boxes[]" value="${no_of_boxes}">
                    ${no_of_boxes}
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="remove_tr('${raw_material_id}')">
                        <span class="fe fe-trash-2"></span>
                    </button>
                </td>
            </tr>
        `;

    $("#grn-tbl tbody").append(newRowContent);
    raw_material_id_array.push(raw_material_id);

    // $('#rm_id').val('').trigger('change');
    // $('#quantity').val('');
    // $('#amount').val('');
    $('#no_of_boxes').val('');
});

function remove_tr(raw_material_id) {
    $("#tr_" + raw_material_id).remove();
    raw_material_id_array = raw_material_id_array.filter(function (id) {
        return id !== raw_material_id;
    });

    if ($("#grn-tbl tbody tr").length === 0) {
        $(".hid-tbl").hide();
    }
}

/*** RM Quality Check */
$("#rm_grn_id").on('change', function () {
    var rm_grn_id = $(this).val();
    $.ajax({
        cache: false,
        url: base_url + 'rm-grn-detail',
        type: "POST",
        data: { rm_grn_id: parseInt(rm_grn_id) },
        success: function (html) {
            //var items = [];
            //items.push(html);
            $("#rm_grn_list").html(html);
            $(".hid-tbl").show();
        },
    });
});
$(".no_of_boxes_").on('change', function () {
    var no_of_boxes = $(this).val();
    console.log(no_of_boxes);
    if (no_of_boxes != '') {
        $.ajax({
            type: 'POST',
            url: base_url + 'show-input-rm-box',
            data: { no_of_boxes: no_of_boxes },
            success: function (htmlresponse) {
                $('.rm-box').remove();
                $('#box_no').after(htmlresponse);
            }
        });
    } else {
        $('.rm-box').remove();
    }
});

/*********** RM PUT AWAY ****************************/
$(document).ready(function ($) {
    $("#location_no").focus();
    $("#rm_location_no").on('change', function () {
        var rm_location_no = $(this).val();
        if (rm_location_no) {
            $.ajax({
                type: 'POST',
                url: base_url + 'show-location-detail',
                data: { rm_location_no: rm_location_no },
                success: function (response) {
                    if (response.length > 0) {
                        $("#error_msg").text("");
                        $("#error_msg").hide();
                        var parse_data = JSON.parse(response);
                        $("#location_name").val(parse_data.location_name);
                        $(".l_name").show();
                    } else {
                        $("#location_name").val("");
                        $(".l_name").hide();
                        $("#error_msg").text("No record found!");
                        $("#error_msg").show();
                    }
                }
            });
        }
    });

    $("#rm_box_no").on('change', function () {
        var box_no = $(this).val();
        var location_no = $('#location_no').val();
        console.log(location_no)
        if (box_no) {
            $.ajax({
                type: 'POST',
                url: base_url + 'show-rm-detail',
                data: { box_no: box_no, location_no: location_no },
                success: function (response) {
                    $("#p_detail").html(response);
                    $("#rm_box_no").val("");
                }
            });
        }
    });
});

$(document).ready(function () {
    $(".refresh-btn-store").on('click', function () {
        $("#store").attr('disabled', false);
    });
});

$(".qc_no_of_boxes").on('change', function () {
        var no_of_boxes = $(this).val();
        console.log(no_of_boxes);
        if (no_of_boxes != '') {
            $.ajax({
                type: 'POST',
                url: base_url + 'show-rm-input-box',
                data: { no_of_boxes: no_of_boxes },
                success: function (htmlresponse) {
                    $('.rm-box').remove();
                    $('#box_no').after(htmlresponse);
                }
            });
        } else {
            $('.rm-box').remove();
        }
    });
/*********** Create Sfg Grn ****************************/

$(document).ready(function () {
    var sfg_id_array = [];
    $("#create-sfg-grn").on('click', function () {
        var sfg_name = $('#sfg_id option:selected').text();
        var sfg_id = $('#sfg_id').val();
        var quantity = $('#quantity').val();
        var amount = $('#amount').val();
        var no_of_boxes = $('#no_of_boxes').val();
        var supplier_name = $('#supplier option:selected').text();
        var po_date = $('#po_date').val();

        if (sfg_id === "") {
            $("#js-error-msg").text("Please select sfg");
            $(".h-id").show();
            return;
        }

        if (sfg_id_array.includes(sfg_id)) {
            $("#js-error-msg").text("Sfg is already added.");
            $(".h-id").show();
            return;
        }

        $(".h-id").hide();
        $(".hid-tbl").show();


        var newRowContent = `
            <tr id="tr_${sfg_id}">
                <td>${supplier_name}</td>
                <td>
                    <input type="hidden" name="sfg_id[]" value="${sfg_id}">
                    ${sfg_name}
                </td>
                <td>
                    <input type="hidden" name="quantity[]" value="${quantity}">
                    ${quantity}
                </td>
                <td>
                    <input type="hidden" name="amount[]" value="${amount}">
                    ${amount}
                </td>
                <td>
                    <input type="hidden" name="po_date[]" value="${po_date}">
                    ${po_date}
                </td>
                <td>
                    <input type="hidden" name="no_of_boxes[]" value="${no_of_boxes}">
                    ${no_of_boxes}
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="remove_tr('${sfg_id}')">
                        <span class="fe fe-trash-2"></span>
                    </button>
                </td>
            </tr>
        `;

        $("#grn-tbl tbody").append(newRowContent);
        sfg__id_array.push(sfg_id);

        // $('#rm_id').val('').trigger('change');
        // $('#quantity').val('');
        // $('#amount').val('');
        $('#no_of_boxes').val('');
    });
});

function remove_tr(sfg_id) {
    $("#tr_" + sfg_id).remove();
    sfg_id_array = sfg_id_array.filter(function (id) {
        return id !== sfg_id;
    });

    if ($("#grn-tbl tbody tr").length === 0) {
        $(".hid-tbl").hide();
    }
}
$("#add-sfg-grn").on('click', function () {
    var supplier = $("#supplier").val();
    var supplier_name = $('#supplier option:selected').text();

    if (supplier == '') {
        $("#js-error-msg").text("Please select vendor.");
        $("#h-id").show();
    } else {
        $(".nxt-btn").hide();
        $(".nxt-btn").html("");

        $('#supplier').attr('disabled', true);
        $("#supplier_id").val(supplier);
        $("#supplier_name").val(supplier_name);
        $(".hid-row").show();
    }
});
/*** Sfg Quality Check */
$("#sfg_grn_id").on('change', function () {
    var sfg_grn_id = $(this).val();
    $.ajax({
        cache: false,
        url: base_url + 'sfg-grn-detail',
        type: "POST",
        data: { sfg_grn_id: parseInt(sfg_grn_id) },
        success: function (html) {
            //var items = [];
            //items.push(html);
            $("#sfg_grn_list").html(html);
            $(".hid-tbl").show();
        },
    });
});
$(".no_of_boxes_").on('change', function () {
    var no_of_boxes = $(this).val();
    console.log(no_of_boxes);
    if (no_of_boxes != '') {
        $.ajax({
            type: 'POST',
            url: base_url + 'show-input-sfg-box',
            data: { no_of_boxes: no_of_boxes },
            success: function (htmlresponse) {
                $('.sfg-box').remove();
                $('#box_no').after(htmlresponse);
            }
        });
    } else {
        $('.sfg-box').remove();
    }
});

$(document).ready(function () {
    $("#sfg_id").on('change', function () {
        var sfg_id = $(this).val();

        if (sfg_id) {
            $.ajax({
                type: 'POST',
                url: base_url + 'get-sfg-hsn-code',
                data: { sfg_id: sfg_id },
                success: function (response) {
                    var parse_data = JSON.parse(response);
                    console.log("HSN Code:", parse_data);

                    if (parse_data.status == 1) {
                        $("#hsn_code").val(parse_data.hsn_code);
                        $("#error_msg").text('');
                    } else {
                        $("#hsn_code").val('');
                        $("#error_msg").text(parse_data.message);
                    }
                },
                error: function () {
                    $("#hsn_code").val('');
                    $("#error_msg").text('An error occurred while fetching HSN code.');
                }
            });
        };
    });
});

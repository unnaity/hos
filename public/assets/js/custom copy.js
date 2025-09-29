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
            else{
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
            else{
            ActiveSubmenu();
            }
            checkHoriMenu();
            responsive();
            document.querySelector('.horizontal .nav.hor-menu').style.flexWrap = 'nowrap'
        }        
    }

    function supplier_option(){
        $.ajax({
            type: "POST",
            url: base_url+'supplier-option',
            data: $(this).attr('id'), //--> send id of checked checkbox on other page
            success: function(data) { 
                console.log(data);           		
                $('.supplier-option').html(data);
            },
            error: function() {
            }
        });
    }

    $('#buy').click(function() {   
        if(this.checked){
            supplier_option();    
        }else{            
            var make_check = $("input[id='buy']:checked").length;
            if(!make_check.checked){            
                $("#make").prop( "checked", true );
                console.log('make is checked');                
            }
            $('.supplier-option').html("");
        }
    }); 
    
    $('#make').click(function() {   
        if(!this.checked){        
            //var buy_check = $("#buy");
            var buy_check = $("input[id='buy']:checked").length;
            if(!buy_check){
                $("#buy").prop( "checked", true );
                supplier_option();
            }
            //$('.supplier-option').html("");
        }
    }); 
    
    $('#multiple_variant').click(function() {   
        if(this.checked){
            $("#variant_tbl").hide();    
        }else{
            $(".no-border").val("");
            $("#variant_tbl").show();            
        }
    });  

	function confirm_modal(delete_url)
	{
		jQuery('#confirm-modal').modal('show', {backdrop: 'static'});
		document.getElementById('delete_link').setAttribute('href' , delete_url);
	}
    var product_id_array = [];
    var btn_click = 0;
    $(document).ready(function($) {
        $("#category_id").on('change', function() {            
            var category_id = $(this).val();            
            if(category_id){
                $.ajax ({
                    type: 'POST',
                    url: base_url+'options/index/cateory-wise-product-list',
                    data: {category_id:category_id},
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

        $("#no_of_boxes").on('change', function() { 
            var no_of_boxes = $("#no_of_boxes").val();
            console.log(no_of_boxes);
            if(no_of_boxes != ''){
                $.ajax ({
                    type: 'POST',
                    url: base_url+'show-input-box',
                    data: {no_of_boxes:no_of_boxes},
                    success : function(htmlresponse) {
                        $('.rm-box').remove();
                        $('#box_no').after(htmlresponse);
                    }
                });
            }else{
                $('.rm-box').remove();
            }
        });

        $("#create-product-grn").on('click', function() {
            btn_click = btn_click+1;
            var invoice_type = $("#invoice_type").val();
            var category_name = $('#category_name').val();
            var category_id = $('#category_id').val();
            var product_name = $('#product_name').val();
            var product_id = $('#product_id').val();
            var no_of_boxes = $('#no_of_boxes').val();
            var no_of_items = $('#no_of_items').val();           
            var supplier_id = $("#supplier_id").val();
            var mfg_date = $("#mfg_date").val();            
            var expiry_date = $("#expiry_date").val();
            console.log(mfg_date);
            console.log(expiry_date);
            var supplier_name = $('#supplier_id option:selected').text();
            //po_no=0;
            /*if(po_no == ''){                
                $("#js-error-msg").text("Please enter purchase order no.");
                $("#h-id").show();
            }
            else*/
            //console.log(expiry_date);

            if(invoice_type == ''){                
                $("#js-error-msg").text("Please select invoice type.");
                $("#h-id").show();
            }else if(supplier_id == ''){                
                $("#js-error-msg").text("Please select vendor.");
                $("#h-id").show();
            }else if(category_id == ''){                
                $("#js-error-msg").text("Please select category.");
                $("#h-id").show();
            }else if(product_id == ''){                
                $("#js-error-msg").text("Please select product.");
                $("#h-id").show();
            }else if(no_of_items == ''){                
                $("#js-error-msg").text("Please enter no. of items.");
                $("#h-id").show();
            }else if(no_of_items == 0){                
                $("#js-error-msg").text("No. of items should be greater than zero.");
                $("#h-id").show();
            }else if(no_of_boxes == ''){                
                $("#js-error-msg").text("Please enter no. of boxes.");
                $("#h-id").show();
            }else if(no_of_boxes == 0){
                $("#js-error-msg").text("No. of boxes should be greater than zero.");
                $("#h-id").show();
            }else if(mfg_date != '' && expiry_date != ''){
                if(mfg_date > expiry_date){
                    $("#js-error-msg").text("MFG date must be less than Expiry date.");
                    $("#h-id").show();
                }
            }else{
                if($.inArray(product_id, product_id_array) != -1) {                    
                    $("#js-error-msg").text("Product is already added.");
                    $("#h-id").show();  
                } else {
                    $("#h-id").hide();
                   
                    var box_html = '';
                    var total_box_val = 0;
                    for (var i = 1; i <= no_of_boxes; i++) {
                        var box_val = $("#box_item_"+i).val();
                        if(box_val > 0){
                            total_box_val = parseInt(total_box_val)+parseInt(box_val);
                            console.log(total_box_val);
                            box_html = box_html+"<br><input type=\"hidden\" name=\"box_no_"+product_id+"[]\" value=\"" + box_val + "\"> Box "+i+": "+box_val+" items";
                        }else{
                            $("#js-error-msg").text("Please enter no. of item in Box "+i);
                            $("#h-id").show();
                        }                        
                    }
                    if(total_box_val != no_of_items){
                        $("#js-error-msg").text("Total no. of items and sum of boxes value does not match.");
                        $("#h-id").show();
                    }else{

                        if(mfg_date == ''){mfg_date = ' ';}
                        if(expiry_date == ''){expiry_date = ' ';}

                        $(".hid-tbl").show();
                        var newRowContent = "<tr id=\"tr_"+product_id+"\"><td><input type=\"hidden\" name=\"supplier_id[]\" value=\"" + supplier_id + "\">"+supplier_name+"</td><td><input type=\"hidden\" name=\"category_id[]\" value=\"" + category_id + "\">"+category_name+"</td><td><input type=\"hidden\" name=\"product_id[]\" value=\"" + product_id + "\">"+product_name+"</td><td><input type=\"hidden\" name=\"mfg_date_array[]\" value=\"" + mfg_date + "\">"+mfg_date+"</td><td><input type=\"hidden\" name=\"expiry_date_array[]\" value=\"" + expiry_date + "\">"+expiry_date+"</td><td><input type=\"hidden\" name=\"no_of_items[]\" value=\"" + no_of_items + "\">"+no_of_items+"</td><td><input type=\"hidden\" name=\"no_of_boxes[]\" value=\"" + no_of_boxes + "\">"+no_of_boxes+""+box_html+"</td><td><button type=\"button\" name=\"remove\" onclick=\"remove_tr("+product_id+")\" class=\"btn  btn-sm btn-danger\"><span class=\"fe fe-trash-2\"> </span></button></td></tr>";
                        $("#grn-tbl tbody").append(newRowContent);                    
                        product_id_array.push(product_id);

                        //$('#product_id').val("");                
                        $('#no_of_items').val("");
                        $('#no_of_boxes').val("");
                        $('.rm-box').remove();
                    }
                }
                
                //$('#create-grn').trigger("reset");
                //$("#create-grn").load(location.href + " #create-grn");
            }
        });   
        
        $("#create-sales-order").on('click', function() {
            btn_click = btn_click+1;
            var new_prod_name = '';
            var customer_id = $('#customer_id').val();
            var category_name = $('#category_name').val();
            var category_id = $('#category_id').val();
            var product_name = $('#product_name').val();
            var product_id = $('#product_id').val();
            var quantity = $('#quantity').val();
            var price = $('#price').val();
            var product_alias_id = $('#product_alias_id').val();
           console.log(product_alias_id);

            if(customer_id == ''){
                $("#h-id").show();
                $("#js-error-msg").text("Please select customer");
            }else if(category_id == ''){
                $("#h-id").show();
                $("#js-error-msg").text("Please select category");
            }else if(product_id == ''){
                $("#h-id").show();
                $("#js-error-msg").text("Please select product");
            }else if(quantity == ''){
                $("#h-id").show();
                $("#js-error-msg").text("Please enter quantity");
            }else if(price == ''){
                $("#h-id").show();
                $("#js-error-msg").text("Please enter price");
            }else{
                if($.inArray(product_id, product_id_array) != -1) {  
                    $("#h-id").show();  
                    $("#js-error-msg").text("Product is already added.");
                } else {
                    $("#h-id").hide();
                    $(".hid-tbl").show();
                    var unit_code = $('#product_id :selected').attr("data-unit");                   
                    if(product_alias_id != ''){
                        new_prod_name = $('#product_alias_id :selected').text();
                        console.log(new_prod_name);
                    }
                    var newRowContent = "<tr id=\"tr_"+product_id+"\"><td><input type=\"hidden\" name=\"category_id_array[]\" value=\"" + category_id + "\">"+category_name+"</td><td><input type=\"hidden\" name=\"product_id_array[]\" value=\"" + product_id + "\">"+product_name+"</td><td><input type=\"hidden\" name=\"product_alias_name_array[]\" value=\""+new_prod_name+"\">"+new_prod_name+"</td><td><input style='width:60px;' type=\"number\" name=\"quantity_array[]\" value=\""+quantity+"\"></td><td>"+unit_code+"</td><td><input style='width:60px;' type=\"hidden\" name=\"price_array[]\" value=\""+price+"\">"+price+"</td><td><input type=\"button\" name=\"remove\" onclick=\"remove_tr("+product_id+")\" value=\"remove\"></td></tr>";
                    
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

        $("#create-purchase-order").on('click', function() {
            btn_click = btn_click+1;
            
            var customer_id = $('#customer_id').val();
            var category_name = $('#category_name').val();
            var category_id = $('#category_id').val();
            var product_name = $('#product_name').val();
            var product_id = $('#product_id').val();
            var quantity = $('#quantity').val();
            var price = $('#price').val();
            var po_date = $('#po_date').val();
            //var delivery_date = $('#delivery_date').val();

            if(po_date == ''){
                $("#h-id").show();
                $("#js-error-msg").text("Please select purchase order Date");
            }else if(customer_id == ''){
                $("#h-id").show();
                $("#js-error-msg").text("Please select customer");
            }else if(category_id == ''){
                $("#h-id").show();
                $("#js-error-msg").text("Please select category");
            }else if(product_id == ''){
                $("#h-id").show();
                $("#js-error-msg").text("Please select product");
            }else if(quantity == ''){
                $("#h-id").show();
                $("#js-error-msg").text("Please enter quantity");
            }else if(price == ''){
                $("#h-id").show();
                $("#js-error-msg").text("Please enter price");
            }else{
                if($.inArray(product_id, product_id_array) != -1) {  
                    $("#h-id").show();  
                    $("#js-error-msg").text("Product is already added.");
                } else {
                    $("#h-id").hide();
                    $(".hid-tbl").show();
                    var unit_code = $('#product_id :selected').attr("data-unit");                    
                    var newRowContent = "<tr id=\"tr_"+product_id+"\"><td><input type=\"hidden\" name=\"category_id_array[]\" value=\"" + category_id + "\">"+category_name+"</td><td><input type=\"hidden\" name=\"product_id_array[]\" value=\"" + product_id + "\">"+product_name+"</td><td><input style='width:60px;' type=\"number\" name=\"quantity_array[]\" value=\""+quantity+"\"></td><td>"+unit_code+"</td><td><input style='width:60px;' type=\"hidden\" name=\"price_array[]\" value=\""+price+"\">"+price+"</td><td><input type=\"button\" name=\"remove\" onclick=\"remove_tr("+product_id+")\" value=\"remove\"></td></tr>";
                    
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

        $(".on_change").on('change', function() {
            product_name();
        });

        $("#customer_id").on('change', function() {
            var act = $("#act").val();
            var customer_id = $(this).val();
            if(act == 'get_order'){
                get_order_list(customer_id);
            }            
        });

        $("#order_id").on('change', function() {
            var sales_order_id = $(this).val();            
            get_sales_order_product_list(sales_order_id);
        });

        $(".show-hsn").on('change', function() {
            var category_id = $(this).val();
            if(category_id > 0){
                window.location.href = base_url+'add-product/'+category_id;
            }
            //var hsn_code = $('option:selected', this).attr('data-hsn');
            //$("#hsn_code").val(hsn_code);
            
        });

        $(".show_location").on('change', function() {
            show_location();
        });

        $("#invoice_type").on('change', function() {
            var invoice_type = $(this).val();
            if(invoice_type == 'bill_no'){
                $("#bill_id").html('Bill No.: <span class="tx-danger">*</span>');
            }else if(invoice_type == 'delivery_challan'){
                $("#bill_id").html('Delivery Challan No.:');
            }
        });
    });

    function product_name(){        
        var oem = '';
        var model = '';
        var quality_id = '';
        var size = '';
        var materialquality = '';
        var oem_id = $('#oem_id').val();
        var model_id = $('#model_id').val();
        var quality_id = $('#quality_id').val();
        var size_id = $('#size_id').val();

        if(oem_id != ''){
            var oem = $('#oem_id option:selected').text();    
        }

        if(model_id != ''){
            var model = $('#model_id option:selected').text();
        }
        if(quality_id != ''){
            var materialquality = $('#quality_id option:selected').text();
        }
        if(size_id != ''){
            var size = $('#size_id option:selected').text();
        }       
        var sku = $('#product_sku').val(); 
                
        var new_product_name = oem+' '+model+' '+materialquality+' '+size+' '+sku;
        $("#product_name").val($.trim(new_product_name));
    }

    function show_location(){
        var floor_no = $('#floor_no option:selected').text();    
        var room_no = $('#room_no option:selected').text();    
        var rack_no = $('#rack_no option:selected').text();    
        var shelf_no = $('#shelf_no option:selected').text();    
        var bin_no = $('#bin_no option:selected').text();    
        
        var location_name = $.trim(floor_no)+'/'+$.trim(room_no)+'/'+$.trim(rack_no)+'/'+$.trim(shelf_no)+'/'+$.trim(bin_no);
        $("#location_name").val($.trim(location_name));
    }

    function remove_tr(product_id){        
        product_id_array = $.grep(product_id_array, function(n) {
            return n != product_id;
          });        
        $("#tr_"+product_id).remove();
    }

    function show_input(product_id){
        var category_name = $('#category_id :selected').text();
        var product_name = $('#product_id :selected').text();
        show_alias_name(product_id)
        if(product_id){
            $('.h-id').show();
            $('#category_name').val($.trim(category_name));
            $('#product_name').val(product_name);
        }else{
            $('.h-id').hide();
            $('.h-cls').val("");
        }
    }

    function show_alias_name(product_id){        
        if(product_id){
            $.ajax ({
                type: 'POST',
                url: base_url+'options/index/product-alias-list',
                data: {product_id:product_id},
                success: function (html) {                        
                    var items = [];
                    items.push(html);
                    $("#product_alias_id").html(items.join(' '));                      
                },               
            });
        }        
    }

    function change_branch(branch_id){
        $.ajax({
            type: "POST",
            url: base_url+'change-branch',
            data: {branch_id:branch_id},
            success: function(data) { 
                location.reload(true);
            },
            error: function() {
            }
        });
    }

    function change_store(store_id){
        $.ajax({
            type: "POST",
            url: base_url+'change-store',
            data: {store_id:store_id},
            success: function(data) { 
                location.reload(true);
            },
            error: function() {
            }
        });
    }

    function get_order_list(customer_id){
        if(customer_id){
            $.ajax({
                cache: false,
                url: base_url+'get-order-list',
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

    function get_sales_order_product_list(sales_order_id){
        if(sales_order_id){
            $.ajax({
                cache: false,
                url: base_url+'sales-order-product-list',
                type: "POST",
                data: { sales_order_id: parseInt(sales_order_id) },
                success: function (html) {
                    var items = [];
                    items.push(html);
                    $("#prod_list").html(items.join(' '));
                    $(".hid-tbl").show();
                },
            });
        }
    }

    /************************Create Put Away Page **********/
    $(document).ready(function($) {
        $("#location_no").focus();
        $("#location_no").on('change', function() {            
            var location_no = $(this).val();
            if(location_no){
                $.ajax ({
                    type: 'POST',
                    url: base_url+'show-location-detail',
                    data: {location_no:location_no},
                    success : function(response) {                        
                        if(response.length > 0){
                            $("#error_msg").text("");
                            $("#error_msg").hide();
                            var parse_data = JSON.parse(response);                            
                            $("#location_name").val(parse_data.location_name);
                            $(".l_name").show();
                        }else{                    
                            $("#location_name").val("");
                            $(".l_name").hide();        
                            $("#error_msg").text("No record found!");
                            $("#error_msg").show();
                        }
                    }
                });
            }
        });

        $("#box_no").on('change', function() {            
            var box_no = $(this).val();            
            if(box_no){
                $.ajax ({
                    type: 'POST',
                    url: base_url+'show-product-detail',
                    data: {box_no:box_no},
                    success : function(response) {                        
                        $("#p_detail").html(response);
                    }
                });
            }
        });
    });


    /************************Create Pick List Page **********/
    $(document).ready(function($) {        
        $("#pick_list_box_no").on('change', function() {            
            var pick_list_box_no = $(this).val();
            var order_id = $("#order_id").val();
            var delivery_date = $("#delivery_date").val();
            if(pick_list_box_no){
                $.ajax ({
                    type: 'POST',
                    url: base_url+'show-box-detail',
                    data: {pick_list_box_no:pick_list_box_no,order_id:order_id,delivery_date:delivery_date},
                    success : function(response) {
                        var parse_data = JSON.parse(response); 
                        console.log(parse_data);
                        if(parse_data.status == 0){
                            $("#error_msg").text(parse_data.message);
                        }else{
                            $("#error_msg").text("");
                            $(".pick-list-tbl, .hid-btn").show();
                            $("#pick_list").append(parse_data.data); 
                        }
                    }
                });
            }
        });

    });
    

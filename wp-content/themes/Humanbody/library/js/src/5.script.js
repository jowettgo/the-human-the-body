/**
 * $('#datepicker-example10').Zebra_DatePicker({
  view: 'years'
});
 */

var $ = jQuery;
// start document ready
$(document).ready(function(){

	$('.not-premium').fancybox({
		beforeLoad: function(element){
			$('#are-you-sure p').html($(this.element).attr('data-message'));
			$('#are-you-sure .remove-yes').attr('href', $(this.element).attr('data-href'));
		}
	});

	$('.custom-message .remove-no').click(function(){
		parent.$.fancybox.close();
	});

	$(document).on('click', 'body .message-info .items p', function(){
		var el = $(this);
		el.fadeOut(200, function(){
			el.parent().find('div').fadeIn();
		});

	});

	/* mobile video / image placeholder script */
	var ww = $(window).width();
	if(ww < 768) {
		$('#bgvid').remove();
		$('#mobile-video-placeholder').removeClass('hidden').show();
	}


	$('#joined .load-more').click(function(){
		var nr = parseFloat($(this).attr('data-load'));
		for(i=0; i<=nr; i++){
			$('#joined .table-condensed tbody tr:nth-child('+i+')').removeAttr('style');
		}
		$(this).attr('data-load', nr + 5);
		var count = $("#joined .table-condensed tbody").children().length
		if(nr > count ){
			$(this).remove()
		}
	});


	// map page

	if($('#mapdiv').length > 0) {
		var map = AmCharts.makeChart("mapdiv", {
			type : "map",
			theme : "dark",
			addClassNames: true,
			pathToImages : "http://cdn.amcharts.com/lib/3/images/",
			panEventsEnabled : true,
			backgroundColor : "transparent",
			backgroundAlpha : 1,
			zoomControl : {
				zoomControlEnabled : true
			},
			dataProvider : {
				map : "worldHigh",
				getAreasFromMap : true,
				areas : []
			},
			areasSettings : {
				autoZoom : true,
				color : "#fff",
				colorSolid : "#84ADE9",
				selectedColor : "rgba(28,28,28, 0.8)",
				outlineColor : "#c5c2c3",
				rollOverColor : "rgba(28,28,28, 0.35)",
				rollOverOutlineColor : "#000000"
			}
		});

		$('#mapdiv svg g g g path').on('click', function(){
			alert(1);
		});
	}

	// show notifications

	$('.inner-bell').click(function(){
		$('.my-account-list').slideUp(100);
		$('.notifications-list').slideToggle();
	});

	$('.user-name').click(function(){
		$('.notifications-list').slideUp(100);
		$('.my-account-list').slideToggle();
	});


	$(document).click(function(event) {
		console.log(event); 
	  // if( $(event.target).attr('class') != 'notifications-list' &&  $(event.target).attr('class') != 'inner-bell') {
	  //   $(".notifications-list").slideUp();
	  // }
	});




	// upload image profile
	var readURL = function(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$('.profile-pic-upload').attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		}
	}


	$(".file-upload").on('change', function(){
		readURL(this);
	});

	$(".upload-button").on('click', function() {
		$(".file-upload").click();
	});

	// input checked membership popup
	$('.avatar-popup.membership .avatar-pic input').click(function () {
		$('input:not(:checked)').parent().removeClass("checked");
		$('input:checked').parent().addClass("checked");
	});


	// start menu
	$('#menu-button').click(function(){

		el = $('#nav-icon');

		el.toggleClass('open');

		mobileMenu();

	});

	var menuH = $('.main-menu').height();
	var windowH = $(window).height();

	if (menuH > windowH) {
		$('.main-menu').css({
			'overflow':'auto',
			'max-height': windowH
		});
	}else{
		$('.main-menu').removeAttr("style");
	};
	// end menu

	// start show user
	$('.sign-up, .sign-in').on('click', function(){

		$('.user-wrapper').fadeIn(300);
		$('.sign-up-wrapper').hide();

	});
	// end show user

	// video slow down
	var vid = document.getElementById("bgvid");

	if($('#bgvid').length > 0){
		vid.playbackRate = 0.5;
	};
	// end video slow down

	// start equalheight
	equalheight('.article .inner-wrap');
	equalheight('.first-article-row .article-small .inner-wrap');
	equalheight('.second-article-row .article-small .inner-wrap');
	equalheight('.align-middle');
	equalheight('.category-section .section-inner p');
	equalheight('.col-20-percent');
	equalheight('.filter-blog-featured h3');


	// end equalheight

	// start detail article half container
	containerHalf();
	// end detail article half container

	// start scroll top
	$(".scroll-top").click(function(e){
		e.preventDefault;
		var body = $("html, body");
		body.stop().animate({scrollTop:0}, '500', 'swing', function() {
		});
	});



    $('.scroll-down').click(function(){
	    $('html, body').animate({
	        scrollTop: $( $.attr(this, 'href') ).offset().top
	    }, 500);
	    return false;
	});

	// $('.table-section table tr td p a').each(function(){
	// 	var text = $(this).text().length;
	// 	if(text >= 47){
	// 		$(this).append('...');
	// 	}

	// });



	// end scroll top


	// start footer bottom
	footerBottom()
    // end footer bottom


	// start stickyUpload
	if ($('.upload-photo').length > 0 ) {
		stickyUpload();
	};
    // end stickyUpload

    contentHeight('.research-subpage');

    // windowHeight('.afectiuni-section');
    windowHeight('.secondary-subpage.sing-in .top-banner-wrapper');


    $('.category-select').ddslick({
    	selectText: "categories"
    });

    $('.category-select').on('click', function(){
    	// $('#nav-icon-category').toggleClass('open');
    });


	// start gallery slideshow

	$('.galley-slideshow').slick({
		dots: false,
		infinite: true,
		speed: 300,
		slidesToShow: 6,
		slidesToScroll: 6,
		responsive: [

		{
			breakpoint: 1600,
			settings: {
				slidesToShow: 5,
				slidesToScroll: 5,
				infinite: true
			}
		},
		{
			breakpoint: 1300,
			settings: {
				slidesToShow: 4,
				slidesToScroll: 4,
				infinite: true
			}
		},
		{
			breakpoint: 1024,
			settings: {
				slidesToShow: 3,
				slidesToScroll: 3,
				infinite: true
			}
		},
		{
			breakpoint: 778,
			settings: {
				slidesToShow: 2,
				slidesToScroll: 2,
				infinite: true,
			}
		},
		{
			breakpoint: 480,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1,
				infinite: true,
			}
		}
		]
	});
	// end gallery slideshow

	$('.afectiuni-slideshow').slick({
		dots: false,
		infinite: true,
		speed: 300,
		slidesToShow: 9,
		slidesToScroll: 4,
		responsive: [

		{
			breakpoint: 2100,
			settings: {
				slidesToShow: 7,
				slidesToScroll: 4,
				infinite: true
			}
		},

		{
			breakpoint: 1600,
			settings: {
				slidesToShow: 5,
				slidesToScroll: 4,
				infinite: true
			}
		},
		{
			breakpoint: 1200,
			settings: {
				slidesToShow: 3,
				slidesToScroll: 4,
				infinite: true
			}
		},

		{
			breakpoint: 778,
			settings: {
				slidesToShow: 2,
				slidesToScroll: 2,
				infinite: true
			}
		},
		{
			breakpoint: 480,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 2,
				infinite: true
			}
		},
		]
	});

	 // start close fancybox

	 $('.close-popup-wrapper').on('click', function(){
	 	$('.fancybox-close').trigger('click');
	 });

	 // end close fancybox

	// pause/play slider
	$('.onoffswitch-label').on('click', function(){
		var el = $(this);

		if (el.hasClass('active')) {
			el.removeClass('active');
			$('.product-slider').slick('slickPause');
		}else{
			el.addClass('active');
			$('.product-slider').slick('slickPlay');
		};
	});
	// pause/play slider

	$('.activate-slider').on('click', function(){
		var el = $(this);
		var elData = el.attr('data-index');

		// $('inner-thumb').indexOf(2);
		// $('.product-slider').slickGoTo(2);
		galleryFancyBox(elData);

	});


	//start datepicker
	$('#birth-date').Zebra_DatePicker({
	  view: 'years',
	  format: 'm/d/Y'
	});


	$('#meeting-date').datepicker();
	//end datepicker

	$('#time-date').timepicker({
		showPeriod: true,
		showLeadingZero: true
	});

	//start avatar popup
	$('.chose-wrapper a').fancybox({
		'autoSize': false,
		'width': '750px',
	});

	//start avatar popup
	$('.fancy-therapeut').fancybox({
		'autoSize': false,
		'width': '750px',
	});

	$('.add-topic').fancybox({
		'autoSize': false,
		'width': '750px',
		afterLoad: function () {
			$('.fancybox-wrap').addClass('show-close');
		}
	});

	$('.add-together').fancybox({
		'autoSize': false,
		'width': '750px',
		afterLoad: function () {
			$('.fancybox-wrap').addClass('show-close');
		}
	});

	$('.max-allow').fancybox({
		'autoSize': false,
		'width': 'auto',
	});

	// $(".membership .get-membership a").fancybox({
	// 	'autoSize': false,
	// 	'width': '750px',
	// 	afterLoad: function () {
	// 		$('.fancybox-wrap').addClass('height-auto');
	// 	}
	// });


$('.cancel-popup').on('click', function(e){
	e.preventDefault();
	$('.fancybox-close').click();
});
	//end avatar popup



	$('.avatar-outer-wrap').on('click', function(){
		var el = $(this);

		if (el.hasClass('active')) {
			el.removeClass('active');
		}else{
			$('.avatar-outer-wrap, .avatar-outer-wrap img').removeClass('active');
			el.addClass('active');
			el.find('img').addClass('active');
		};
	});

	// start take avatar img attr and send it to hidden field value
	$('.select-avatar').on('click', function(e){
		e.preventDefault();

		var activeImg = $('.avatar-outer-wrap.active img').attr('alt');

		$('#profile-pic').val(activeImg);

		$('.fancybox-close').click();
	});
	// start take avatar img attr and send it to hidden field value


	//scroll pane
	$('.avatar-list').jScrollPane({
		showArrows: false,
		autoReinitialise: true
	}).mousewheel();

	//scroll pane

	//start ddSlick
	$('.profile-information select').ddslick();
	//End ddSlick


	// start remove messages
	$('.delete-message').on('click', function(){
		$(this).closest('li').hide();
	});

	$('.delete-conversation').on('click', function(e){
		e.preventDefault();
		$(this).closest('form').find('.conversation-list').hide();
		$(this).hide();
	});
	// end remove messages


	$('.ui.dropdown').dropdown();

	//remove from array and return array whithout that val
	function removeA(arr) {
	    var what, a = arguments, L = a.length, ax;
	    while (L > 1 && arr.length) {
	        what = a[--L];
	        while ((ax= arr.indexOf(what)) !== -1) {
	            arr.splice(ax, 1);
	        }
	    }
	    return arr;
	}

	//numara cate ite-muri sunt adaugate
	$(document).on('click', 'body .check .menu .item', function(){
		var el = $(this);
		var i = 0;
	    $('.check').each(function(){
	    	i += $(this).find('.visible').length
	    });
	    if(i>= 42){
	    	setTimeout(function(){
	    		$('.max-allow').trigger('click');
	    	}, 300);
	    	$('.check .label').each(function(){
	    		var el2 = $(this);
	    		if(el.data('value') == el2.data('value')){
	    			el2.remove();
	    		}
	    	});
	    	$('.check > div > input').each(function(){
	    		var str = $(this).val();
	    		var str = str.split(',');
	    		$(this).val(removeA(str, el.data('value')).toString());
	    	})
	    }

	});


	//people attending modal
	$('#participants_m_all').on('show.bs.modal', function (e) {
		var esseyId = e.relatedTarget.id;

		var split = esseyId.split('-');
        var element = split['1'];

        //apend table to modal
        $( "#participants_m_all .modal-body table").remove();
        $( "#participants_m_all .modal-body" ).append( $('.people_attending_append-'+element).clone());
        $( "#participants_m_all .modal-body table").show();

	})

});
// end document ready


$(window).load(function(){

	// start fancybox
	//galleryFancyBox();
	//end fancybox

	//#NOTE: check if there is any element to bind it to before initialization of a plugin
	if($('.grid').length > 0 ) {
		// start isotope
		$('.grid').isotope({
			itemSelector: '.grid-item',
			percentPosition: true,
			isFitWidth: true,
			masonry: {
			}
		})
	}
	// end isotope

	// start equalheight
	equalheight('.article .inner-wrap');
	equalheight('.membership-baners ul li div');
	equalheight('.membership-baners ul li p');
	equalheight('.first-article-row .article-small .inner-wrap');
	equalheight('.second-article-row .article-small .inner-wrap');
	equalheight('.align-middle');
	equalheight('.category-section .section-inner p');
	equalheight('.col-20-percent');
	equalheight('.banner-box');

	// end equalheight

});

// start window resize
$(window).resize(function(){

	if ($('.upload-photo').length > 0) {
		stickyUpload();
	};

	galleryFancyBox();

	contentHeight('.research-subpage');

	// windowHeight('.afectiuni-section');
	windowHeight('.secondary-subpage.sing-in .top-banner-wrapper');

	// start menu
	var menuH = $('.main-menu').height();
	var windowH = $(window).height();

	if (menuH > windowH) {
		$('.main-menu').css({
			'overflow':'auto',
			'max-height': windowH
		});
	}else{
		$('.main-menu').removeAttr("style");
	};
	// end menu

	// start equalheight
	equalheight('.article .inner-wrap');
	equalheight('.first-article-row .article-small .inner-wrap');
	equalheight('.second-article-row .article-small .inner-wrap');
	equalheight('.align-middle');
	equalheight('.category-section .section-inner p');
	equalheight('.membership-baners ul li div');
	equalheight('.membership-baners ul li p');
	equalheight('.col-20-percent');
	equalheight('.banner-box');
	// end equalheight

	// start footer bottom
	footerBottom();
    // end footer bottom

	// start detail article half container
	containerHalf();
	// end detail article half container

	$('#nav, html').removeAttr('style');

});
// end window resize

// Start Mobile menu function
function mobileMenu() {

	if($('#nav').css('right') != '-270px') {

		$('#nav').animate({
			right: -270,
		}, 300);

		$('#wrapper').animate({
			marginLeft: 0,
			marginRight: 0
		}, 300);

		$('#header').animate({
			right: 0
		}, 300);

		$('html').css('overflow-y', 'scroll');
		$('html').css('position','relative');
	} else {

		$('#nav').animate({
			right: 0
		}, 300);

		$('#wrapper').animate({
			marginLeft: -270,
			marginRight: 270
		}, 300);

		$('#header').animate({
			right: 270,
		}, 300);

		$('#nav').css('position','fixed');
		$('#nav').css('display','block');
		$('html').css('position','fixed');
		$('html').css('overflow-x', 'hidden');
	}
};
// end Mobile menu function

// start equalheight function
equalheight = function(container){

	var currentTallest = 0,
	currentRowStart = 0,
	rowDivs = new Array(),
	$el,
	topPosition = 0;
	$(container).each(function() {

		$el = $(this);
		$($el).height('auto')
		topPostion = $el.position().top;

		if (currentRowStart != topPostion) {
			for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
				rowDivs[currentDiv].height(currentTallest);
			}
			rowDivs.length = 0;
			currentRowStart = topPostion;
			currentTallest = $el.height();
			rowDivs.push($el);
		} else {
			rowDivs.push($el);
			currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
		}
		for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
			rowDivs[currentDiv].height(currentTallest);
		}
	});
};
// end equalheight function

// start article half container
function containerHalf() {

	var ww = $(window).width();

	if (ww > 970) {
		var containerW = $('.container').width() / 2;

		$('.left-container .left-inner').width(containerW);

		equalheight('.half-wrap');

	} else{
		$('.left-container .left-inner, .half-wrap').removeAttr('style');
	};
};
// end article half container

// start content height
function contentHeight(element){

	var wH = $(window).height();
	var hH = $('#header').height();
	var fH = $('#footer').height();
	var contentH = wH - hH - fH;
	var halfContentH = contentH / 2;

	var cTop = $('.subpage-top-inner').height();

	var cBot = $('.subpage-bottom-inner').height();
	var cInner = cTop + cBot;

	if (cInner < contentH) {
		$(element).height(contentH);
		$('.subpage-bottom-inner').css('position','absolute');
	}else{
		$(element).removeAttr('style');
		$('.subpage-bottom-inner').css('position','relative');
	};



	var ww = $(window).width();

	if (ww > 970) {
		var containerW = $('.container').width() / 2;

		$('.comm-general-wrap .text-inner').width(containerW);

		$('.community-wrapper').height(halfContentH);

	} else{
		$('.comm-general-wrap .text-inner, .community-wrapper').removeAttr('style');
	};

};


function windowHeight(element){
	var wW = $(window).width();
	var wH = $(window).height();
	var hH = $('#header').height();
	var fH = $('#footer').height();
	var contentH = wH - hH - fH;

	if (wW > 780 - 30) {
		$(element).height(contentH);
	}else{
		$(element).height('auto');
	};
};

// end content height

function footerBottom(){

	var footerHeight = $('#footer').height();
	var wW = $(window).width();

	if (wW > 780 - 30) {
		$('#wrapper').css('padding-bottom', footerHeight);

	}else{
		$('#wrapper').removeAttr('style');
	};

}

function stickyUpload() {

	var $cache = $('.upload-photo');

	//store the initial position of the element
	var vTop = $cache.offset().top - parseFloat($cache.css('top').replace(/auto/, 0));

	$(window).scroll(function (event) {

    	// what the y position of the scroll is
    	var y = $(this).scrollTop();
    	// whether that's below the form
    	if (y >= vTop) {
      		// if so, ad the fixed class
      		$cache.addClass('fixed');
      	} else {
      		// otherwise remove it
      		$cache.removeClass('fixed');
      	}
      });

}


function galleryFancyBox(goTO){
	$("a.activate-slider").fancybox({
		width: "100%",
		height: '100%',
		autoSize: false,
		padding: 0,
		margin: 0,
		afterShow: function(){

			$('.product-slider').on({
				beforeChange: function (event, slick, current_slide_index, next_slide_index) {
					$('.product-slider-nav .slick-slide').removeClass('slick-active-1');
					$('.product-slider-nav .slick-slide[data-slick-index=' + next_slide_index + ']').addClass('slick-active-1');
				}
			}).slick({
				initialSlide:goTO,
				infinite: true,
				accessibility: true,
				slidesToShow: 1,
				slidesToScroll: 1,
				autoplay: true,
				pauseOnHover: false,
				speed: 300,
				fade: true,
				asNavFor: '.product-slider-nav',
				arrows: true,
				responsive: [
				{
					breakpoint: 778,
					settings: {
						autoplay: false,
					}
				},
				]
			});

			$('.product-slider-nav').on({
				init: function() {
					$('.product-slider-nav .slick-slide').removeClass('slick-active-1');
					$('.product-slider-nav .slick-slide[data-slick-index=0]').addClass('slick-active-1');
				}

			}).slick({
				initialSlide:goTO,
				infinite: true,
				slidesToShow: 6,
				slidesToScroll: 6,
				autoplay: false,
				dots: false,
				asNavFor: '.product-slider',
				focusOnSelect: true,
				responsive: [
				{
					breakpoint: 1600,
					settings: {
						slidesToShow: 5,
						slidesToScroll: 5,
						infinite: true
					}
				},
				{
					breakpoint: 1300,
					settings: {
						slidesToShow: 4,
						slidesToScroll: 4,
						infinite: true
					}
				},
				{
					breakpoint: 1240,
					settings: {
						slidesToShow: 3,
						slidesToScroll: 3,
						infinite: true
					}
				},
				{
					breakpoint:600,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 2,
						infinite: true,
					}
				},
				]
			});

		},
		afterClose: function(){
			$('.product-slider, .product-slider-nav').slick('unslick');
		}
	});
}

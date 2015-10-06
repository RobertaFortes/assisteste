/**
 * EMthemes
 *
 * @license commercial software
 * @copyright (c) 2014 Codespot Software JSC - EMthemes.com. (http://www.emthemes.com)
 */
var timeout = null;
var touch = false;
var animate = false;
(function($) {
	if (typeof EM == 'undefined') EM = {};
	if (typeof EM.tools == 'undefined') EM.tools = {};

	var domLoaded = false;
	var windowLoaded = false;

	/**
	 *   Add class mobile
	 **/

	function addClassMobile() {
		if (isMobile) {
			$('body').addClass('mobile-view');
		}
	};

	/**
	 * Fix iPhone/iPod auto zoom-in when text fields, select boxes are focus
	 */

	function fixIPhoneAutoZoomWhenFocus() {
		var viewport = $('head meta[name=viewport]');
		if (viewport.length == 0) {
			$('head').append('<meta name="viewport" content="width=device-width, initial-scale=1.0"/>');
			viewport = $('head meta[name=viewport]');
		}

		var old_content = viewport.attr('content');

		function zoomDisable() {
			viewport.attr('content', old_content + ', user-scalable=0');
		}

		function zoomEnable() {
			viewport.attr('content', old_content);
		}

		$("input[type=text], textarea, select").mouseover(zoomDisable).mousedown(zoomEnable);
	};

	/* hover language currency */

	function hoverUl() {
		if (!isMobile) {
			$('.cat-search').each(function(){
				$(this).insertUlCategorySearch();
				$(this).selectUlCategorySearch();
			});
			$('#select-language').each(function() {
				$(this).insertUlLanguage();
				$(this).selectUl();
			});
			$('.currency').each(function() {
				$(this).insertUlCurrency();
				$(this).selectUl();
			});
			$('#select-store').each(function() {
				$(this).insertUl();
				$(this).selectUl();
			});
		}
	};

	function persistentMenu() {
		if (FREEZED_TOP_MENU != 0 && !isPhone) {
			var sticky_navigation = function() {
				var scroll_top = $(window).scrollTop();
				//var navpos = $('#navpos').offset().top;
				if ($(window).width() > 767) {
					if (scroll_top > 0) {
						$('.top-menu').addClass('navbar-fixed-top');
					} else {
						$('.top-menu').removeClass('navbar-fixed-top');
					}
				} else {
					$('.top-menu').removeClass('navbar-fixed-top');
				}
			};
			$(window).scroll(function() {
				sticky_navigation();
			});
		}
		if ($(window).width() <= 767) {
			$('.top-menu').removeClass('navbar-fixed-top');
		}
	};

	// Back to top

	function backToTop() {
		// hide #back-top first
		$("#back-top").hide();

		// fade in #back-top
		if (!isMobile) {
			$(window).scroll(function() {
				if ($(this).scrollTop() > 100) {
					$('#back-top').fadeIn();
				} else {
					$('#back-top').fadeOut();
				}
			});

			// scroll body to 0px on click
			$('#back-top a').click(function() {
				$('body,html').animate({
					scrollTop: 0
				}, 800);
				return false;
			});
		}
	};

	/* Top Cart Hover */

	function hoverTopCart() {
		$('.dropdown-cart').each(function() {
			if (isMobile) {
				$('.dropdown-cart').find('.amount').attr('href', 'javascript:void(0);');
				$(this).unbind('click');
				var divWrapper = $(this);
				$(this).find('.icon.cart,.amount').click(function(e) {
					e.preventDefault();
					divWrapper.find('.cart-popup').slideToggle(function() {
						$(this).css("overflow", "inherit")
					});
				});
			} else {
				var tm;

				function show(el) {
					clearTimeout(tm);
					tm = setTimeout(function() {
						el.slideDown(function() {
							$(this).css("overflow", "inherit")
						});
					}, 200);
				}

				function hide(el) {
					clearTimeout(tm);
					tm = setTimeout(function() {
						el.slideUp(function() {
							$(this).css("overflow", "inherit")
						});
					}, 200);
				}
				$(this).bind('mouseenter', show.curry($('.cart-popup', this))).bind('mouseleave', hide.curry($('.cart-popup', this))).find('.cart-popup').slideUp();
			}
		});
	};

	/**
	 Tab On Details Page
	 */
    function decorateProductCollateralTabs() {
        var sTab = $('.em-details-tabs');
        if (sTab.length) {
            sTab.each(function(i) {
                $(this).prepend("<ul class='tabs-control'></ul>");
                var controlTab = $('.tabs-control', this);
                $('.box-collateral', $(this)).each(function(j) {
                    id = 'em-details-tab-' + i + '-' + j;
                    $(this).attr('id', id);
                    controlTab.append('<li><a href="#' + id + '">' + $('h2', this).html() + '</a></li>');
                });
                $(this).responsiveTabs({
                    animation: 'slide'
                });
            });
        }
    };

	/**
	 *   showReviewTab
	 **/

	function showReviewList() {
		if (jQuery('#customer-reviews').size()) {
			// scroll to customer review
			jQuery('html, body').animate({
				scrollTop: jQuery('#customer-reviews').offset().top
			}, 500);
		} else {
			return false;
		}
		return true;
	};

	function showReviewForm() {
		if (jQuery('#review-form').size()) {
			// scroll to customer review
			jQuery('html, body').animate({
				scrollTop: jQuery('#review-form').offset().top
			}, 500);
		} else {
			return false;
		}
		return true;
	};

	function setupReviewLink() {
		jQuery('.product-view .product-essential .link_review_list').click(function(e) {
			if (showReviewList()) {
				e.preventDefault();
			}
		});
		jQuery('.product-view .product-essential .link_review_form').click(function(e) {
			if (showReviewForm()) {
				e.preventDefault();
			}
		});
	};

	

	function toogleStore() {
		if (!isMobile) {
			var owl = $("#slider_storeview > ul"); 
			if(owl.length){ 
				owl.owlCarousel({
					//Basic Speeds
					slideSpeed: 800,
					rewindSpeed: 800,

					//Autoplay
					autoPlay: false,
					lazyLoad: false,
					stopOnHover: false,
					mouseDrag: true,
					touchDrag: true,

					// Navigation
					navigation: true,
					navigationText: ["Previous", "Next"],
					pagination: false,
					paginationNumbers: false,

					// Responsive 
					responsive: true,
					items: 3,
					/*items above 1200px browser width*/
					itemsDesktop: [1199, 3],
					/*//items between 1199px and 981px*/
					itemsDesktopSmall: [991, 3],
					itemsTablet: [767, 3],
					itemsTabletSmall: [599, 3],
					itemsMobile: [320,3],

					// CSS Styles
					baseClass: "owl-carousel",
					theme: "owl-theme",
					addClassActive: true,
					scrollPerPage: false
				});
			}
			$('.storediv').hide();
			$(".btn_storeview").click(function() {
				store_show();
			});
			$(".btn_storeclose").click(function() {
				store_hide();
			});

			function store_show() {
				var bg = $("#bg_fade_color");
				bg.css("opacity", 0.5);
				bg.css("display", "block");
				var top = ($(window).height() - $(".storediv").height()) / 2;
				var left = ($(window).width() - $(".storediv").width()) / 2;
				$(".storediv").show();
				$(".storediv").css('top', top + 'px');
				$(".storediv").css('left', left + 'px');
			};

			function store_hide() {
				var bg = $("#bg_fade_color");
				$(".storediv").hide();
				bg.css("opacity", 0);
				bg.css("display", "none");
			};
		}
	};

	function qrCode() {
		$('#qr_code .qr_code_name a').mouseover(function(e) {
			var $img = $('img', this);
			$img.css({
				'display': 'block'
			}).attr('src', $img.data('originalImg'));

			var parent = $img.offsetParent().offset();
			//var left = e.pageX - parent.left + 15;
			var top = e.pageY - parent.top + 15;

			if ((parent.top + top + $img.height()) > ($(document).scrollTop() + $(window).height())) top = $(document).scrollTop() + $(window).height() - $img.height() - parent.top;

			if ((parent.top + top) < $(document).scrollTop()) top = $(document).scrollTop() - parent.top;

			$img.css({
				'left': 48 + 'px',
				'position': 'absolute',
				'top': 0 + 'px',
				'width': 'auto',
				'height': 'auto'
			}).attr('src', $img.data('originalImg'));
		});

		$('#qr_code .qr_code_name a').mouseout(function(e) {
			var $img = $('img', this);
			$img.css({
				'display': 'none'
			}).attr('src', $img.data('originalImg'));
		});
	};



	/**
	 *   Toogle Footer Information Mobile View
	 **/

	function toogleFooter() {
		var wi = $(window).width();
		if (isPhone || (wi <= 767)) {
			$('.link_content > div > .content_links').css('display', 'none');
			$('.link_content > div > p.h5').removeClass('active');
			$('.link_content > div > p.h5').addClass('toogle-icon');
			$('.link_content > div > p.h5').unbind('click');
			$('.link_content > div > p.h5').on('click', function() {
				$(this).toggleClass("active").parent().find(".content_links").slideToggle();
			});
		} else {
			$('.link_content > div > p.h5').unbind('click');
			$('.link_content > div > p.h5').removeClass('toogle-icon');
			$('.link_content > div > p.h5').removeClass('active');
			$('.link_content > div > .content_links').css('display', 'block');
		}
	};

	function buildBootrapJs() {
		$('.link_connect a.icon').tooltip();

		$('.reset-button').click(function() {
			var btn = $(this);
			btn.button('loading');
			var tm;
			clearTimeout(tm);
			tm = setTimeout(function() {
				btn.button('reset');
			}, 1000);
		});
	};

	function dataAnimate() {
		if ($('[data-animate]')) {
			$('[data-animate]').each(function() {

				var $toAnimateElement = $(this);

				var toAnimateDelay = $(this).attr('data-delay');

				var toAnimateDelayTime = 0;

				if (toAnimateDelay) {
					toAnimateDelayTime = Number(toAnimateDelay);
				} else {
					toAnimateDelayTime = 200;
				}

				if (!$toAnimateElement.hasClass('animated')) {

					/*$toAnimateElement.addClass('not-animated');*/

					var elementAnimation = $toAnimateElement.attr('data-animate');
					setTimeout(function() {
						$toAnimateElement.waypoint({
							handler: function() {
								$toAnimateElement.removeClass('not-animated').addClass(elementAnimation + ' animated');
							},
							offset: '100%',
							triggerOnce: false
						});
					}, toAnimateDelayTime);
				}
				if ($toAnimateElement.hasClass('not-animated')) animate = false;
			});
		}

	};

	function ieVersion() {
		var rv = -1; // Return value assumes failure.
		if (navigator.appName == 'Microsoft Internet Explorer') {
			var ua = navigator.userAgent;
			var re = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
			if (re.exec(ua) != null) rv = parseFloat(RegExp.$1);
		}
		return rv;
	};

	function doAnimate() { /* DETECT PLATFORM */
		$.support.touch = 'ontouchend' in document;

		if ($.support.touch) {
			touch = true;
			$('body').addClass('touch');
		} else {
			$('body').addClass('notouch');
		}
		if (touch == false && ANIMATION_LOADING == 0) {
			if (ieVersion() == -1 || ieVersion() > 9) $('[data-animate]').addClass('not-animated');
		}
	};

	function setAnimate() {
		$('.header-top-left').each(function() {
			if (!$(this).hasClass('clear') && !$(this).hasClass('bkg-hover')) $(this).attr('data-animate', animate_header);
		});
		$('.header-top-right').each(function() {
			if (!$(this).hasClass('clear') && !$(this).hasClass('bkg-hover')) $(this).attr('data-animate', animate_header);
		});
		$('.header-bottom .logo_wrapper .row .col-sm-24').children('div').each(function() {
			$(this).children('div').each(function() {
				if (!$(this).hasClass('clear') && !$(this).hasClass('bkg-hover')) $(this).attr('data-animate', animate_header);
			});
		});
		$('.em_left').children('div').each(function() {
			$(this).children('div').each(function() {
				if (!$(this).hasClass('clear') && !$(this).hasClass('bkg-hover') && !$(this).hasClass('megamenu-wrapper')) $(this).attr('data-animate', animate_left);
			});
		});
		$('.em_right').children('div').each(function() {
			$(this).children('div').each(function() {
				if (!$(this).hasClass('clear') && !$(this).hasClass('bkg-hover') && !$(this).hasClass('megamenu-wrapper')) $(this).attr('data-animate', animate_right);
			});
		});
		$('.em_main').children('div').each(function() {
			$(this).children('div').each(function() {
				if (!$(this).parents().first().hasClass('tab_home') && !$(this).hasClass('clear') && !$(this).hasClass('bkg-hover')) $(this).attr('data-animate', animate_main);
			});
		});
		$('.area3-banner').children('div').each(function() {
			$(this).children('div').each(function() {
				if (!$(this).hasClass('clear') && !$(this).hasClass('bkg-hover')) $(this).attr('data-animate', animate_main);
			});
		});
		$('.area6-links,.footer-container, .area12-payment,.area7-network-newsletter').children('div').each(function() {
			$(this).children('div').each(function() {
				if (!$(this).hasClass('clear') && !$(this).hasClass('bkg-hover')) $(this).attr('data-animate', animate_footer);
			});
		});
	};


	function colorVariation() {
		var screen = "<div id='bg_fade_color'></div>";
		$(document.body).append(screen);
		$("#bg_fade_color").css("opacity", 0);
		$("#bg_fade_color").css("display", "none");

		$(".btn_color_variation").click(function() {
			var bg = $("#bg_fade_color");
			bg.css("opacity", 0.5);
			bg.css("visibility", "visible");
			$(".colordiv").show();

			$(".colordiv").css('top', Math.max($(document).scrollTop(), Math.min($(this).offset().top, $(document).scrollTop() + $(window).height() - $(".colordiv").outerHeight())) + 20 + 'px');

			var bg = $("#bg_fade");
			bg.css("opacity", 0.5);
			bg.css("visibility", "visible");
		});

		$(".btn_color_close").click(function() {
			color_hide();
		});

		function color_hide() {
			var bg = $("#bg_fade");
			$(".colordiv").hide();
			bg.css("opacity", 0);
			bg.css("visibility", "hidden");
		}
	};


	/**
	 Ready Function
	 */
	$(document).ready(function() {
		domLoaded = true;
		setAnimate();
		addClassMobile();
		if (boxwide_selected == "wide") {
			if (fullSlideshow) {
				$('.em-slideshow').addClass('em-full-custom');
			} else {
				$('.em-slideshow').removeClass('em-full-custom');
			};
		} else {
			$('.em-slideshow').removeClass('em-full-custom');
		}

		if (disableVariation != 1) {
			colorVariation();
			qrCode();
			toogleStore();
		}
		// safari hack: remove bold in h5, .h5
		if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
			$('h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6').css('font-weight', 'normal');
		}
		isMobile && fixIPhoneAutoZoomWhenFocus();
		hoverUl();
		toolbarCategoty();
		backToTop();
		hoverTopCart();
		alternativeProductImage();

		setupReviewLink();
		if (useTab) {
			decorateProductCollateralTabs();
		}
		responsive();
		persistentMenu();
		toogleFooter();
		var tm;
		$(window).resize($.throttle( 350,function() {
			if (tm) {
				clearTimeout(tm);
			}
			tm = setTimeout(function() {
				toogleFooter();
				if (typeof em_slider !== 'undefined') em_slider.reinit();
			}, 300);
		}));
		buildBootrapJs();

		if ($('.wrapper').hasClass('em-box-custom')) {
			if ($('body').hasClass('adapt-0')) {
				$('.wrapper').removeClass('em-box-custom');
			}
			$(window).bind('resize', function() {
				if ($('body').hasClass('adapt-0')) {
					$('.wrapper').removeClass('em-box-custom');
				} else {
					$('.wrapper').addClass('em-box-custom');
				}
			});
		}
		doAnimate();
	});
})(jQuery);

	
	
/**
 * Adjust elements to make it responsive
 */

function responsive() {
	var $ = jQuery;
	var position = $('.products-grid .item').css('position');
	if (position != 'absolute' && position != 'fixed' && position != 'relative') $('.products-grid .item').css('position', 'relative');
};

/**
 * Change the alternative product image when hover
 */

function alternativeProductImage() {
	var $ = jQuery;
	function swap() {
		el = $(this).find('img[data-alt-src]');
		var newImg = $(el).data('alt-src');
		var oldImg = $(el).attr('src');
		$(el).attr('src', newImg).data('alt-src', oldImg);
	}

	$('.item .product-image img[data-alt-src]').parents('.item').bind('mouseenter', swap).bind('mouseleave', swap);
	
};

function toolbarCategoty() {
	var $ = jQuery;
	if (!isMobile) {
		$('.toolbar-show').each(function() {
			$(this).insertUl();
			$(this).selectUl();
		});
		$('.sortby').each(function() {
			$(this).insertUl();
			$(this).selectUl();
		});
	}
};

/**
 *   After Layer Update
 **/
window.afterLayerUpdate = function() {
	var $ = jQuery;
	toolbarCategoty();
	alternativeProductImage();
	if (typeof EM_QUICKSHOP_DISABLED == 'undefined' || !EM_QUICKSHOP_DISABLED) {
		qs({
			itemClass: '.products-grid li.item, .products-list li.item, li.item .cate_product, .product-upsell-slideshow li.item, .mini-products-list li.item, #crosssell-products-list li.item',
			//selector for each items in catalog product list,use to insert quickshop image
			aClass: 'a.product-image',
			//selector for each a tag in product items,give us href for one product
			imgClass: '.product-image img' //class for quickshop href
		});
	}
};

function afterLoadAjax(id) {
	responsive();
	alternativeProductImage();
	if (typeof EM_QUICKSHOP_DISABLED == 'undefined' || !EM_QUICKSHOP_DISABLED) {
		qs({
			itemClass: '.products-grid li.item, .products-list li.item, li.item .cate_product, .product-upsell-slideshow li.item, .mini-products-list li.item, #crosssell-products-list li.item',
			//selector for each items in catalog product list,use to insert quickshop image
			aClass: 'a.product-image',
			//selector for each a tag in product items,give us href for one product
			imgClass: '.product-image img' //class for quickshop href
		});
	}
};

function hideAgreementPopup(e) {
	jQuery('#checkout-agreements .agreement-content').hide();
};

function showAgreementPopup(e) {
	jQuery('#checkout-agreements label.a-click').parent().parent().children('.agreement-content').show().css({
		'left': (parseInt(document.viewport.getWidth()) - jQuery('#checkout-agreements label.a-click').parent().parent().children('.agreement-content').width()) / 2 + 'px',
		'top': (parseInt(document.viewport.getHeight()) - jQuery('#checkout-agreements label.a-click').parent().parent().children('.agreement-content').height()) / 2 + 'px'
	});
};
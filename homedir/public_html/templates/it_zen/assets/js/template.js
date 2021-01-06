/***********************************************************************************************/
/* Main IceTheme template Scripts */
/***********************************************************************************************/

/* Accordion menu fix. Keep Selected Parent Open on new Page */
jQuery(document).ready(function() {
	var FindClass = jQuery('#responsivebar-inner ul.menu').find('li.active');
	jQuery(FindClass).parents('#responsivebar-inner ul.menu > li.parent').addClass('active');
	jQuery('#responsivebar-inner ul.menu li.active ul').slideDown('normal');
});

/*  Accordion menu for responsivebar nav - IceTheme  */
( function( $ ) {
	
	$( document ).ready(function() {
		$('#responsivebar-inner ul.menu > li > a').click(function() {
		 
		  $('#responsivebar-inner ul.menu li').removeClass('active');
		  $(this).closest('li').addClass('active');	
		  var checkElement = $(this).next();
		 
		  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
			$(this).closest('li').removeClass('active');
			$('#responsivebar-inner ul.menu li ul').slideUp('normal');
		  }
		 
		  if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
			$('#responsivebar-inner ul.menu li ul').slideUp('normal');
			$('#responsivebar-inner ul.menu li.active ul').slideDown('normal');
		  }
		 
		  if($(this).closest('li').find('ul').children().length == 0) {
			return true;
		  } else {
			return false;	
		  }	
		  	
		});
		
	});
	
} )( jQuery );

/* default joomla script */
(function($)
{
	$(document).ready(function()
	{
		$('*[rel=tooltip]').tooltip()

		// Turn radios into btn-group
		$('.radio.btn-group label').addClass('btn');
		$(".btn-group label:not(.active)").click(function()
		{
			var label = $(this);
			var input = $('#' + label.attr('for'));

			if (!input.prop('checked')) {
				label.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');
				if (input.val() == '') {
					label.addClass('active btn-primary');
				} else if (input.val() == 0) {
					label.addClass('active btn-danger');
				} else {
					label.addClass('active btn-success');
				}
				input.prop('checked', true);
			}
		});
		$(".btn-group input[checked=checked]").each(function()
		{
			if ($(this).val() == '') {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn-primary');
			} else if ($(this).val() == 0) {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn-danger');
			} else {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn-success');
			}
		});
	})
})(jQuery);


/* Responsive tables (pure JS */
jQuery(window).on('load', function() {
	// Check all tables. You may need to be more restrictive.
	jQuery('table').each(function() {
		var element = jQuery(this);
		// Create the wrapper element
		var scrollWrapper = jQuery('<div />', {
			'class': 'scrollable',
			'html': '<div />' // The inner div is needed for styling
		}).insertBefore(element);
		// Store a reference to the wrapper element
		element.data('scrollWrapper', scrollWrapper);
		// Move the scrollable element inside the wrapper element
		element.appendTo(scrollWrapper.find('div'));
		// Check if the element is wider than its parent and thus needs to be scrollable
		if (element.outerWidth() > element.parent().outerWidth()) {
			element.data('scrollWrapper').addClass('has-scroll');
		}
		// When the viewport size is changed, check again if the element needs to be scrollable
		jQuery(window).on('resize orientationchange', function() {
			if (element.outerWidth() > element.parent().outerWidth()) {
				element.data('scrollWrapper').addClass('has-scroll');
			} else {
				element.data('scrollWrapper').removeClass('has-scroll');
			}
		});
	});
});


/* jQuery scripts for IceTheme template */
jQuery(document).ready(function() { 
	
	/* detect if broser is ipad */
	var iPad_detect = navigator.userAgent.match(/iPad/i) != null;
	if (iPad_detect) {
		jQuery("body").addClass("ipad");  
	}
	
	/*  Go to Top Link 
	-------------------*/
	 jQuery(window).scroll(function(){
		if ( jQuery(this).scrollTop() > 1200) {
			 jQuery("#gotop").addClass("gotop_active");
		} else {
			 jQuery("#gotop").removeClass("gotop_active");
		}
	}); 
	jQuery(".scrollup").click(function(){
		jQuery("html, body").animate({ scrollTop: 0 }, 600);
		return false;
	});

	
	/* initialize bootstrap tooltips */
	jQuery("[rel='tooltip']").tooltip();
	
	/* initialize bootstrap popopver */
	jQuery("[rel='popover']").popover();
	
	
	/* Languages Module 
	-------------------*/
	/* language module hover effect for flags */
	jQuery(".mod-languages").hover(function () {
		jQuery(".mod-languages li").css({opacity : .25});
	  }, 
	  function () {
		jQuery(".mod-languages li").css({ opacity : 1});
	  }
	);	
	/* language module remove title attribute from img*/
	jQuery("div.mod-languages img").data("title", jQuery(this).attr("title")).removeAttr("title");
	
	
	/* small plugin to do an event outside the element */
	(function($){
    $.fn.outside = function(ename, cb){
        return this.each(function(){
            var $this = $(this),
                self = this;

            $(document).bind(ename, function tempo(e){
                if(e.target !== self && !$.contains(self, e.target)){
                    cb.apply(self, [e]);
                    if(!self.parentNode) $(document.body).unbind(ename, tempo);
                }
            });
        });
    };
	}(jQuery));

		
	/* Search 
	---------*/
	/* adjust the search module */
    jQuery("#search form .inputbox").attr('maxlength','35');
	jQuery("#search form .btn").addClass("icebtn"); 
	
	/* jQuery('#search_toogle').click(function () {
		jQuery('#search').addClass('search_expanded');
		jQuery('#search_toogle').addClass('search_toogle_closed');
		jQuery("#mainmenu").addClass('mainmenu_closed');
	});
	
	jQuery('#mainmenu').outside('click', function() {
		jQuery('#search').removeClass('search_expanded');
		jQuery('#search_toogle').removeClass('search_toogle_closed');
		jQuery("#mainmenu").removeClass('mainmenu_closed');
	}); */


	/* Joomla Rating 
	-----------------*/
	/* fix some classes on the content voting */
	jQuery("span.content_vote .btn").removeClass("btn-mini");
	
	
	/* BreadCrumbs  
	--------------*/
	/* fix breadcrumbs */
	jQuery("ul.breadcrumb .divider.icon-location").removeClass("divider icon-location");
	
	
	/* Joomla Articles  
	------------------*/
	/* remove links from article titles */
	jQuery(".item-page .page-header h2 a").contents().unwrap();
	

	/* Joomla Newsflash module 
	-------------------------*/ 
	/* only in carousel mode remove introtext but leave the img */
	jQuery('.newsflash-carousel-item p:first-child').contents().filter(function(){ 
		return this.nodeType === 3; 
	}).remove();
	

	/* Main menu 
	---------------*/
	jQuery('.dropdown-toggle').click(function () {
		jQuery('#slideshow').toggleClass('mainmenu-hover');
	});
	
	jQuery('.dropdown-toggle').outside('click', function() {
		jQuery('#slideshow').removeClass('mainmenu-hover');
	});
	

	/* Slideshow module (CK slideshow) 
	----------------------------------*/
	if (jQuery("#slideshow").length > 0){
		jQuery(document).scroll(function () {
			var scroll = jQuery(this).scrollTop();
			var topDist = jQuery("#content").position();
			if (scroll > topDist.top-topDist.top/2.5) {
				jQuery('a.iceslide_link').addClass('slide_visible');
				jQuery('ul#jj_sl_navigation').addClass('slide_visible');
			} else {
				jQuery('a.iceslide_link').removeClass('slide_visible');
				jQuery('ul#jj_sl_navigation').removeClass('slide_visible');
			}
		});
	}
	else{
		jQuery('a.iceslide_link').addClass('slide_visible');
		jQuery('ul#jj_sl_navigation').addClass('slide_visible');
	}
	
	

});  

/* Slideshow module (CK slideshow) 
----------------------------------*/
jQuery(window).load(function() {
	function pagWidth() {
		var _pagwidth = jQuery('#slideshow').width() - jQuery('.container').width();
		jQuery('.camera_pag').css("right", _pagwidth/2);
	}
	// Execute on load
	pagWidth();
	// Bind event listener
	jQuery(window).resize(pagWidth);
});

// detect if screen is with touch or not (pure JS)
if (("ontouchstart" in document.documentElement)) {
	document.documentElement.className += "with-touch";
}else {
	document.documentElement.className += "no-touch";
}
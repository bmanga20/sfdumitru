<?php
//  @copyright	Copyright (C) 2008 - 2014 IceTheme. All Rights Reserved
//  @license	Copyrighted Commercial Software 
//  @author     IceTheme (icetheme.com)


// No direct access.
defined('_JEXEC') or die;
?>
 
<script type="text/javascript">
jQuery.fn.styleSwitcher = function(){
	jQuery(this).click(function(){
		loadStyleSheet(this);
		return false;
	});
	function loadStyleSheet(obj) {
		jQuery('body').append('<div id="overlay"><ul class="ice_css3_loading"><li></li><li></li><li></li><li></li></ul></div>');
		jQuery('body').css({height:'100%'});
		jQuery('#overlay')
			.fadeIn(500,function(){
				/* change the default style */
				jQuery.get( obj.href+'&js',function(data){
					jQuery('#stylesheet').attr('href','<?php echo IT_THEME; ?>/assets/less/styles/' + data + '.css');
					
					cssDummy.check(function(){
						jQuery('#overlay').fadeOut(1000,function(){
							jQuery(this).remove();
						});	
					});
				});
				
				
				/* change some parts only for demo
				jQuery.get( obj.href+'&js',function(data){
					jQuery('#promo .moduletable:nth-child(1) img').attr('src','<?php echo IT_THEME; ?>/images/styles/' + data + '/promo1.png');
				}); 
				
				jQuery.get( obj.href+'&js',function(data){
					jQuery('#promo .moduletable:nth-child(2) img').attr('src','<?php echo IT_THEME; ?>/images/styles/' + data + '/promo2.png');
				}); 
				
				jQuery.get( obj.href+'&js',function(data){
					jQuery('#promo .moduletable:nth-child(3) img').attr('src','<?php echo IT_THEME; ?>/images/styles/' + data + '/promo3.png');
				}); */
				
			});
	}

	var cssDummy = {
		init: function(){
			jQuery('<div id="dummy-element" style="display:none" />').appendTo('body');
		},
		check: function(callback) {
			if (jQuery('#dummy-element').width()==2) callback();
			else setTimeout(function(){cssDummy.check(callback)}, 200);
		}
	}
	cssDummy.init();
}
	jQuery('#ice-switcher a').styleSwitcher(); 	
	jQuery('a.style-switcher-link').styleSwitcher(); 
	
	
		/* Control the active class to styleswitcher */
		jQuery(function() {
		jQuery('#ice-switcher a').click(function(e) {
			e.preventDefault();
			var $icethis = jQuery(this);
			$icethis.closest('ul').find('.active').removeClass('active');
			$icethis.parent().addClass('active');
		});
		
		jQuery(document).ready(function(){
			jQuery('#ice-switcher li.<?php echo $templatestyle ?>').addClass('active');
		});
		
	});
	
</script>

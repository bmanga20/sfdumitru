<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

$moduleid = $module->id;
?>

<style type="text/css">
.newsflash-carousel .slides > div {
	float:left;
	width:361px;
}
  
</style>


<div id="newsflash-carousel-<?php echo $moduleid; ?>" class="newsflash-carousel newsflash-carousel-<?php echo $params->get('moduleclass_sfx'); ?>">
	
    <div class="slides">
    
		<?php for ($i = 0, $n = count($list); $i < $n; $i ++) :
        $item = $list[$i]; ?>
        <div>
            <div class="newsflash-carousel-item">
            <?php require JModuleHelper::getLayoutPath('mod_articles_news', '_item_carousel');
            if ($n > 1 && (($i < $n - 1) || $params->get('showLastSeparator'))) : ?>
            <?php endif; ?>
            </div>
		</div>
        <?php endfor; ?>
        
    </div>

</div>


<script type="text/javascript">
// Can also be used with $(document).ready()
(function($) {
	$(window).load(function(){
		$('#newsflash-carousel-<?php echo $moduleid; ?>').flexslider({
		selector: ".slides > div", 
		animation: "slide",
		direction: "horizontal",
		itemWidth:361,
		slideshowSpeed:7000, 
		animationspeed:6000,  
		itemMargin:0,
		minItems:1,
		maxItems:0, 
		move: 0,    
		slideshow: false,
		directionNav: false,
		controlNav: true,
      });
    });
   })(jQuery);

</script>
<?php
//  @copyright	Copyright (C) 2008 - 2014 IceTheme. All Rights Reserved
//  @license	Copyrighted Commercial Software 
//  @author     IceTheme (icetheme.com)

// No direct access.
defined('_JEXEC') or die;
?>


<a href="#innerlide" rel="tooltip" data-placement="<?php if ($this->direction == 'rtl') {echo "left";}else{echo "right";} ?>" data-original-title="<?php echo JText::_('TPL_SLIDE_OPEN'); ?>" class="iceslide_link" title="<?php echo JText::_('TPL_SLIDE_OPEN'); ?>"><span></span></a>

    <div id="innerlide" style="display:none">
    	<jdoc:include type="modules" name="slide" />
    	<p><a class="close" title="<?php echo JText::_('TPL_SLIDE_CLOSE'); ?>" href="javascript:jQuery.pageslide.close()">×</a></p>
    </div>

	
	 <script>
		jQuery(document).ready(function() {
			jQuery(".iceslide_link").pageslide({
				  direction: "<?php if ($this->direction == 'rtl') {echo "left";}else{echo "right";} ?>"
			});
		});
	</script>
    

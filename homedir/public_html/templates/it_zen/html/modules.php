<?php
// © IceTheme 2008 - 2014
// GNU General Public License

defined('_JEXEC') or die;

/**
 * This is a file to add template specific chrome to module rendering.  To use it you would
 * set the style attribute for the given module(s) include in your template to use the style
 * for each given modChrome function.
 *
 * eg.  To render a module mod_test in the submenu style, you would use the following include:
 * <jdoc:include type="module" name="test" style="submenu" />
 *
 * This gives template designers ultimate control over how modules are rendered.
 *
 * NOTICE: All chrome wrapping methods should be named: modChrome_{STYLE} and take the same
 * two arguments.
 */



// "Sidebar" module position
function modChrome_sidebar($module, &$params, &$attribs)
{
	 if(strpos($module->title,"|") !== false){
		$titleArray = explode("|",$module->title);		
		$module->title = "";
		for($i=0;$i<count($titleArray);$i++){
				$module->title .= $titleArray[$i];
		}		
	} 
	
	$headerLevel = $params->get('header_tag');
	
	if ($module->content) { ?>
		
		<div class="sidebar_module sidebar_module_<?php echo htmlspecialchars($params->get('moduleclass_sfx')) ?>">
        	
			     <?php if ($module->showtitle) {
                  
                  echo "<".$headerLevel." class=\"sidebar_module_heading\"><span>" . $module->title . "</span></".$headerLevel.">";
                    
               } ?>
			
                <div class="sidebar_module_content"><?php echo $module->content ?></div>
		
          </div>
          
         
	
    <?php  } } 
	

// Footer Modules
function modChrome_footer($module, &$params, &$attribs)
{ 

	static $modulecount;
	static $modules;
		
	global $it_mod_footer; //Number of Modules
	
	$animate_no=0; //Module Counter
	
	if ($it_mod_footer == 1) {
		$footer_width = "span12";
			
	} elseif ($it_mod_footer == 2) {
		$footer_width = "span6";
		
	} elseif ($it_mod_footer == 3) {
		$footer_width = "span4";
		
	} elseif ($it_mod_footer == 4) {
		$footer_width = "span3";
		
	} elseif ($it_mod_footer == 6) {	
		$footer_width = "span2";	
		
	} else {
		$footer_width = "span";	
	}
	
	
	if ($modulecount < 1)
	{
		$modulecount = count(JModuleHelper::getModules($module->position));
		$modules = array();
	}
	
	if ($modulecount == 1)
	{
		//Create a Class Temp to Store all Values
		$temp = new stdClass;
		$temp->content = $module->content;
		$temp->title = $module->title;
		$temp->htag = $params->get('header_tag');
		$temp->csuffix = $params->get('moduleclass_sfx');
		$temp->showtitle = $module->showtitle;
		$temp->params = $module->params;
		$temp->id = $module->id;
		//Store all class elements in Array
		$modules[] = $temp;
		// list of moduletitles
		// list of moduletitles
	
		foreach ($modules as $rendermodule)
		{
			$animate_no++;
			
			if(strpos($rendermodule->title,"|") !== false){
				$titleArray = explode("|",$rendermodule->title);		
				$rendermodule->title = "";
				for($i=0;$i<count($titleArray);$i++){
					$rendermodule->title .= $titleArray[$i];
				}		
			}
			
			$headerLevel = $rendermodule->htag; //Store Header TAG
			$moduleSuffix = $rendermodule->csuffix; //Store Module Class Suffix

			echo '<div class="moduletable ' .$footer_width. ' animated fadeIn'.$moduleSuffix.'" data-id="'.$animate_no.'">';
				if ($rendermodule->showtitle) {
					echo '<'.$headerLevel.' class="moduletable_heading">' . $rendermodule->title . '</'.$headerLevel.'>';
				 } ?>
				<div class="moduletable_content clearfix">
					<?php echo $rendermodule->content; ?>
                </div>   
			</div>
            
		<?php }
	}
	else {
		//Duplicate Above content
		$temp = new stdClass;
		$temp->content = $module->content;
		$temp->params = $module->params;
		$temp->title = $module->title;
		$temp->htag = $params->get('header_tag');
		$temp->csuffix = $params->get('moduleclass_sfx');
		$temp->showtitle = $module->showtitle;
		$temp->id = $module->id;
		$modules[] = $temp;
		$modulecount--;
	}
		   
}

// Promo Modules
function modChrome_promo($module, &$params, &$attribs)
{ 

	static $modulecount;
	static $modules;
		
	global $it_mod_promo; //Number of Modules
	
	$animate_no=0; //Module Counter
	
	if ($it_mod_promo == 1) {
		$promo_width = "span12";
			
	} elseif ($it_mod_promo == 2) {
		$promo_width = "span6";
		
	} elseif ($it_mod_promo == 3) {
		$promo_width = "span4";
		
	} elseif ($it_mod_promo == 4) {
		$promo_width = "span3";
		
	} elseif ($it_mod_promo == 6) {	
		$promo_width = "span2";	
		
	} else {
		$promo_width = "span";	
	}
	
	
	if ($modulecount < 1)
	{
		$modulecount = count(JModuleHelper::getModules($module->position));
		$modules = array();
	}
	
	if ($modulecount == 1)
	{
		//Create a Class Temp to Store all Values
		$temp = new stdClass;
		$temp->content = $module->content;
		$temp->title = $module->title;
		$temp->htag = $params->get('header_tag');
		$temp->csuffix = $params->get('moduleclass_sfx');
		$temp->showtitle = $module->showtitle;
		$temp->params = $module->params;
		$temp->id = $module->id;
		//Store all class elements in Array
		$modules[] = $temp;
		// list of moduletitles
		// list of moduletitles
	
		foreach ($modules as $rendermodule)
		{
			$animate_no++;
			
			if(strpos($rendermodule->title,"|") !== false){
				$titleArray = explode("|",$rendermodule->title);		
				$rendermodule->title = "";
				for($i=0;$i<count($titleArray);$i++){
					$rendermodule->title .= $titleArray[$i];
				}		
			}
			
			$headerLevel = $rendermodule->htag; //Store Header TAG
			$moduleSuffix = $rendermodule->csuffix; //Store Module Class Suffix

			echo '<div class="moduletable ' .$promo_width. ' animated fadeIn'.$moduleSuffix.'" data-id="'.$animate_no.'">';
				if ($rendermodule->showtitle) {
					echo '<'.$headerLevel.' class="moduletable_heading">' . $rendermodule->title . '</'.$headerLevel.'>';
				 } ?>
				<div class="moduletable_content clearfix">
					<?php echo $rendermodule->content; ?>
                </div>   
			</div>
            
		<?php }
	}
	else {
		//Duplicate Above content
		$temp = new stdClass;
		$temp->content = $module->content;
		$temp->params = $module->params;
		$temp->title = $module->title;
		$temp->htag = $params->get('header_tag');
		$temp->csuffix = $params->get('moduleclass_sfx');
		$temp->showtitle = $module->showtitle;
		$temp->id = $module->id;
		$modules[] = $temp;
		$modulecount--;
	}
		   
}

/*
"Social Area (tabs)" position */

function modChrome_social_tabs($module, &$params, &$attribs)
{ 
	
	static $modulecount;
	static $modules;
	
	$datapane_li=0;
	$datapane_id=0;
	
	if ($modulecount < 1)
	{
		$modulecount = count(JModuleHelper::getModules($module->position));
		$modules = array();
	}

	if ($modulecount == 1)
	{
		$temp = new stdClass;
		$temp->content = $module->content;
		$temp->title = $module->title;
		$temp->params = $module->params;
		$temp->id = $module->id;
		$modules[] = $temp;
		// list of moduletitles
		// list of moduletitles
		

		echo '<div class=""><ul class="nav nav-tabs responsive" id="social_tabs_mod">';

		foreach ($modules as $rendermodule)
		{
			$datapane_li++;
			
			$activeclass = "";
            if($datapane_li == 1){
                $activeclass = "active";
            }
			
			echo '<li class="'.$activeclass.'"><a href="#pane'. $datapane_li .'" data-toggle="tab">'.$rendermodule->title.'</a></li>';
		}
		echo '</ul>';
		
		echo '<div class="tab-content responsive">';
		// modulecontent
		foreach ($modules as $rendermodule)
		{
			$datapane_id++;
			
			$activeclass = "";
            if($datapane_id == 1){
                $activeclass = "active";
            }
			
			echo '<div id="pane'. $datapane_id .'" class="tab-pane '.$activeclass.'">'. $rendermodule->content.'</div>';
		}
		
		echo '</div>';
		
		echo '</div>';
		
		echo '<script>
		 	  
		  (function($) {
			  fakewaffle.responsiveTabs([\'phone\', \'tablet\']);
		  })(jQuery);


		</script>';
		
	} else {
		$temp = new stdClass;
		$temp->content = $module->content;
		$temp->params = $module->params;
		$temp->title = $module->title;
		$temp->id = $module->id;
		$modules[] = $temp;
		$modulecount--;
	}
           
 } 


// Showcase Modules
 
function modChrome_showcase($module, &$params, &$attribs)
{
	global $it_mod_showcase;
	
	if ($it_mod_showcase == 1) {
		$showcase_width = "span12";
			
	} elseif ($it_mod_showcase == 2) {
		$showcase_width = "span6";
		
	} elseif ($it_mod_showcase == 3) {
		$showcase_width = "span4";
		
	} elseif ($it_mod_showcase == 4) {
		$showcase_width = "span3";
		
	} elseif ($it_mod_showcase == 6) {	
		$showcase_width = "span2";	
		
	} else {
		$showcase_width = "span";	
	}
	
	if(strpos($module->title,"|") !== false){
		$titleArray = explode("|",$module->title);		
		$module->title = "";
		for($i=0;$i<count($titleArray);$i++){
			$module->title .= $titleArray[$i];
		}		
	}
	
	$headerLevel = $params->get('header_tag');
	
	if (!empty ($module->content)) : ?>
         
       <div class="moduletable <?php echo $showcase_width; ?>">
        
			<?php if ($module->showtitle) {
				 echo '<'.$headerLevel.' class="moduletable_heading">' . $module->title . '</'.$headerLevel.'>';
			} ?>
        	
             <div class="moduletable_content clearfix">
			 <?php echo $module->content; ?>
             </div>
                
		</div>
	<?php endif;
}





// Banner Modules
function modChrome_banner($module, &$params, &$attribs)
{
	global $it_mod_banner;
	
	if ($it_mod_banner == 1) {
		$banner_width = "span12";
			
	} elseif ($it_mod_banner == 2) {
		$banner_width = "span6";
		
	} elseif ($it_mod_banner == 3) {
		$banner_width = "span4";
		
	} elseif ($it_mod_banner == 4) {
		$banner_width = "span3";
		
	} elseif ($it_mod_banner == 6) {	
		$banner_width = "span2";	
		
	} else {
		$banner_width = "span";	
	}
	
	$headerLevel = $params->get('header_tag');
	
	if (!empty ($module->content)) : ?>
         
       <div class="moduletable <?php echo $banner_width; ?>">
        
			<?php if ($module->showtitle) {
				 echo '<'.$headerLevel.' class="moduletable_heading">' . $module->title . '</'.$headerLevel.'>';
			} ?>
        	
             <div class="moduletable_content clearfix">
			 <?php echo $module->content; ?>
             </div>
                
		</div>
	<?php endif;
}
 
?>
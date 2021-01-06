<?php
//  @copyright	Copyright (C) 2008 - 2014 IceTheme. All Rights Reserved
//  @license	Copyrighted Commercial Software 
//  @author     IceTheme (icetheme.com)

// No direct access.
defined('_JEXEC') or die;

// Detecting Joomla Active Variables;
$document = JFactory::getDocument();
$app = JFactory::getApplication();
$sitename = $app->getCfg('sitename');
$lang = JFactory::getLanguage();
$user = JFactory::getUser();
jimport( 'joomla.application.module.helper' );

// Current item id
$itemid = JRequest::getVar('Itemid');
$menu = $app->getMenu();
$menu_active = $app->getMenu()->getActive();
$pageclass = '';
if (is_object($menu_active))
$pageclass = $menu_active->params->get('pageclass_sfx');

// Define Constants 
define('IT_THEME', $this->baseurl .'/templates/'. $this->template);
define('IT_THEME_DIR', JPATH_ROOT .'/templates/'. $this->template);

// Include Variables
include_once(IT_THEME_DIR.'/php/variables.php'); 

// Load Bootstrap Frameworks (loads JQuery framework as well)
if ($it_params_advanced_bootstrap) {
	JHtml::_('bootstrap.framework');
}
	
// Load the main template JavaScript File
$document->addScript(IT_THEME .'/assets/js/template.js');

// Load background parallax effect script
if (($it_mod_showcase || $it_mod_tagline) != 0) {
	$document->addScript(IT_THEME .'/assets/js/jquery.stellar.min.js');
}

// Load Owl carousel JS
if ($it_mod_testimonial > 1 ) { 
	$document->addScript(IT_THEME . '/assets/js/owl.carousel.min.js');
}


// Template Styles 
if(!empty($_COOKIE['templatestyle'])) 
$templatestyle = $_COOKIE['templatestyle'];
else $templatestyle =  $this->params->get('TemplateStyle');

// Hide Frontpage view on homepage (has paramter as well)
if ($menu->getActive() == $menu->getDefault($lang->getTag())) {
	$it_hide_frontpage_menu = 1;
}
else {
	$it_hide_frontpage_menu = 0;
}

if ($it_params_hide_frontpage == 1 && $it_hide_frontpage_menu == 1 ) {
	$it_hide_frontpage = 1;	
}

	
// get the login module title
if ($it_mod_search != 0 ) {
	
	$get_search_module = JModuleHelper::getModules('search');
	$modal_search_title = $get_search_module[0]->title;
}

// offline page direct link
if (JRequest::getCmd("tmpl", "index") == "offline") {
	require_once(JPATH_ROOT . "/templates/" . $this->template . "/offline.php");  
} 

// Add sidebar class to sidebar div 
if($it_params_sidebar_pos == 'left') {
	$it_sidebar_pos_class = 'sidebar_left';	
}
else {
	$it_sidebar_pos_class = 'sidebar_right';	
}

// sidebar "normal" or "narrow" paramter
if($it_params_sidebar_width == 'normal') 
{
	$it_sidebar_span = "span4";
}
else
{
	$it_sidebar_span = "span3";
}

// Layout Columns width		
if ($it_mod_sidebar != 0)
{ 	
	$it_content_span = "span8";
}
else
{
	$it_content_span = "span12";
}

if (($it_mod_sidebar != 0) && ($it_params_sidebar_width == 'narrow'))
{
	$it_content_span = "span9";
}

?>
<?php
//  @copyright	Copyright (C) 2008 - 2014 IceTheme. All Rights Reserved
//  @license	Copyrighted Commercial Software 
//  @author     IceTheme (icetheme.com)

// No direct access.
defined('_JEXEC') or die;


/* Template Parameters Variables
-------------------------------------------------------------------------*/

// General
$templatestyle		 			 	= $this->params->get('TemplateStyle');
$it_params_custom_color				= $this->params->get('TemplateCustomColor');
$it_params_logo 					= $this->params->get('logo');
$it_params_responsivebarlogo 		= $this->params->get('responsivebarlogo');
$it_params_sidebar_pos    		 	= $this->params->get('sidebar_position');
$it_params_sidebar_width		   	= $this->params->get('sidebar_span');
$it_params_gotop				   	= $this->params->get('go2top');
$it_params_hide_frontpage    	 	= $this->params->get('hidefrontpage');
$it_params_welcome    		     	= $this->params->get('it_params_welcome');
$it_params_showcase_image      	  	= $this->params->get('showcase_image');

// advanced
$it_params_responsive        	  	= $this->params->get('responsive_template');
$it_params_advanced_bootstrap      	= $this->params->get('advanced_bootstrap');
$it_params_advanced_animations     	= $this->params->get('advanced_animations');
$it_params_icelogo        	 	 	= $this->params->get('icelogo');

// Offline page 
$it_params_offline_h2		      	= $this->params->get('offline_h2');
$it_params_offline_p		       	= $this->params->get('offline_p');
$it_params_offline_form		    	= $this->params->get('offline_form');
$it_params_offline_count	       	= $this->params->get('offline_count');
$it_params_offline_count_time      	= $this->params->get('offline_count_time');
$it_params_offline_newsletter      	= $this->params->get('offline_newsletter');
$it_params_offline_social          	= $this->params->get('offline_social');

// Custom CSS
$it_params_custom_css			  	= $this->params->get('custom_css_code');

// helpers
$it_params_icelogo_title          = "We would like to inform that this website is designed by IceTheme.com with the latest standards provied by the World Wide Web Consortium (W3C)";

$it_params_logo_img				= '<img class="logo" src="'. JURI::root() . $this->params->get('logo') .'" alt="'. $sitename .'" />';
$it_params_responsivebarlogo_img		= '<img class="logo" src="'. JURI::root() . $this->params->get('responsivebarlogo') .'" alt="'. $sitename .'" />';



/* Module Positions variables 
-------------------------------------------------------------------------*/
global $it_mod_promo; 
global $it_mod_showcase; 
global $it_mod_footer;

$it_mod_language					= $this->countModules('language');
$it_mod_topmenu  	  	     	    = $this->countModules('topmenu');
$it_mod_mainmenu					= $this->countModules('mainmenu');
$it_mod_slideshow					= $this->countModules('slideshow');
$it_mod_promo						= $this->countModules('promo');
$it_mod_sidebar    			    	= $this->countModules('sidebar');
$it_mod_breadcrumbs					= $this->countModules('breadcrumbs');
$it_mod_testimonial			    	= $this->countModules('testimonial');
$it_mod_tagline				    	= $this->countModules('tagline');
$it_mod_showcase	 		  	   	= $this->countModules('showcase');
$it_mod_footer	 			     	= $this->countModules('footer');
$it_mod_footermenu    	         	= $this->countModules('footermenu');
$it_mod_debug						= $this->countModules('debug');
$it_mod_responsivebar 	 			= $this->countModules('responsivebar');
$it_mod_search						= $this->countModules('search');

?>
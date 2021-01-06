<?php
/**
 * @package Joomla.Site
 * @subpackage Templates.dna
 *
 * @copyright Copyright (C) 2014 Robert Went. All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die('caught by _JEXEC');
 
class JFormFieldCompile extends JFormField
{
 protected function getInput()
 {
 
 $app = JFactory::getApplication();
 $jinput = $app->input;
 $compile = 0;
 $compile = $jinput->get('compileless');
 $currentpath = realpath(__DIR__ ) ;
 $pageurl = str_replace('&amp;compileless=1', '', JURI::getInstance ());
 
	// get template frontend parameters
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query  ->select('params')
		->from('#__template_styles')
		->where('`template` = ' . $db->q('it_zen')) // Just replace 'beez3' with with the name of your template
		->where('client_id = 0'); // client_id = 0 for SITE and client_id = 1 for ADMIN templates
	$db->setQuery($query);
	$params = json_decode($db->loadResult());
	
	// get template custom color variable
	$TemplateCustomColor = $params->TemplateCustomColor;
	
	// Define Path to LESS File:
	$file = ($currentpath. '/../../assets/less/styles/custom_style.less');
	// Save each line of code into an Array
	$lines = file($file);
	// New Variable to store the old Line
	if ($lines){
		$NewColor = $lines[12];
	}
	else {
		$NewColor = "null";
	}
	$file_contents = file_get_contents($file);
	$file_contents = str_replace($NewColor,"@bg_primary:".$TemplateCustomColor.";\r\n",$file_contents);
	file_put_contents($file,$file_contents);
  
if ($compile) {
 // Load the RAD layer to use its LESS compiler
 if (!defined('FOF_INCLUDED'))
 {
 require_once JPATH_LIBRARIES . '/fof/include.php';
 }
 
$less = new FOFLess;
 $less->setFormatter(new FOFLessFormatterJoomla);
 
try
 {	 	
 $less->compileFile($currentpath. '/../../assets/less/styles/custom_style.less', $currentpath.'/../../assets/less/styles/custom_style.css');
}
 catch (Exception $e)
 {
 $app->enqueueMessage($e->getMessage(), 'error');
 }
 }
 return '<button onclick="window.location.href=\''.$pageurl.'\'+\'&amp;compileless=1\'" class="btn btn-primary" type="button">Compile</button>';
 }
 
}
?>
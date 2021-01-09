<?php
/**
 * @version     $Id: default.php 19013 2012-11-28 04:48:47Z thailv $
 * @package     JSNUniform
 * @subpackage  Form
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2016 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
defined('_JEXEC') or die('Restricted access');
$showTitle 	= false;
$showDes 	= false;
$app 		= JFactory::getApplication();
$app->enqueueMessage(JText::_('JSN_UNIFORM_PREVIEW_THIS_VIEW_IS_ONLY_FOR_PREVIEWING_PURPOSES'));
$postData 		= $this->_input->getArray($_POST);
if (!count($postData))
{
    $postData = $this->_input->post->getArray();
}
$config         = JFactory::getConfig();
$formToken      = $postData['form_token'];
$secretKey      = $postData['secret_key'];
$secret         = $config->get('secret');

if (md5($secret . $formToken) != $secretKey)
{
	die('Invalid token');
}

$htmlItems 					= json_decode($postData['html_items']);
$htmlFormpage 				= json_decode($postData['html_formpage']);
$htmlItems->form_content 	= base64_decode($htmlItems->form_content);

foreach ($htmlFormpage as $key => $value)
{
	$item = $htmlFormpage[$key];
	$item->page_content = base64_decode($item->page_content);
}

echo JSNUniformHelper::generateHTMLPages($this->_formId, $this->_formName, '', '', '', $showTitle, $showDes, false, true, $htmlItems, $htmlFormpage);



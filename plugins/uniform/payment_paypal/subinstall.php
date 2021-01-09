<?php
/**
 * @version    $Id$
 * @package    JSNUniform
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2016 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Disable notice and warning by default for our products.
// The reason for doing this is if any notice or warning appeared then handling JSON string will fail in our code.
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

/**
 * Subinstall script for finalizing JSN Uniform Plugin installation.
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
class PlgUniformPayment_PaypalInstallerScript
{

	public function preflight($mode, $parent)
	{
		$this->_updateSchema();
	}

	/**
	 * Enable JSN Framework system plugin.
	 *
	 * @param   string  $route  Route type: install, update or uninstall.
	 * @param   object  $_this  The installer object.
	 *
	 * @return  boolean
	 */
	public function postflight($route, $_this)
	{
		// Get a database connector object
		$db = JFactory::getDbo();
		try
		{
			// Enable plugin by default
			$q = $db->getQuery(true);
			$q->update('#__extensions');
			$q->set(array('enabled = 1'));
			$q->where("element = 'payment_paypal'");
			$q->where("type = 'plugin'", 'AND');
			$q->where("folder = 'uniform'", 'AND');
			$db->setQuery($q);
			$db->execute();
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * Update the extension schema
	 *
	 * @return void
	 */
	private function _updateSchema()
	{
		$row = JTable::getInstance('extension');
		$eid = $row->find(array('element' => 'payment_paypal', 'type' => 'plugin', 'folder' => 'uniform'));
		if ($eid)
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('version_id')
			->from('#__schemas')
			->where('extension_id = ' . $eid);
			$db->setQuery($query);
			$version = $db->loadResult();

			if (is_null($version))
			{
				$info = $this->_getInfo($eid);
				$info = json_decode($info->manifest_cache);
				$query = $db->getQuery(true);
				$query->delete()
				->from('#__schemas')
				->where('extension_id = ' . $eid);
				$db->setQuery($query);
				if ($db->Query())
				{
					$query->clear();
					$query->insert($db->quoteName('#__schemas'));
					$query->columns(array($db->quoteName('extension_id'), $db->quoteName('version_id')));
					$query->values($eid . ', ' . $db->quote($info->version));
					$db->setQuery($query);
					$db->Query();
				}
			}
		}
	}
	/**
	 * Get the extension information by ID
	 *
	 * @param (int) $id		The extension ID
	 * @return JDatabase object
	 */
	private function _getInfo($id)
	{
		$db		= JFactory::getDbo();
		$query 	= $db->getQuery(true);
		$query->select('*')
		->from($db->quoteName('#__extensions'))
		->where('element="payment_paypal" AND type="plugin" AND folder="uniform" AND extension_id = ' . $id);
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}
}

<?php
/**
 * @package         Advanced Module Manager
 * @version         7.13.3
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2020 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

use RegularLabs\Library\Document as RL_Document;

defined('_JEXEC') or die;

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

if ( ! RL_Document::isJoomlaVersion(3))
{
	return;
}

if (true)
{
	class PlgActionlogAdvancedModules
		extends \RegularLabs\Library\ActionLogPlugin
	{
		public $name  = 'ADVANCEDMODULEMANAGER';
		public $alias = 'advancedmodules';

		public function __construct(&$subject, array $config = [])
		{
			parent::__construct($subject, $config);

			$this->items = [
				'module' => (object) [
					'title' => 'PLG_ACTIONLOG_JOOMLA_TYPE_MODULE',
				],
			];
		}
	}
}

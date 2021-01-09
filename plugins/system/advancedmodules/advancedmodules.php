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

defined('_JEXEC') or die;

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Language\Text as JText;
use RegularLabs\Library\Document as RL_Document;
use RegularLabs\Library\Extension as RL_Extension;
use RegularLabs\Library\Language as RL_Language;
use RegularLabs\Library\Plugin as RL_Plugin;
use RegularLabs\Library\Protect as RL_Protect;
use RegularLabs\Library\RegEx as RL_RegEx;
use RegularLabs\Plugin\System\AdvancedModules\Document;
use RegularLabs\Plugin\System\AdvancedModules\ModuleHelper;
use RegularLabs\Plugin\System\AdvancedModules\Params;

// Do not instantiate plugin on install pages
// to prevent installation/update breaking because of potential breaking changes
$input = JFactory::getApplication()->input;
if (in_array($input->get('option'), ['com_installer', 'com_regularlabsmanager']) && $input->get('action') != '')
{
	return;
}

require_once __DIR__ . '/vendor/autoload.php';

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	JFactory::getLanguage()->load('plg_system_advancedmodules', __DIR__);
	JFactory::getApplication()->enqueueMessage(
		JText::sprintf('AMM_EXTENSION_CAN_NOT_FUNCTION', JText::_('ADVANCEDMODULEMANAGER'))
		. ' ' . JText::_('AMM_REGULAR_LABS_LIBRARY_NOT_INSTALLED'),
		'error'
	);

	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

if (! RL_Document::isJoomlaVersion(3, 'ADVANCEDMODULEMANAGER'))
{
	RL_Extension::disable('advancedmodules', 'plugin');

	RL_Language::load('plg_system_regularlabs');

	JFactory::getApplication()->enqueueMessage(
		JText::sprintf('RL_PLUGIN_HAS_BEEN_DISABLED', JText::_('ADVANCEDMODULEMANAGER')),
		'error'
	);

	return;
}

if (true)
{
	class PlgSystemAdvancedModules extends RL_Plugin
	{
		public $_title           = 'ADVANCEDMODULEMANAGER';
		public $_lang_prefix     = 'AMM';
		public $_page_types      = ['html'];
		public $_enable_in_admin = true;
		public $_jversion        = 3;

		protected function extraChecks()
		{
			if ( ! RL_Protect::isComponentInstalled('advancedmodules'))
			{
				return false;
			}

			return true;
			//return parent::extraChecks();
		}

		protected function handleOnAfterInitialise()
		{
			if (Params::get()->initialise_event != 'onAfterRoute')
			{
				$this->initialise();
			}
		}

		protected function handleOnAfterRoute()
		{
			if ( ! $this->_is_admin)
			{
				Document::loadFrontEditScript();
			}

			if (Params::get()->initialise_event == 'onAfterRoute')
			{
				$this->initialise();
			}
		}

		private function initialise()
		{
			if ($this->_is_admin)
			{
				return;
			}

			ModuleHelper::registerEvents();
		}

		protected function changeFinalHtmlOutput(&$html)
		{
			Document::replaceLinks($html);

			return true;
		}

	}
}


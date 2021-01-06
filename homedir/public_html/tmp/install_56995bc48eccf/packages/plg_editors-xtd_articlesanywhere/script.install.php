<?php
/**
 * Install script
 *
 * @package         Articles Anywhere
 * @version         4.1.5
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2015 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/script.install.helper.php';

class PlgEditorsXtdArticlesAnywhereInstallerScript extends PlgEditorsXtdArticlesAnywhereInstallerScriptHelper
{
	public $name = 'ARTICLES_ANYWHERE';
	public $alias = 'articlesanywhere';
	public $extension_type = 'plugin';
	public $plugin_folder = 'editors-xtd';

	public function uninstall($adapter)
	{
		$this->uninstallPlugin($this->extname, 'system');
	}
}

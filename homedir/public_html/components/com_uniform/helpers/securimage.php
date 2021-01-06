<?php

/**
 * @version     $Id: form.php 19014 2012-11-28 04:48:56Z thailv $
 * @package     JSNUniform
 * @subpackage  Helpers
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2016 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ROOT . '/components/com_uniform/libraries/3rd-party/securimage/securimage.php';

class JsnSecurimage extends Securimage
{
	/**
	 * Gets the code and returns the binary audio file for the stored captcha code
	 *
	 * @return The audio representation of the captcha in Wav format
	 */
	protected function getAudibleCode()
	{
		$letters = array();
		$code = $this->getCode(true, true);

		if ($code['code'] == '')
		{
			if (strlen($this->display_value) > 0)
			{
				$code = array('code' => $this->display_value, 'display' => $this->display_value);
			}
			else
			{
				$this->createCode();
				$code = $this->getCode(true);
			}
		}

		if (preg_match('/(\d+) (\+|-|x) (\d+)/i', $code['display'], $eq))
		{
			$math = true;

			$left = $eq[1];
			$sign = str_replace(array('+', '-', 'x'), array('plus', 'minus', 'times'), $eq[2]);
			$right = $eq[3];

			$letters = array($left, $sign, $right);
		}
		else
		{
			$math = false;

			$length = strlen($code['display']);

			for ($i = 0; $i < $length; ++$i)
			{
				$letter = $code['display'][$i];
				$letters[] = $letter;
			}
		}

		try
		{
			return $this->generateWAV($letters);
		}
		catch (Exception $ex)
		{
			throw $ex;
		}
	}
}

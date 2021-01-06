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

// No direct access
defined('_JEXEC') or die('Restricted access');
require_once JPATH_ROOT . '/administrator/components/com_uniform/helpers/currency.php';

//Backward compatibility
if (file_exists(JPATH_ROOT . '/administrator/components/com_uniform/helpers/email.php')) {
	require_once JPATH_ROOT . '/administrator/components/com_uniform/helpers/email.php';
}

if (!class_exists('JSNUFPayment_PaypalHelperPaypalExp')) {
	require_once(JPATH_ROOT  .'/plugins/uniform/payment_paypal/helpers/paypalexp.php');
}

if (!class_exists('JSNUFPayment_PaypalHelperPaypalStd')) {
	require_once(JPATH_ROOT  .'/plugins/uniform/payment_paypal/helpers/paypalstd.php');
}

class PlgUniformPayment_Paypal extends JPlugin
{
	/**
	 * Method to initialize form object for editing payment gateway profile.
	 *
	 * @param   string  $path   Path to form declaration file.
	 *
	 * @return  mixed   A JForm object that can be used to render edit form, or null if any problem occurs.
	 */
	private $_currency = array(
		'USD' => array('currency_symbol' => '$', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
		'THB' => array('currency_symbol' => '฿', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
		'TWD' => array('currency_symbol' => 'NT$', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
		'CHF' => array('currency_symbol' => 'CHF', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
		'SEK' => array('currency_symbol' => 'kr', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
		'SGD' => array('currency_symbol' => '$', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
		'RUB' => array('currency_symbol' => 'руб', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
		'GBP' => array('currency_symbol' => '£', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
		'PLN' => array('currency_symbol' => 'zł', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
		'PHP' => array('currency_symbol' => '₱', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
		'NZD' => array('currency_symbol' => '$', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
		'NOK' => array('currency_symbol' => 'kr', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
		'MXN' => array('currency_symbol' => '$', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
		'JPY' => array('currency_symbol' => '¥', 'currency_decimals' => '0', 'currency_decimal_symbol' => ',', 'currency_thousands_separator' => ''),
		'HUF' => array('currency_symbol' => 'Ft', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
		'HKD' => array('currency_symbol' => '元', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
		'EUR' => array('currency_symbol' => '€', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
		'DKK' => array('currency_symbol' => 'kr', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
		'CZK' => array('currency_symbol' => 'Kč', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
		'CAD' => array('currency_symbol' => '$', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
		'BRL' => array('currency_symbol' => 'R$', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
		'AUD' => array('currency_symbol' => '$', 'currency_decimals' => '2', 'currency_decimal_symbol' => '.', 'currency_thousands_separator' => ''),
	);

	private $_paymentGatewayType = null;

	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe.
	 * @param   array   $config    An array that holds the plugin configuration.
	 */
	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);
		$this->setPaymentGatewayType();
	}

	public function getEditForm($path = '')
	{
		// Define path to form declaration file
		!empty($path) or $path = JPATH_PLUGINS . '/uniform/payment_paypal/config.xml';

		return 	$form = JForm::getInstance('uniform.plugin.paymentgateway.paypal.params', $path, array('control' => 'jform'));

	}

	/**
	 * @return bool|mixed
	 */
	public function getPaymentGatewayConfig()
	{
		$db			= JFactory::getDBO();
		$query 		= $db->getQuery(true);
		$query->clear();
		$query->select('*');
		$query->from($db->quoteName('#__extensions'));
		$query->where($db->quoteName('type') . ' = ' . $db->quote('plugin') . ' AND ' . $db->quoteName('folder') . ' = ' . $db->quote('uniform'). ' AND ' . $db->quoteName('element') . ' = ' . $db->quote('payment_paypal'));
		$db->setQuery($query);
		$return = $db->loadObject();

		if (count($return))
		{
			return json_decode($return->params);
		}
		return false;
	}

	/**
	 * @param $params
	 *
	 * @return bool
	 */
	public function savePaymentGatewayConfig($params)
	{
		if (!count ($params)) return false;

		$params['uniform'] = 'uniform';
		$db			= JFactory::getDBO();
		$defaults 	= json_encode($params);
		$query 		= $db->getQuery(true);
		$query->clear();
		$query->update($db->quoteName('#__extensions'));
		$query->set($db->quoteName('params') . ' = ' . $db->quote($defaults));
		$query->where($db->quoteName('type') . ' = ' . $db->quote('plugin') . ' AND ' . $db->quoteName('folder') . ' = ' . $db->quote('uniform'). ' AND ' . $db->quoteName('element') . ' = ' . $db->quote('payment_paypal'));
		$db->setQuery($query);
		return $db->execute();
	}

	public function setPaymentGatewayType()
	{
		$_config = $this->getPaymentGatewayConfig();
		if ($this->params->get('payment_product') == 'standard')
		{
			$this->_paymentGatewayType = new JSNUFPayment_PaypalHelperPaypalStd($this->_currency, $_config);
		}
		else
		{
			$this->_paymentGatewayType = new JSNUFPayment_PaypalHelperPaypalExp($this->_currency, $_config);
		}
	}

	public function processToPostPaymentGateway($data, $dataField, $sub_id)
	{
		return $this->_paymentGatewayType->processToPostPaymentGateway($data, $dataField, $sub_id);
	}

	/**
	 *
	 * @param string $query				JQuery
	 * @param string $paymentStatus		string of status
	 */
    public function buildPaymentStatusQueryFilter(&$query, $paymentStatus)
    {
        $query->join('INNER', '#__jsn_uniform_payment_paypal_logs AS pm ON pm.submission_id = sd.submission_id');
        $query->where('pm.log_status = "' . $paymentStatus . '"');
    }

    /**
     * Render Payment status combobox
     *
     * @param string $paymentStatus	the payment status for filter searching
     * @return string
     */
    public function renderPaymentStatusComboBox($paymentStatus)
    {
        $paymentStatusList = array(
            'successful'=> JTEXT::_("JSN_UNIFORM_SELECT_PAYMENT_SUCCESSFUL"),
        	'unsuccessful' => JTEXT::_("JSN_UNIFORM_SELECT_PAYMENT_UNSUCCESSFUL"),
        );
        return '<select name="filter_payment_status" onchange="this.form.submit()" class="inputbox" id="filter_payment_status">
            <option value="">- ' . JText::_('JSN_UNIFORM_SELECT_PAYMENT_STATUS') . ' -</option>
            ' . JHtml::_('select.options', $paymentStatusList, 'value', 'text', $paymentStatus) . '
        </select>';
    }

    /**
     * Delete transaction log
     * @param $submission_id
     */
    public function deletePaymentLog($submissionID)
    {
        $db 	= JFactory::getDbo();
        $query 	= $db->getQuery(true);
        $conditions = array(
            $db->quoteName('submission_id') . ' = ' . (int) $submissionID
        );
        $query->delete($db->quoteName('#__jsn_uniform_payment_paypal_logs'));
        $query->where($conditions);
        $db->setQuery($query);
        $db->execute();
    }

	public function verifyGatewayResponse($post)
	{
	    $isValid = $this->_paymentGatewayType->verifyGatewayResponse($post);

		return $isValid;
	}

	public function checkPaymentGatewayValid()
	{
		return $this->_paymentGatewayType->checkPaymentGatewayValid();
	}

	public function displayCurrency($number)
	{
		$curencyData = $this->_currency[$this->params->get('paypal_currency')];
		$curencyFormat = new JSNUniFormCurrencyHelper($this->params->get('paypal_currency'), $curencyData['currency_decimals'], $curencyData['currency_decimal_symbol'], $curencyData['currency_thousands_separator'], $curencyData['currency_symbol'], $this->params->get('paypal_positon_symbol'));
		$price = $curencyFormat->getFormattedCurrency($number);
		$display = '';
		switch($this->params->get('paypal_positon_symbol'))
		{
			case 'left':
				$display = '<span class="currency-symbol">'.$curencyData['currency_symbol'].'</span><span class="total-money">'. $price .'</span>';
				break;
			case 'right':
				$display = '<span class="total-money">'. $price .'</span><span class="currency-symbol">'.$curencyData['currency_symbol'].'</span>';
				break;
			case 'left_with_space':
				$display = '<span class="currency-symbol">'.$curencyData['currency_symbol'].' </span><span class="total-money">'. $price .'</span>';
				break;
			case 'right_with_space':
				$display = '<span class="total-money">'. $price .' </span><span class="currency-symbol"> '.$curencyData['currency_symbol'].'</span>';
				break;
			default:
				$display = '<span class="currency-symbol">'.$curencyData['currency_symbol'].'</span><span class="total-money">'. $price .'</span>';
				break;
		}
		return $display;
	}

	public function renderConfigForm()
	{
		$form = $this->getEditForm();
		$data = $this->getPaymentGatewayConfig();
		$form->bind($data);
		$this->_loadAssets();
		include_once JPATH_ROOT . '/plugins/uniform/payment_paypal/form/settings.php';
	}

	protected function _loadAssets()
	{
		JSNHtmlAsset::addScript(JURI::root() . 'plugins/uniform/payment_paypal/assets/js/settings.js');
	}

	/**
	 *
	 * @return Ambigous <boolean, mixed>
	 */
	public function getPaymentGatewayParams()
	{
		return $this->getPaymentGatewayConfig();
	}
}


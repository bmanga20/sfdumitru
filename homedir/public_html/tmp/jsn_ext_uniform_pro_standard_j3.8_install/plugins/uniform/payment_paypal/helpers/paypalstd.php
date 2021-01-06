<?php
/**
 * @version    $Id$
 * @package    JSN_Uniform
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2016 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

defined('_JEXEC') or die('Restricted access');

class JSNUFPayment_PaypalHelperPaypalStd
{
	private $_nvpURL = '';

	private $_currency = array();

	private $_paymentConfig = null;

	public function __construct($_currency, $_paymentConfig)
	{
		$this->_currency = $_currency;
		$this->_paymentConfig = $_paymentConfig;

		$this->_nvpURL	= 'https://www.' . ($this->_paymentConfig->test_mode ? 'sandbox.' : '') . 'paypal.com/cgi-bin/webscr';

		if ($this->_paymentConfig->test_mode)
		{
			$this->_business 	= $this->_paymentConfig->merchant_email_sandbox;
		}
		else
		{
			$this->_business 	= $this->_paymentConfig->merchant_email;
		}
	}

    /**
     *	Insert Payment Log
     *
     * @param (int) $sub_id		The submission ID
     *
     * @return void
     */
    public function insertPaymentLog($sub_id)
    {
    	$params = (array) $this->_paymentConfig;

    	$db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->insert($db->quoteName("#__jsn_uniform_payment_paypal_logs"));
        $query->columns($db->quoteName(array('submission_id', 'log_currency')));
        $query->values(implode(',', array($sub_id, $db->quote($params['paypal_currency']))));
        $db->setQuery($query);
        $db->execute();
    }

	public function processToPostPaymentGateway($data, $dataField, $sub_id)
	{
		$data 	= $this->_prepareData($data, $dataField, $sub_id);
		$this->insertPaymentLog($sub_id);
		echo $this->_renderStandardPaypalForm($data);
		return true;

	}

	/**
	 * prepare and process data before submit to PayPal
	 * @param array $data
	 *
	 * @return array
	 */
	private function _prepareData($data, $dataField, $subId)
	{
		$params = (array) $this->_paymentConfig;
		$submitData = array();
		$curencyData = $this->_currency[$params['paypal_currency']];

		$curencyFormat = new JSNUniFormCurrencyHelper($params['paypal_currency'], $curencyData['currency_decimals'], $curencyData['currency_decimal_symbol'], $curencyData['currency_thousands_separator'], $curencyData['currency_symbol'], $params['paypal_positon_symbol']);

		$total = $curencyFormat->getFormattedCurrency($data['jsn_form_total_money']['form_payment_money_value']);

		if (count($dataField))
		{
			$index = 1;
			foreach ($dataField as $key => $value)
			{

				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select('field_settings');
				$query->from('#__jsn_uniform_fields');
				$query->where('field_id='. $value['field_id']);
				$db->setQuery($query);
				$fieldSettings = $db->loadObjectList($query);

				foreach ($fieldSettings as $fieldSetting)
				{
					$itemSettings = json_decode($fieldSetting->field_settings);
					if (!empty($itemSettings->options->paymentMoneyValue) && $itemSettings->options->paymentMoneyValue == 'Yes')
					{

						if (!empty($value['submission_data_value']))
						{
							if ($value['field_type'] == 'checkboxes' || $value['field_type'] == 'dropdown' || $value['field_type'] == 'choices')
							{

							}

							if ($value['field_type'] == 'checkboxes' || $value['field_type'] == 'list' )
							{
								$items = json_decode($value['submission_data_value']);
								foreach($items as $item){
									$value['submission_data_value'] = $item;
									$moneyValue = str_replace(',', '.',$value['submission_data_value']);
									$tmpMoneyValue = explode('|', $moneyValue);
									$quantityValue = trim(end($tmpMoneyValue));
									$moneyValue = trim($tmpMoneyValue[1]);
									$expSubmitsionDataValue = explode('|', $value['submission_data_value']);
									$title = trim($value['field_title']) . ':' . trim(@$expSubmitsionDataValue[0]);
									$submitData = array_merge($submitData,array('item_name_'.$index => $title, 'item_number_'.$index => '1', 'amount_'.$index => $curencyFormat->getFormattedCurrency((float)$moneyValue), 'quantity_'.$index => $quantityValue));
									$index++;
								}
							}
						}
					}
				}
				foreach ($fieldSettings as $fieldSettingSpc)
				{
					$itemSettings = json_decode($fieldSettingSpc->field_settings);
					if (!empty($itemSettings->options->paymentMoneyValue) && $itemSettings->options->paymentMoneyValue == 'Yes')
					{


						if (!empty($value['submission_data_value']))
						{
							if ($value['field_type'] != 'checkboxes' && $value['field_type'] != 'list' )
							{
								$title = $value['field_title'];
								$moneyValue = str_replace(',', '.', $value['submission_data_value']);
								$tmpMoneyValue = explode('|', $moneyValue);
								$quantityValue = trim(end($tmpMoneyValue));
								$moneyValue = trim($tmpMoneyValue[1]);
								if ($value['field_type'] == 'dropdown' || $value['field_type'] == 'choices')
								{
									$expSubmitsionDataValue = explode('|', $value['submission_data_value']);
									$title = trim($value['field_title']) . ':' . trim(@$expSubmitsionDataValue[0]);
								}
								if ($value['field_type'] == 'number' || $value['field_type'] == 'currency')
								{
									$moneyValue = str_replace(',', '.', $value['submission_data_value']);
									$quantityValue = '1';
								}
								$submitData = array_merge($submitData, array('item_name_' . $index => $title, 'item_number_' . $index => '1', 'amount_' . $index => $curencyFormat->getFormattedCurrency((float) $moneyValue), 'quantity_' . $index => $quantityValue));
								$index++;
							}
						}
					}
				}
			}
		}

		$token 		= JSession::getFormToken();
		$config 	= JFactory::getConfig();
		$secret 	= $config->get('secret');
		$returnUrl = JURI::base() . 'index.php?option=com_uniform&form_id=' . $data['form_id'] . '&secret_key=' . md5($secret) . '&submission_id=' . $subId . '&method=payment_paypal&view=paymentgateway&task=paymentgateway.postback';
		if (!isset($params['archive_cancel_transaction']) || $params['archive_cancel_transaction'] == "1")
		{
			$cancelUrl = $params['paypal_cancel_url'] != '' ? $params['paypal_cancel_url'] : JURI::base();
		}
		else
		{
			$cancelUrl = JURI::base() . 'index.php?option=com_uniform&form_id=' . $data['form_id'] . '&submission_id=' . $subId . '&secret_key=' . md5($secret) . '&' . $token . '=1' . '&method=payment_paypal&view=paymentgateway&task=paymentgateway.cancelTransaction';
		}

		$fields = array();
		$fields['cmd'] 				= '_cart';
		$fields['business'] 		= $this->_business;
		$fields['amount'] 			= $total;
		$fields['currency_code'] 	= $params['paypal_currency'];
		$fields['cpp_logo_image'] = $params['paypal_logo'];
		$fields['charset'] 			= 'utf-8';
		$fields['no_note'] 			= '1';
		$fields['invoice']			= $this->_createOrderCode();
		$fields['custom'] 			= $data['form_id'].'|'.md5($secret);
		$fields['return'] 			= $returnUrl;
		$fields['cancel_return'] 	= $cancelUrl;
		$fields['upload'] 			= '1';
		$fields['rm']				= '2';
		$fields['lc']				= 'US';
		$fields = array_merge($fields, $submitData);
		return $fields;

	}

	public function verifyGatewayResponse($post)
	{
		if ("completed" == strtolower($post["st"]) || "pending" == strtolower($post["st"]) || "created" == strtolower($post["st"]) ||
		    "completed" == strtolower($post["payment_status"]) || "pending" == strtolower($post["payment_status"]) || "created" == strtolower($post["payment_status"])
        )
		{
			$this->_updatePaymentLog($post, $post);
			$this->_sendMail($post);
			return true;
		}

		return false;
	}


	private function _renderStandardPaypalForm($data)
	{
		$lang 			= JFactory::getLanguage();
		$lang->load('plg_uniform_payment_paypal', JPATH_BASE . '/administrator');

		$html = '<div class="ui-widget-overlay">
					  <div class="img-box-loading">
					    <img id="img-loading-uiwindow-1" class="imgLoading" src="'. JURI::base() .'/plugins/uniform/payment_paypal/assets/img/icon-24-dark-loading-circle.gif">
					  </div>
					  <div class="you-are-being-redirected">
					  	' . JText::_('PLG_JSNUNIFORM_PAYMENT_YOU_ARE_BEING_REDIRECTED') . '
					  </div>
				  </div>';
		$html .= '<form action="'.$this->_nvpURL.'" method="post" id="jsnuf-paypalstd" target="_parent">';
		foreach($data as $name => $value)
		{
			$html .= '<input type="hidden" name="'.trim($name).'" value="'. htmlspecialchars(trim($value), ENT_COMPAT, 'UTF-8').'">';
		}
		$html .= '</form>';
		$html .= '
				<script>
					document.getElementById("jsnuf-paypalstd").submit();
				</script>';
		echo '<link rel="stylesheet" href="'. JURI::base() .'/plugins/uniform/payment_paypal/assets/css/jsnpayment_paypal.css">';

		return $html;
	}

	public function checkPaymentGatewayValid()
	{
		if (!is_null($this->_paymentConfig->test_mode))
		{
			if ($this->_paymentConfig->test_mode)
			{
				if((string) $this->_business == '')
				{
					return false;
				}
			}
			else
			{
				if ((string) $this->_business == '')
				{
					return false;
				}
			}
			return true;
		}
		return false;
	}

	private function _createOrderCode()
	{
		$length		    = 8;
		$chars		    = 'abcdefghijklmnopqrstuvwxyz';
		$chars_length   = (strlen($chars) - 1);
		$string		    = $chars{rand(0, $chars_length)};
		for ($i	= 1; $i < $length; $i = strlen($string))
		{
			$r  = $chars{rand(0, $chars_length)};
			if ($r != $string{$i - 1})
				$string .= $r;
		}
		$fullString = dechex(time() + mt_rand(0, 10000000)) . $string;
		$result	    = strtoupper(substr($fullString, 2, 10));
		return $result;
	}

	/**
	 * Send email
	 *
	 * @param array $post	the post data
	 *
	 * @return boole
	 */
	private function _sendMail($post)
	{
		if (!class_exists('JSNUniFormEmailHelper')) return false;
		// only send email when transaction done

		if (isset($this->_paymentConfig->receive_confirmation_of_successful_transaction))
		{
			if ($this->_paymentConfig->receive_confirmation_of_successful_transaction == '1')
			{
				$objJSNUniFormEmailHelper = new JSNUniFormEmailHelper;
				$objJSNUniFormEmailHelper->prepareDataForEmail($post);
			}
		}

		return true;
	}

	/**
	 *
	 *  Update transaction log. Set status, amount, currency
	 * @param array $post
	 *
	 */
	private function _updatePaymentLog($data, $post)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$fields = array(
				$db->quoteName('log_status') . ' = ' . $db->quote('successful'),
				$db->quoteName('log_amount') . ' = ' . $db->quote($data['mc_gross']),
				$db->quoteName('log_currency') . ' = ' . $db->quote($data['mc_currency'])
		);
		$conditions = array(
				$db->quoteName('submission_id') . ' = ' . $post['submission_id']
		);
		$query->update($db->quoteName("#__jsn_uniform_payment_paypal_logs"));
		$query->set($fields);
		$query->where($conditions);
		$db->setQuery($query);
		$db->execute();
	}
}
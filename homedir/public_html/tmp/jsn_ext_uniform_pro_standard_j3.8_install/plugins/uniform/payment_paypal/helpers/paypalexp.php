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

class JSNUFPayment_PaypalHelperPaypalExp
{
	private $_expressCheckoutURL = '';

	private $_nvpURL = '';

	private $_paypalVersion = 93;

	private $_apiUsername = '';

	private $_apiPassword = '';

	private $_apiSignature = '';

	private $_currency = array();

	private $_paymentConfig = null;

	public function __construct($_currency, $_paymentConfig)
	{
		$this->_currency = $_currency;
		$this->_paymentConfig = $_paymentConfig;
		@$this->_expressCheckoutURL	= 'https://www.' . ($this->_paymentConfig->test_mode ? 'sandbox.' : '') . 'paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';
		@$this->_nvpURL	= 'https://api-3t.' . ($this->_paymentConfig->test_mode ? 'sandbox.' : '') . 'paypal.com/nvp';

		if (@$this->_paymentConfig->test_mode)
		{
			$this->_apiUsername 	= $this->_paymentConfig->paypal_sandbox_api_username;
			$this->_apiPassword 	= $this->_paymentConfig->paypal_sandbox_api_password;
			$this->_apiSignature 	= $this->_paymentConfig->paypal_sandbox_api_signature;
		}
		else
		{
			@$this->_apiUsername 	= $this->_paymentConfig->paypal_live_api_username;
			@$this->_apiPassword 	= $this->_paymentConfig->paypal_live_api_password;
			@$this->_apiSignature 	= $this->_paymentConfig->paypal_live_api_signature;
		}
	}


	/**
	 * Post Data to Paypal
	 *
	 * @param array $data
	 *
	 * @return mix
	 */
	private function _postDataToPayPal($fields)
	{
		if (!count($fields)) return false;

		$fieldsString = http_build_query($fields);
		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $this->_nvpURL);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);

		//execute post
		$result = curl_exec($ch);

		if (curl_errno($ch))
		{
			return false;
		}
		//close connection
		curl_close($ch);
		return $result;
	}

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

    /**
     *	Insert Payment Log
     *
     * @param (int) $sub_id		The submission ID
     *
     * @return void
     */
	public function processToPostPaymentGateway($data, $dataField, $sub_id)
	{
		$checkCURL = $this->_checkCURLLibrary();
		if ($checkCURL === false) return false;


		$data 	= $this->_prepareData($data, $dataField, $sub_id);

		$nvpreq = $this->_postDataToPayPal($data);

		if ($nvpreq === false) return false;

		$nvpResArray = $this->_deformatNVP($nvpreq);

		if (isset($nvpResArray['TOKEN']))
		{
			$this->insertPaymentLog($sub_id);

			$lang 			= JFactory::getLanguage();
			$lang->load('plg_uniform_payment_paypal', JPATH_BASE . '/administrator');
			echo '<link rel="stylesheet" href="'. JURI::base() .'/plugins/uniform/payment_paypal/assets/css/jsnpayment_paypal.css">';
			echo '<div class="ui-widget-overlay">
					  <div class="img-box-loading">
					    <img id="img-loading-uiwindow-1" class="imgLoading" src="'. JURI::base() .'/plugins/uniform/payment_paypal/assets/img/icon-24-dark-loading-circle.gif">
					  </div>
					   <div class="you-are-being-redirected">
					  	' . JText::_('PLG_JSNUNIFORM_PAYMENT_YOU_ARE_BEING_REDIRECTED') . '
					  </div>
				  </div>';
			echo "<script>window.parent.location.href ='".$this->_expressCheckoutURL . $nvpResArray['TOKEN']."';</script>";
			return true;
		}
		elseif(isset($nvpResArray['L_ERRORCODE0']))
		{
			echo "<script>alert('Error Code: " . $nvpResArray['L_ERRORCODE0'] . ' - ' . $nvpResArray['L_LONGMESSAGE0'] . "');window.history.back();</script>";
			return false;
		}

		return false;

	}

	/**
	 * Deformat returned string from paypal
	 *
	 * @param string $nvpstr
	 *
	 * @return array
	 */
	private function _deformatNVP($nvpstr)
	{
		$intial=0;
		$nvpArray = array();

		while(strlen($nvpstr))
		{
			//postion of Key
			$keypos = strpos($nvpstr,'=');
			//position of value
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval = substr($nvpstr, $intial, $keypos);
			$valval = substr($nvpstr, $keypos + 1, $valuepos - $keypos - 1);
			//decoding the respose
			$nvpArray[urldecode($keyval)] = urldecode( $valval);
			$nvpstr = substr($nvpstr,$valuepos + 1, strlen($nvpstr));
		}

		return $nvpArray;
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
			$index = 0;
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
									$submitData = array_merge($submitData,array('L_PAYMENTREQUEST_0_NAME'.$index => $title, 'L_PAYMENTREQUEST_0_NUMBER'.$index => '1', 'L_PAYMENTREQUEST_0_DESC'.$index => $title, 'L_PAYMENTREQUEST_0_AMT'.$index => $curencyFormat->getFormattedCurrency((float)$moneyValue), 'L_PAYMENTREQUEST_0_QTY'.$index => $quantityValue));
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
								$submitData = array_merge($submitData, array('L_PAYMENTREQUEST_0_NAME' . $index => $title, 'L_PAYMENTREQUEST_0_NUMBER' . $index => '1', 'L_PAYMENTREQUEST_0_DESC' . $index => $title, 'L_PAYMENTREQUEST_0_AMT' . $index => $curencyFormat->getFormattedCurrency((float) $moneyValue), 'L_PAYMENTREQUEST_0_QTY' . $index => $quantityValue));
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
		$fields = array('USER' => $this->_apiUsername,
		                'PWD' => $this->_apiPassword,
		                'SIGNATURE' => $this->_apiSignature,
		                'METHOD' => 'SetExpressCheckout', 'VERSION' => $this->_paypalVersion, 'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
						'SOLUTIONTYPE' => 'Sole',
						'LANDINGPAGE' => 'Billing',
						'LOCALECODE' => 'us',
		                'PAYMENTREQUEST_0_AMT' => $total,
		                'PAYMENTREQUEST_0_CURRENCYCODE' => $params['paypal_currency'],
		                'RETURNURL' => $returnUrl,
		                'CANCELURL' => $cancelUrl,
		                'LOGOIMG' => (string) $params['paypal_logo']);
		$fields = array_merge($fields, $submitData);
		return $fields;
	}

	public function verifyGatewayResponse($post)
	{
		return $this->_getExpressCheckoutDetails($post);
	}


	private function _getExpressCheckoutDetails($data)
	{
		$fields = array('USER' => $this->_apiUsername,
		                'PWD' => $this->_apiPassword,
		                'SIGNATURE' => $this->_apiSignature,
		                'METHOD' => 'GetExpressCheckoutDetails', 'VERSION' => $this->_paypalVersion,
		                'TOKEN' => $data['token']);

		$nvpreq = $this->_postDataToPayPal($fields);

		if ($nvpreq === false) return false;

		$nvpResArray = $this->_deformatNVP($nvpreq);

		if ("success" == strtolower($nvpResArray["ACK"]) || "successwithwarning" == strtolower($nvpResArray["ACK"]))
		{
			return $this->_doExpressCheckoutPayment($nvpResArray, $data);
		}

		return false;

	}

	private function _doExpressCheckoutPayment($data, $post)
	{
		$fields = array('USER' => $this->_apiUsername,
		                'PWD' => $this->_apiPassword,
		                'SIGNATURE' => $this->_apiSignature,
		                'METHOD' => 'DoExpressCheckoutPayment', 'VERSION' => $this->_paypalVersion);

		$fields = array_merge($fields, $data);

		if (isset($fields["ACK"]))
		{
			unset($fields["ACK"]);
		}

		$nvpreq = $this->_postDataToPayPal($fields);

		if ($nvpreq === false) return false;

		$nvpResArray = $this->_deformatNVP($nvpreq);

		if ("success" == strtolower($nvpResArray["ACK"]) || "successwithwarning" == strtolower($nvpResArray["ACK"]))
		{
			$this->_updatePaymentLog($nvpResArray, $post);
			$this->_sendMail($post);
			return true;
		}

		return false;
	}

	public function checkPaymentGatewayValid()
	{
		if (@!is_null($this->_paymentConfig->test_mode))
		{
			if ($this->_paymentConfig->test_mode)
			{
				if((string) $this->_apiUsername == '' || (string) $this->_apiPassword == '' || (string) $this->_apiSignature == '')
				{
					return false;
				}
			}
			else
			{
				if ((string) $this->_apiUsername == '' || (string) $this->_apiPassword == '' || (string) $this->_apiSignature == '')
				{
					return false;
				}
			}
			return true;
		}
		return false;
	}

	/**
	 * check support CURL
	 * @return true/false
	 */
	private function _checkCURLLibrary()
	{
		if (!function_exists("curl_init") &&
			!function_exists("curl_setopt") &&
			!function_exists("curl_exec") &&
			!function_exists("curl_close")) {
			return false;
		};

		return true;
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
				$db->quoteName('log_amount') . ' = ' . $db->quote($data['PAYMENTINFO_0_AMT']),
				$db->quoteName('log_currency') . ' = ' . $db->quote($data['PAYMENTINFO_0_CURRENCYCODE'])
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
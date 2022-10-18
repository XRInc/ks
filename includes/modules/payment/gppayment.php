<?php
/**
 * payment gppayment.php
 */
class gppayment
{
	function before()
	{
		$monthStr = '<option value="">' . __('Month') . '</option>';
		for ($i = 1; $i <= 12; $i++) {
			$monthStr .= '<option value="' . str_pad($i, 2, '0', STR_PAD_LEFT) . '">' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>';
		}

		$year    = date('Y');
		$yearStr = '<option value="">' . __('Year') . '</option>';
		for ($i = 0; $i < 25; $i++) {
			$yearStr .= '<option value="' . substr($year + $i, -2, 2) . '">' . substr($year + $i, -2, 2) . '</option>';
		}

		$txtCardNumber = __('Card Number');
		$txtCVC        = __('CVV');
		$v             = DIR_WS_CATALOG_IMAGES . 'payment/icon/v.png';
		$m             = DIR_WS_CATALOG_IMAGES . 'payment/icon/m.png';
		$j             = DIR_WS_CATALOG_IMAGES . 'payment/icon/j.png';
		$a             = DIR_WS_CATALOG_IMAGES . 'payment/icon/a.png';
		$vmj           = DIR_WS_CATALOG_IMAGES . 'payment/icon/vmj.png';
		$security      = DIR_WS_CATALOG_IMAGES . 'payment/icon/security.jpg';
		$notesTitle    = __('Notes');
		$notesContent  = __('You are now connected to a secure payment site with certificate issued by VeriSign, Your payment details will be securely transmitted to the Bank for transaction authorization in full accordance with PCI standards.');

		$html = <<<HTML
<ul class="inside-payform inside-payform-gppay">
	<li class="field-card form-group">
        <div class="input-box">
            <input type="tel" class="form-control input-text required-entry creditcard" name="gppayment_card_number" id="gppayTxtCardNumber" maxLength="16" onkeyup="gppayCheckCardNumber();" oninput="gppayCheckCardNumber();" placeholder="$txtCardNumber" />
        </div>
        <span class="brand brand-card" id="gppayBrandCard"></span>
    </li>
    <li class="field-date form-group">
        <select class="form-control required-entry field-date-month" name="gppayment_card_month">$monthStr</select>
        <select class="form-control required-entry" name="gppayment_card_year">$yearStr</select>
    </li>
    <li class="field-cvv form-group">
        <div class="input-box">
            <input type="tel" class="form-control input-text required-entry digits" name="gppayment_card_cvv" id="txtCardCVV" minLength="3" maxLength="4" onkeyup="this.value=this.value.replace(/\D/g,'')" oninput="this.value=this.value.replace(/\D/g,'')" placeholder="$txtCVC"/>
        </div>
    </li>
    <li class="field-notes">
        <div class="title">$notesTitle</div>
        <div class="content"><p class="std">$notesContent</p></div>
        <img src="$security" />
    </li>
</ul>
<style type="text/css">
.inside-payform.inside-payform-gppay li.field-card,
.inside-payform.inside-payform-gppay li.field-date,
.inside-payform.inside-payform-gppay li.field-cvv {border: 1px solid #E4E4E4; margin-bottom: 10px; position: relative; height: 62px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
.inside-payform.inside-payform-gppay li .input-box {position: absolute; height: 32px; width: auto; left: 11px; top: 11px; right: 40px; margin-top: 5px; z-index: 1;}
.inside-payform.inside-payform-gppay li.field-date {border: none;}
.inside-payform.inside-payform-gppay li select {float:left;width:50%;padding:3px 0;border:none;box-shadow:none;color:#32325d;line-height:24px;font-size:16px;height:30px;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;}
.inside-payform.inside-payform-gppay li.field-date .form-control {padding: 16px 22px; height: 62px; line-height: 30px; width: 49%; margin-right: 2%; border: 1px solid #e4e4e4; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; border-radius: 0;}
.inside-payform.inside-payform-gppay li.field-date .form-control:last-child {margin-right: 0;}
.inside-payform.inside-payform-gppay li .form-control.valid {border: none !important; background: none !important;}
.inside-payform.inside-payform-gppay li.field-date .form-control.valid {border: 1px solid #e4e4e4 !important;}
.inside-payform.inside-payform-gppay input,
.inside-payform.inside-payform-gppay input:focus,
.inside-payform.inside-payform-gppay select,
.inside-payform.inside-payform-gppay select:focus {background-color: transparent; background-image: none; border: none; outline: 0 none;}
.inside-payform.inside-payform-gppay li input.input-text {color: #32325d; line-height: 30px; font-size: 16px; width: 100%; height: 30px; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; box-shadow: none;}
.inside-payform.inside-payform-gppay li .brand {position:absolute;top:11px;right:5px;display:block;margin-top:5px;width:32px;height:32px;background-repeat:no-repeat;background-position:center center;background-size:80%;z-index:2;}
.inside-payform.inside-payform-gppay li .brand.brand-card {background-image: url("$vmj"); top: 11px; margin-top: 5px;}
</style>
<script type="text/javascript">
function gppayCheckCardNumber(){
    var txtCardNumber = document.getElementById('gppayTxtCardNumber'),
        brandCard = document.getElementById('gppayBrandCard');

    txtCardNumber.value = txtCardNumber.value.replace(/\D/g, '');
    if ((/^[4]{1}/).test(txtCardNumber.value)) {
        brandCard.style.backgroundImage = 'url("$v")';
    } else if ((/^[5]{1}[1-5]{1}/).test(txtCardNumber.value)) {
        brandCard.style.backgroundImage = 'url("$m")';
    } else if ((/^[3]{1}[5]{1}/).test(txtCardNumber.value)) {
        brandCard.style.backgroundImage = 'url("$j")';
    } else if ((/^[3]{1}[47]{1}/).test(txtCardNumber.value)) {
        brandCard.style.backgroundImage = 'url("$a")';
    } else {
        brandCard.style.backgroundImage = 'url("$vmj")';
    }
}
</script>
HTML;

		return $html;
	}

	function after()
	{
		global $message_stack, $error, $current_page;

		$gppayment_card_number = $_POST['gppayment_card_number'];
		$gppayment_card_month  = $_POST['gppayment_card_month'];
		$gppayment_card_year   = $_POST['gppayment_card_year'];
		$gppayment_card_cvv    = $_POST['gppayment_card_cvv'];

		if (strlen($gppayment_card_number) < 1) {
			$error = true;
			$message_stack->add($current_page, __('"Card Number" is a required value. Please enter the card number.'));
		} elseif (!validate_creditcard($gppayment_card_number)) {
			$error = true;
			$message_stack->add($current_page, __('"Card Number" is not a valid card number.'));
		}

		if (strlen($gppayment_card_month) < 1
			|| strlen($gppayment_card_month) < 1) {
			$error = true;
			$message_stack->add($current_page, __('"Expiry Date" is a required value. Please enter the expiry date.'));
		}

		if (strlen($gppayment_card_cvv) < 1) {
			$error = true;
			$message_stack->add($current_page, __('"CVC/CVV2" is a required value. Please enter the cvc/cvv2.'));
		}

		if ($error == false) {
			$_SESSION['gppayment_card'] = array(
				'gppayment_card_number' => $gppayment_card_number,
				'gppayment_card_month'  => $gppayment_card_month,
				'gppayment_card_year'   => $gppayment_card_year,
				'gppayment_card_cvv'    => $gppayment_card_cvv,
			);
		}
	}

	function process($payment)
	{
		/*
		 *  GP_MTID	1627523432
		 *  GP_BSID	1627523432001
		 *  GP_URL	www.test.com
		 * 	GP_SIGNKEY	DOY6j4CsDoeMPqw2
		 * */

		global $orderInfo, $orderProductInfo, $currencies, $message_stack;
		$pay_method_info = array();

		if ($payment->get_is_inside() == 0) {
			if (!isset($_POST['gppayment_card_number'])) {
				redirect(href_link('gppayment_process', '', 'SSL'));
			} else {
				$pay_method_info = array(
					'CARDNO'   => $_POST['gppayment_card_number'],
					'EN_MONTH' => $_POST['gppayment_card_month'],
					'EN_YEAR'  => $_POST['gppayment_card_year'],
					'CVV'      => $_POST['gppayment_card_cvv'],
					'FT_NAME'  => $orderInfo['billing']['firstname'],
					'LT_NAME'  => $orderInfo['billing']['lastname'],
					'BGDESC'   => ''
				);
			}
		} else {
			$pay_method_info = array(
				'CARDNO'   => $_SESSION['gppayment_card']['gppayment_card_number'],
				'EN_MONTH' => $_SESSION['gppayment_card']['gppayment_card_month'],
				'EN_YEAR'  => $_SESSION['gppayment_card']['gppayment_card_year'],
				'CVV'      => $_SESSION['gppayment_card']['gppayment_card_cvv'],
				'FT_NAME'  => $orderInfo['billing']['firstname'],
				'LT_NAME'  => $orderInfo['billing']['lastname'],
				'BGDESC'   => ''
			);
		}

		$riskInfo   = array();
		$prductInfo = array();

		foreach ($orderProductInfo as $key => $value) {
			$price                         = $currencies->get_price($value['price'], $orderInfo['currency']['code'], $orderInfo['currency']['value']);
			$productUrl                    = href_link(FILENAME_PRODUCT, 'pID=' . $value['product_id']);
			$prductInfo[$key]['QTY']       = $value['qty'];
			$prductInfo[$key]['NAME']      = $value['name'];
			$prductInfo[$key]['PRICE']     = $price;
			$prductInfo[$key]['URL']       = $productUrl;
			$prductInfo[$key]['ATTRIBUTE'] = isset($value['attribute']) ? $value['attribute'] : '';
			$prductInfo[$key]['IMAGE']     = isset($value['image']) ? base64_encode($value['image']) : '';

			$riskInfo[$key]['SKU']       = $value['sku'];
			$riskInfo[$key]['NAME']      = $value['name'];
			$riskInfo[$key]['PRICE']     = $price;
			$riskInfo[$key]['QTY']       = $value['qty'];
			$riskInfo[$key]['URL']       = $productUrl;
			$riskInfo[$key]['ATTRIBUTE'] = isset($value['attribute']) ? $value['attribute'] : '';
			$riskInfo[$key]['ISGIFT']    = isset($value['is_gift']) ? $value['is_gift'] : '';
			$riskInfo[$key]['ISVIRTUAL'] = isset($value['is_virtual']) ? $value['is_virtual'] : '';
		}

		$bcountry_iso = get_country_iso($orderInfo['billing']['country_id']);
		$scountry_iso = get_country_iso($orderInfo['shipping']['country_id']);
		$risk_info    = array(
			'AT_FACTOR' => '',
			'RY_NUM'    => '',
			'TRADE'     => array('CODE' => '', 'ITEM' => ''),
			'DEVICE'    => array(
				'FR_PRINTID' => 'XCookie',
				'UR_AGENT'   => $_SERVER['HTTP_USER_AGENT'],
				'AT_LANG'    => 'EN'
			),
			'CUST'      => array(
				'RR_USERID' => '',
				'IP'        => !empty($_POST['client_ip']) ? $_POST['client_ip'] : get_ip_address(),
				'EMAIL'     => $orderInfo['customer']['email_address'],
				'PHONE'     => $orderInfo['billing']['telephone'],
				'RN_TIME'   => '',
				'LEVEL'     => '',
				'LT_SGTIME' => ''
			),
			'GOODS'     => $riskInfo,
			'BURIED'    => array(array('code' => '', 'item' => '')),
			'SHIP'      => array(
				'FTNAME'         => $orderInfo['shipping']['firstname'],
				'LTNAME'         => $orderInfo['shipping']['lastname'],
				'EMAIL'          => $orderInfo['customer']['email_address'],
				'PHONE'          => $orderInfo['shipping']['telephone'],
				'ADDRESS'        => trim($orderInfo['shipping']['street_address'] . ' ' . $orderInfo['shipping']['suburb']),
				'CITY'           => $orderInfo['shipping']['city'],
				'STATE'          => $orderInfo['shipping']['region'],
				'POSTCODE'       => $orderInfo['billing']['postcode'],
				'COUNTRY'        => $scountry_iso['iso_code_2'],
				'ADD_MODIFYTIME' => '',
				'PE_MODIFYTIME'  => ''
			),
			'BILL'      => array(
				'FT_NAME'  => $orderInfo['billing']['firstname'],
				'LT_NAME'  => $orderInfo['billing']['lastname'],
				'EMAIL'    => $orderInfo['customer']['email_address'],
				'PHONE'    => $orderInfo['billing']['telephone'],
				'ADDRESS'  => trim($orderInfo['billing']['street_address'] . ' ' . $orderInfo['billing']['suburb']),
				'CITY'     => $orderInfo['billing']['city'],
				'STATE'    => $orderInfo['billing']['region'],
				'POSTCODE' => $orderInfo['billing']['postcode'],
				'COUNTRY'  => $bcountry_iso['iso_code_2']
			)
		);

		$request_type = (((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')))
						 || (isset($_SERVER['HTTP_X_FORWARDED_BY']) && strpos(strtoupper($_SERVER['HTTP_X_FORWARDED_BY']), 'SSL') !== false)
						 || (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && (strpos(strtoupper($_SERVER['HTTP_X_FORWARDED_HOST']), 'SSL') !== false
																		  || strpos(strtoupper($_SERVER['HTTP_X_FORWARDED_HOST']), str_replace('https://', '', HTTPS_SERVER)) !== false))
						 || (isset($_SERVER['SCRIPT_URI']) && strtolower(substr($_SERVER['SCRIPT_URI'], 0, 6)) == 'https:')
						 || (isset($_SERVER["HTTP_SSLSESSIONID"]) && $_SERVER["HTTP_SSLSESSIONID"] != '')
						 || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443')) ? 'SSL' : 'NONSSL';
		$orderId      = put_orderNO($orderInfo['order_id']);
		// 生成秘钥
		$key    = openssl_encrypt($_COOKIE['PHPSESSID'], 'DES-EDE3', 'gppayment');
		$secret = urlencode(base64_encode($key));
		$data   = array(
			'GP_VERSION'    => '3.0',
			'GP_MTID'       => trim($payment->get_account()),
			'GP_BSID'       => trim($payment->get_mark1()),
			'GP_ASTPYE'     => 's2s',
			'GP_ORNUMBER'   => $orderId,
			'GP_TSTYPE'     => 'authorization',
			'GP_TSCHANNEL'  => 'cc',
			'GP_METHOD'     => 'normal',
			'GP_TSTIMEOUT'  => '1440', // 订单有效时长
			'GP_URL'        => $_SERVER['HTTP_HOST'],
			'GP_CURRENCY'   => $orderInfo['currency']['code'],
			'GP_AMOUNT'     => $currencies->get_price($orderInfo['order_total'], $orderInfo['currency']['code'], $orderInfo['currency']['value']),
			'GP_SECURRENCY' => 'USD',
			'GP_PTINFO'     => json_encode($prductInfo),
			'GP_METHODINFO' => json_encode($pay_method_info),
			'GP_COUNTRY'    => $bcountry_iso['iso_code_2'],
			'GP_LANG'       => '',
			'GP_TERTYPE'    => '0',
			'GP_RKINFO'     => json_encode($risk_info),
			'GP_SNCODE'     => '',
			'GP_LOGO'       => '',
			'GP_DCC'        => '',
			'GP_NYURL'      => HTTPS_SERVER . '/notify_gppayment.php',
			'GP_RTURL'      => href_link(FILENAME_CHECKOUT_RESULT, 'orderID=' . $orderId . '&s_order_id=' . $secret, 'SSL'),
			'GP_RQRESERVED' => $orderId,
			'GP_RESERVED'   => '',
			'GP_SNTYPE'     => 'MD5',
		);

		$key             = trim($payment->get_md5key());
		$md5Str          = $data['GP_MTID'] . $data['GP_BSID'] . $data['GP_ORNUMBER'] . $data['GP_TSTYPE'] . $data['GP_TSCHANNEL'] . $data['GP_METHOD'] . $data['GP_URL'] . $data['GP_CURRENCY'] . $data['GP_AMOUNT'] . $data['GP_SECURRENCY'] . $key;
		$data['GP_SIGN'] = md5($md5Str);
		$resArr          = json_decode($this->_post($payment->get_submit_url(), $data), true);

		if (!is_array($resArr)) {
			$resArr = json_decode($this->_post($payment->get_submit_url(), $data), true);
			if (!is_array($resArr)) {
				$message_stack->add_session('shopping_cart', __('System Error.'), 'error');
				redirect(href_link(FILENAME_SHOPPING_CART, '', 'SSL'));
			}
		}

		if (isset($resArr['GP_ERROR']) && $resArr['GP_ERROR']) {
			$message_stack->add_session('shopping_cart', $resArr['GP_MSG'], 'error');
			redirect(href_link(FILENAME_SHOPPING_CART, '', 'SSL'));
		}

		if (isset($resArr['GP_PAYRESP'])) {
			$redirectData = json_decode($resArr['GP_PAYRESP'], true);

			// 输出表单
			if ($redirectData['GP_RTMETHOD'] == '2') {
				echo $redirectData['GP_RTPARAM'];
				die;
			}
		}

		$capResArr = $this->_capture($resArr, $payment);
		$result    = array(
			'authRess' => json_encode($resArr),
			'capRess'  => json_encode($capResArr)
		);

		$form = '<form method="post" action="' . href_link(FILENAME_CHECKOUT_RESULT, '', 'SSL') . '" id="gppayment" name="gppayment" target="_top">' . "\n";

		foreach ($result as $key => $val) {
			$form .= '<input type="hidden" value="' . urlencode($val) . '" name="' . $key . '">' . "\n";
		}

		$form .= '</form>' . "\n";
		$form .= '<script type="text/javascript">' . "\n";
		$form .= 'window.onload = function() {' . "\n";
		$form .= 'document.getElementById(\'gppayment\').submit();' . "\n";
		$form .= '}' . "\n";
		$form .= '</script>' . "\n";

		echo $form;
		die;
	}

	function result($payment)
	{
		global $message_stack;
		$_POST = array_map('urldecode', $_POST);
		$_POST = array_map('urldecode', $_POST);

		// 渠道抛送
		if (isset($_GET['orderID']) && !empty($_GET['orderID'])) {
			$_POST['authRess'] = json_encode($_POST);
			$_POST['capRess']  = json_encode($this->_capture($_POST, $payment));
		}

		if (!isset($_POST['authRess'])) {
			$message_stack->add_session('checkout_result', __('Timeout.'), 'error');
			redirect(href_link(FILENAME_CHECKOUT_RESULT, '', 'SSL'));
		}

		$resArr  = json_decode($_POST['authRess'], true);
		$signArr = $resArr;

		if (!$this->validateSign($signArr, $payment)) {
			$message_stack->add_session('checkout_result', __('Signature Error.'), 'error');
			redirect(href_link(FILENAME_CHECKOUT_RESULT, '', 'SSL'));
		}

		// 存在cap信息时覆盖部分内容
		if (isset($_POST['capRess']) && !empty($_POST['capRess'])) {
			$capArr = json_decode($_POST['capRess'], true);

			if (!empty($capArr)) {
				$resArr['GP_RPCODE'] = $capArr['GP_RPCODE'];
				$resArr['GP_RPMSG']  = $capArr['GP_RPMSG'];
			}
		}

		$cardBill = '';

		if (isset($resArr['GP_RESERVED']) && !empty($resArr['GP_RESERVED'])) {
			$reserved = json_decode($resArr['GP_RESERVED'], true);
			$cardBill = isset($reserved['GP_CARDINFO']['GP_CARDBILL']) ? $reserved['GP_CARDINFO']['GP_CARDBILL'] : '';
		}

		$result = array(
			'order_status_id' => isset($resArr['GP_RPCODE']) && $resArr['GP_RPCODE'] == '0000' ? '3' : '4',
			'remarks'         => isset($resArr['GP_RPMSG']) ? $resArr['GP_RPMSG'] : '',
			'billing'         => $cardBill
		);

		return $result;
	}

	function _post($url, $data)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	function validateSign($signArr, $payment)
	{
		$resSign = $signArr['GP_SIGN'];
		unset($signArr['GP_SIGN']);
		$resStr = implode('', $signArr) . trim($payment->get_md5key());
		return $resSign == md5($resStr) ? true : false;
	}

	private function _capture($data, $payment)
	{
		global $message_stack;
		$returnArr = $data;

		if ($data['GP_TSTYPE'] == 'authorization' && $data['GP_RPCODE'] == '0000') {
			// 请求报文
			$commitArr            = array(
				'GP_VERSION'    => '3.0',
				'GP_MTID'       => trim($payment->get_account()),
				'GP_BSID'       => trim($payment->get_mark1()),
				'GP_ASTPYE'     => 's2s',
				'GP_TSCHANNEL'  => '',
				'GP_OLORDERID'  => $data['GP_ORDERID'],
				'GP_TSTYPE'     => 'capture',
				'GP_AMOUNT'     => '',
				'GP_NYURL'      => '',
				'GP_RQRESERVED' => '',
				'GP_RESERVED'   => '',
				'GP_SNTYPE'     => 'MD5',
			);
			$md5Str               = implode('', $commitArr) . trim($payment->get_md5key());
			$commitArr['GP_SIGN'] = md5($md5Str);
			$res                  = $this->_post($payment->get_submit_url(), $commitArr);

			if (empty($res)) {
				$res = $this->_post($payment->get_submit_url(), $commitArr);

				if (empty($res)) {
					$message_stack->add_session('shopping_cart', __('Capture Error.'), 'error');
					redirect(href_link(FILENAME_SHOPPING_CART, '', 'SSL'));
				}
			}

			$returnArr = json_decode($res, true);
		}

		return $returnArr;
	}
}

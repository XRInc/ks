<?php
/**
 * payment pp.php
 */
class pp
{
	function before()
	{
		$monthStr =  '<option value="">' . __('Month') . '</option>';
		for ($i = 1; $i <= 12; $i++) {
			$monthStr .= '<option value="' . str_pad($i, 2, '0', STR_PAD_LEFT) . '">' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>';
		}

		$year    = date('Y');
		$yearStr = '<option value="">' . __('Year') . '</option>';
		for ($i = 0; $i < 25; $i++) {
			$yearStr .= '<option value="' . $year + $i . '">' . ($year + $i) . '</option>';
		}

		$txtCardNumber             = __('Credit Card Number');
		$txtExpirationDate         = __('Expiration Date');
		$txtCardVerificationNumber = __('Card Verification Number');

		$html = <<<HTML
<ul>
	<li class="fields">
		<label class="required"><em>*</em>$txtCardNumber</label>
		<div class="input-box">
			<input type="text" style="width: 98%;" class="input-text required-entry creditcard" onfocus="$('#pp').click();" name="pp_card[number]" maxlength="16" />
		</div>
	</li>
	<li class="fields">
		<label class="required"><em>*</em>$txtExpirationDate</label>
		<div class="input-box">
			<select class="required-entry" style="width: 45%;" name="pp_card[month]" onfocus="$('#pp').click();">$monthStr</select>
		</div>
	</li>
	<li class="fields">
		<div class="input-box">
			<select class="required-entry" style="width: 45%;" name="pp_card[year]" onfocus="$('#pp').click();">$yearStr</select>
		</div>
	</li>
	<li class="fields">
		<label class="required"><em>*</em>$txtCardVerificationNumber</label>
		<div class="input-box">
			<input type="password" class="input-text required-entry digits" name="pp_card[cvv]" onfocus="$('#pp').click();" maxlength="3" style="width:38%;" />
			<img src="images/payment/cvv.gif" />
		</div>
	</li>
</ul>
HTML;

		return $html;
	}

	function after()
	{
		global $message_stack, $error, $current_page;

		if (isset($_POST['pp_card'])) {
			$pp_card = $_POST['pp_card'];
			if (strlen($pp_card['number']) < 1) {
				$error = true;
				$message_stack->add($current_page, __('"Card Number" is a required value. Please enter the card number.'));
			} elseif (!validate_creditcard($pp_card['number'])) {
				$error = true;
				$message_stack->add($current_page, __('"Card Number" is not a valid card number.'));
			}
			if (strlen($pp_card['month']) < 1
				|| strlen($pp_card['year']) < 1) {
				$error = true;
				$message_stack->add($current_page, __('"Expiry Date" is a required value. Please enter the expiry date.'));
			}
			if (strlen($pp_card['cvv']) < 1) {
				$error = true;
				$message_stack->add($current_page, __('"CVC/CVV2" is a required value. Please enter the cvc/cvv2.'));
			}
			if ($error==true) {
				//nothing
			} else {
				$_SESSION['pp_card'] = array(
					'number' => $pp_card['number'],
					'month'  => $pp_card['month'],
					'year'   => $pp_card['year'],
					'cvv'    => $pp_card['cvv'],
				);
			}
		}
	}

	function process($payment)
	{
		global $orderInfo, $orderProductInfo, $currencies;

		if ($payment->get_is_inside() == 0
			&& !isset($_POST['pp_card_number'])) {
			redirect(href_link('pp_process', '', 'SSL'));
		}

		$data['merchantMID'] = trim($payment->get_account());
		$cardNumber          = $payment->get_is_inside() == 1 ? $_SESSION['pp_card']['number'] : $_POST['pp_card_number'];
		$cardMonth           = $payment->get_is_inside() == 1 ? $_SESSION['pp_card']['month'] : $_POST['pp_card_month'];
		$cardYear            = $payment->get_is_inside() == 1 ? $_SESSION['pp_card']['year'] : $_POST['pp_card_year'];
		$cardCvv             = $payment->get_is_inside() == 1 ? $_SESSION['pp_card']['cvv'] : $_POST['pp_card_cvv'];

		switch ($cardNumber{0}) {
			case '5':
				$data['newcardtype'] = 'Master';
			break;
			case '3':
				$data['newcardtype'] = 'jcb';
			break;
			default:
				$data['newcardtype'] = 'visa';
			break;
		}

		$data['cardnum'] = base64_encode($cardNumber);
		$data['month']   = base64_encode($cardMonth);
		$data['year']    = base64_encode($cardYear);
		$data['cvv2']    = base64_encode($cardCvv);

		$data['cardbank']  = '';
		$data['BillNo']    = put_orderNO($orderInfo['order_id']);
		$data['Amount']    = $currencies->get_price($orderInfo['order_total'], $orderInfo['currency']['code'], $orderInfo['currency']['value']);
		$data['Currency']  = $orderInfo['currency']['code'];
		$data['Language']  = 'en';
		$data['ReturnURL'] = href_link(FILENAME_CHECKOUT_RESULT, '', 'SSL');
		$data['website']   = $_SERVER['HTTP_HOST'];
		$data['ipAddr']    = get_ip_address();
		$data['products']  = implode(',', array_column($orderProductInfo, 'name'));

		$data['firstname'] = $orderInfo['billing']['firstname'];
		$data['lastname']  = $orderInfo['billing']['lastname'];
		$data['email']     = $orderInfo['customer']['email_address'];
		$data['phone']     = $orderInfo['billing']['telephone'];
		$data['zipcode']   = $orderInfo['billing']['postcode'];
		$data['address']   = trim($orderInfo['billing']['street_address'] . ' ' . $orderInfo['billing']['suburb']);
		$data['city']      = $orderInfo['billing']['city'];
		$data['state']     = $orderInfo['billing']['region'];
		$country_iso       = get_country_iso($orderInfo['billing']['country_id']);
		$data['country']   = $country_iso['iso_code_2'];

		$data['shippingFirstName'] = $orderInfo['shipping']['firstname'];
		$data['shippingLastName']  = $orderInfo['shipping']['lastname'];
		$data['shippingEmail']     = $orderInfo['customer']['email_address'];
		$data['shippingPhone']     = $orderInfo['shipping']['telephone'];
		$data['shippingZipcode']   = $orderInfo['shipping']['postcode'];
		$data['shippingAddress']   = trim($orderInfo['shipping']['street_address'] . ' ' . $orderInfo['shipping']['suburb']);
		$data['shippingCity']      = $orderInfo['shipping']['city'];
		$data['shippingState']     = $orderInfo['shipping']['region'];
		$country_iso               = get_country_iso($orderInfo['shipping']['country_id']);
		$data['shippingCountry']   = $country_iso['iso_code_2'];

		$MD5key       = trim($payment->get_md5key());
		$signStr      = $data['merchantMID'] . $data['BillNo'] . $data['Currency'] . $data['Amount'] . 'en' . $data['ReturnURL'] . $MD5key;
		$data['HASH'] = strtoupper(md5($signStr));

		if ($payment->get_is_inside() == 1){
			$_SESSION['pp_card'] = null;
			unset($_SESSION['pp_card']);
		}

		$result = $this->_post($payment->get_submit_url(), $data);
		$result = json_decode($result, true);

		if (!is_array($result)) {
			$result = $this->_post($payment->get_submit_url(), $data);
			$result = json_decode($result, true);
			if (!is_array($result)) {
				redirect(href_link(FILENAME_CHECKOUT_RESULT, '', 'SSL'));
			}
		}

		if ($result['error'] == true) {
			redirect(href_link(FILENAME_CHECKOUT_RESULT, '', 'SSL'));
		} else {
			redirect(
				href_link(
					FILENAME_CHECKOUT_RESULT,
					'StatusCode=' . $result['Succeed'],
					'SSL'
				)
			);
		}
	}

	function result()
	{
		$order_status = 4;

		if (isset($_GET['StatusCode']) && $_GET['StatusCode'] == '88') {
			$order_status = 3;
		}

		return $order_status;
	}

	function _post($url, $data)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch ,CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch ,CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}
}

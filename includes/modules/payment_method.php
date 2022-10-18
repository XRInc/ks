<?php
/**
 * modules payment_method.php
 */
$sql = "SELECT payment_method_id, code, name,
			   description, discount, is_default, is_inside, is_shield, is_pchidden,order_max_amount, mark3
		FROM   " . TABLE_PAYMENT_METHOD . "
		WHERE  status = 1
		ORDER BY sort_order";
$result = $db->Execute($sql);
$paymentMethodList = array();
$grand_total = $shoppingCart['subtotal'] - $shoppingCart['discount'] - $shoppingCart['coupon_discount'] + $shippingMethod['fee'] + $shippingMethod['insurance_fee'];
$mobileDetect = new Mobile_Detect();
while (!$result->EOF) {
	if (($result->fields['is_shield'] == 1 && stristr(HTTP_ACCEPT_LANGUAGE, 'zh')) || (!$mobileDetect->isMobile() && $result->fields['is_pchidden'] == 1)) {
		//nothing
	} else {
        if ($result->fields['order_max_amount'] == '0' || $result->fields['order_max_amount'] > $grand_total) {
            if ($result->RecordCount() == 1) {
                $is_default = 1;
            } elseif (isset($paymentMethod)) {
                $is_default = $paymentMethod['payment_method_id'] == $result->fields['payment_method_id'] ? 1 : 0;
            } else {
                $is_default = $result->fields['is_default'];
            }

            $paymentMethodList[$result->fields['payment_method_id']] = array(
                'code' => $result->fields['code'],
                'name' => $result->fields['name'],
                'description' => $result->fields['mark3'] == 'offline' ? '<img src="images/payment/' . $result->fields['code'] . '.gif" />' : $result->fields['description'],
                'discount' => $result->fields['discount'],
                'is_default' => $is_default,
                'is_inside' => $result->fields['is_inside']
            );
        }
	}
	$result->MoveNext();
}

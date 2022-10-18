<?php
if (empty($_POST)) {
	die('null');
}
require('includes/application_top.php');
$_POST = array_map('urldecode', $_POST);

if (!empty($_POST)) {
	require_once(DIR_FS_CATALOG_CLASSES . 'payment_method.php');
	$payment = new payment_method('gppayment');

	if (validateSign($_POST, $payment)) {

		// 获取订单信息
		$order_id  = get_orderNO($_POST['GP_ORNUMBER']);
		$orderInfo = get_order($order_id);

		// 获取订单产品
		$orderProductInfo = get_order_product($order_id);

		// 订单成功则退出
		if (in_array($orderInfo['order_status_id'], array('3', '6', '7'))) {
			exit('No action required');
		}

		if (isset($_POST['GP_RPCODE']) && $_POST['GP_RPCODE'] == '0000') {
			$orderStatusId = 3;
			$orderRemarks = 'Notify: Transaction is approved';
		} else {
			$orderStatusId = 4;
			$orderRemarks = 'Notify: Invalid Transaction';
		}

		// 更新数据
		// send confirm mail
		if ($orderInfo['send_confirm_mail'] == 0
			&& send_confirm_mail($orderInfo, $orderProductInfo, $orderStatusId)) {
			$orderInfo['send_confirm_mail'] = 1;
		}

		$sql = "UPDATE " . TABLE_ORDERS . " SET order_status_id = :orderStatusID, send_confirm_mail = :sendConfirmMail WHERE order_id = :orderID";
		$sql = $db->bindVars($sql, ':orderStatusID', $orderStatusId, 'integer');
		$sql = $db->bindVars($sql, ':sendConfirmMail', $orderInfo['send_confirm_mail'], 'integer');
		$sql = $db->bindVars($sql, ':orderID', $orderInfo['order_id'], 'integer');
		$db->Execute($sql);

		//order_status_history
		$sqlDataArray = array(
			array('fieldName' => 'order_id', 'value' => $orderInfo['order_id'], 'type' => 'integer'),
			array('fieldName' => 'order_status_id', 'value' => $orderStatusId, 'type' => 'integer'),
			array('fieldName' => 'remarks', 'value' => $orderRemarks, 'type' => 'string'),
			array('fieldName' => 'date_added', 'value' => 'NOW()', 'type' => 'noquotestring')
		);
		$db->perform(TABLE_ORDER_STATUS_HISTORY, $sqlDataArray);

		//product in_stock
		foreach ($orderProductInfo as $_product) {
			$sql = "UPDATE " . TABLE_PRODUCT . " SET ordered = ordered+:productQty WHERE product_id = :productID";
			$sql = $db->bindVars($sql, ':productQty', $_product['qty'], 'integer');
			$sql = $db->bindVars($sql, ':productID', $_product['product_id'], 'integer');
			$db->Execute($sql);
			$sql = "UPDATE " . TABLE_PRODUCT . " SET in_stock = 0 WHERE product_id = :productID AND in_stock = 1 AND stock_qty > 0 AND ordered >= stock_qty";
			$sql = $db->bindVars($sql, ':productID', $_product['product_id'], 'integer');
			$db->Execute($sql);
		}

		die('ok');
	} else {
		die('Notify: Authentication failed');
	}
}

function validateSign($signArr, $payment)
{
	$resSign = $signArr['GP_SIGN'];
	unset($signArr['GP_SIGN']);
	$resStr = implode('', $signArr) . trim($payment->get_md5key());
	return $resSign == md5($resStr) ? true : false;
}

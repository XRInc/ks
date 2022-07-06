<?php
/**
 * checkout_notify header_php.php
 */

$_POST         = array_map('urldecode', $_POST);

// 获取订单信息
$orderId       = get_orderNO($_POST['order_no']);
$orderInfo     = get_order($orderId);

// 获取订单产品
$orderProductInfo = get_order_product($orderId);

// 订单成功则退出
if (in_array($orderInfo['order_status_id'], array('3', '6', '7'))) {
	exit('ok');
}

// 获取支付方式参数
require(DIR_FS_CATALOG_CLASSES . 'payment_method.php');
$payment = new payment_method($orderInfo['payment_method']['code']);
$merchant_id = trim($payment->get_account());
$app_id      = trim($payment->get_mark1());
$api_secret  = trim($payment->get_md5key());

// 签名验证
$signature       = hash('sha256', $merchant_id . $app_id . $_POST['order_no'] . $_POST['gateway_order_no'] . $_POST['order_currency'] . $_POST['order_amount'] . $_POST['order_status'] . $api_secret);
$signatureReturn = $_POST['signature'];

if ($signature != $signatureReturn) {
	die();
}

// 只有成功或者失败才需要修改订单状态
if ($_POST['order_status'] == '2') {
	$orderStatusId = 3;
	echo 'Success';
} elseif ($_POST['order_status'] == '3') {
	$orderStatusId = 4;
	echo 'Failure';
} else {
	die();
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
	array('fieldName' => 'remarks', 'value' => 'ipasspay notify', 'type' => 'string'),
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

exit();

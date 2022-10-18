<?php
/**
 * init_includes init_session.php
 */
require(DIR_FS_CATALOG_CLASSES . 'shopping_cart.php');

session_save_path(DIR_FS_CATALOG_CACHE);

if (isset($_GET['s_order_id']) && !empty($_GET['s_order_id'])) {
	// 解密
	$secret    = urldecode(base64_decode($_GET['s_order_id']));
	unset($_GET['s_order_id']);
	$sessionId = openssl_decrypt($secret, 'DES-EDE3', 'gppayment');
	session_id($sessionId);
	session_start();
} else {
	session_start();
	$sessionId = session_id();
}

setcookie(session_name(), $sessionId, time() + 1200, "/");
if (!isset($_SESSION['securityToken'])) {
	$_SESSION['securityToken'] = md5(uniqid(rand(), true));
}

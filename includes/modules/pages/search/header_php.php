<?php
/**
 * search header_php.php
 */
$_GET['q'] = isset($_GET['q']) ? output_string_protected(trim($_GET['q'])) : '';
if (not_null($_GET['q'])) {
	$searchVars = array(
		'jj watt' => 'J.J. Watt'
	);
	foreach ($searchVars as $key => $val) {
		$_GET['q'] = str_replace($key, $val, $_GET['q']);
	}
	$search = array_filter(explode(' ', $_GET['q']));
	$productListQuery = "SELECT p.product_id, p.name, p.short_description, p.image, p.price,
								p.specials_price, p.specials_expire_date, p.in_stock
						 FROM   " . TABLE_PRODUCT . " p
						 WHERE  p.status = 1
						 AND    (p.sku LIKE '%:q%' OR p.name LIKE '%:q%'";
	$productListQuery = $db->bindVars($productListQuery, ':q', $_GET['q'], 'noquotestring');
	$skuList = array();
	$nameList = array();
	if (count($search) > 1) {
		foreach ($search as $val) {
			$skuList[] = $db->bindVars("p.sku LIKE '%:val%'", ':val', $val, 'noquotestring');
			$nameList[] = $db->bindVars("p.name LIKE '%:val%'", ':val', $val, 'noquotestring');
		}
	}
	$productListQuery .= (count($skuList) > 0 ? " OR (" . implode(" AND ", $skuList) . ')' : '');
	$productListQuery .= (count($nameList) > 0 ? " OR (" . implode(" AND ", $nameList) . ')' : '');
	$productListQuery .= ")";
	//subcategories
	if (isset($_GET['cID']) && not_null($_GET['cID'])) {
		$subcategories = $category_tree->getSubcategories('', $_GET['cID']);
		if (count($subcategories) > 0) {
			$sql = "SELECT product_id FROM " . TABLE_PRODUCT_TO_CATEGORY . " WHERE category_id IN (:categoryIDS)";
			$sql = $db->bindVars($sql, ':categoryIDS', implode(',', $subcategories), 'noquotestring');
		} else {
			$sql = "SELECT product_id FROM " . TABLE_PRODUCT_TO_CATEGORY . " WHERE category_id = :categoryID";
			$sql = $db->bindVars($sql, ':categoryID', $_GET['cID'], 'integer');
		}
		$productListQuery .= " AND p.product_id IN ({$sql})";
	}

	$sql = "SELECT COUNT(*) AS total
			FROM   " . TABLE_POPULAR_SEARCH . "
			WHERE  search = :search";
	$sql = $db->bindVars($sql, ':search', $_GET['q'], 'string');
	$popularResult = $db->Execute($sql);
	if ($popularResult->fields['total']>0) {
		$db->Execute("UPDATE " . TABLE_POPULAR_SEARCH . " SET freq = freq + 1 WHERE search = '". db_input($_GET['q']) . "'");
	} else {
		$db->Execute("INSERT INTO " . TABLE_POPULAR_SEARCH . " (search) VALUES ('" . db_input($_GET['q']) . "')");
	}
} else {
	$productListQuery = "SELECT p.product_id, p.name, p.short_description, p.image, p.price,
								p.specials_price, p.specials_expire_date, p.in_stock
						 FROM   " . TABLE_PRODUCT . " p
						 WHERE  p.product_id = 0";
}
//Breadcrumb
$breadcrumb->add(__('Search results for: "%s"', $_GET['q']), 'root');

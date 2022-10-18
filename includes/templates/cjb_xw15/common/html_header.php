<?php require(DIR_FS_CATALOG_MODULES . 'meta.php'); ?>
<!DOCTYPE html>
<html xml:lang="<?php echo STORE_LANGUAGE; ?>" lang="<?php echo STORE_LANGUAGE; ?>">
<head>
    <?php if (defined('TIKTOK_ID') && strlen(TIKTOK_ID) > 0) { ?>
        <!-- Tiktok Pixel Code -->
        <script>
            !function (w, d, t) {
                w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};

                <?php $ttId = explode(',',TIKTOK_ID); ?>
                <?php foreach ($ttId as $val) { ?>
                <?php if (!isset($val)) continue; ?>
                ttq.load('<?php echo $val; ?>');
                <?php } ?>

                ttq.page();
                <?php switch ($current_page) {
                case FILENAME_PRODUCT:
                    echo "ttq.track('ViewContent');";
                    break;
                case FILENAME_ACCOUNT:
                    echo isset($_GET['success']) ? "ttq.track('CompleteRegistration');" : "";
                    break;
                case 'checkout_result':
                    if (defined('ORDER_TEST_BTN') && ORDER_TEST_BTN == '1') {
                        echo "ttq.track('CompletePayment', {content_id: '" . $orderInfo['order_id'] . "', value: '" . $currencies->get_price($orderInfo['order_total'], $orderInfo['currency']['code'], $orderInfo['currency']['value']) . "', currency: '" . $orderInfo['currency']['code'] . "'});";
                    } else {
                        if ($orderInfo['order_status_id'] == 3
                            && !isset($_GET['order_token'])) {
                            echo "ttq.track('CompletePayment', {content_id: '" . $orderInfo['order_id'] . "', value: '" . $currencies->get_price($orderInfo['order_total'], $orderInfo['currency']['code'], $orderInfo['currency']['value']) . "', currency: '" . $orderInfo['currency']['code'] . "'});";
                        }
                    }
            } ?>
            }(window, document, 'ttq');
        </script>
        <!-- End Tiktok Pixel Code -->
    <?php } ?>
	<?php if (defined('FACEBOOK_ID') && strlen(FACEBOOK_ID) > 0) { ?>
	<!-- Facebook Pixel Code -->
	<script>
		!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');

		<?php $facebookId = explode(',',FACEBOOK_ID); ?>
		<?php foreach ($facebookId as $val) { ?>
		<?php if (!isset($val) || !is_numeric($val) || $val < 1) continue; ?>
			fbq('init', '<?php echo $val; ?>');
		<?php } ?>

			fbq('track', 'PageView');
			<?php switch ($current_page) {
				case FILENAME_PRODUCT:
					echo "fbq('track', 'ViewContent');";
					break;
				case FILENAME_ACCOUNT:
					echo isset($_GET['success']) ? "fbq('track', 'CompleteRegistration');" : "";
					break;
				case 'checkout_result':
					if ($orderInfo['order_status_id'] == 3
						&& !isset($_SESSION['facebook_purchase'])
					    && !isset($_GET['order_token'])) {
						echo "fbq('track', 'Purchase', {value: '" . $currencies->get_price($orderInfo['order_total'], $orderInfo['currency']['code'], $orderInfo['currency']['value']) . "', currency: '" . $orderInfo['currency']['code'] . "'});";
					}
					break;
			} ?>
		</script>
		<noscript>
			<?php foreach ($facebookId as $val) { ?>
				<?php if (!isset($val) || !is_numeric($val) || $val < 1) continue; ?>
				<img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?php echo $val; ?>&ev=PageView&noscript=1" />
			<?php } ?>
		</noscript>
		<!-- End Facebook Pixel Code -->
<?php } ?>
<title><?php echo $metaInfo['title']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="keywords" content="<?php echo $metaInfo['keywords']; ?>" />
<meta name="description" content="<?php echo $metaInfo['description']; ?>" />
<meta name="facebook-domain-verification" content="<?php echo $metaInfo['facebook-domain-verification']; ?>" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="renderer" content="webkit">
<link rel="icon" href="<?php echo DIR_WS_TEMPLATE; ?>favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo DIR_WS_TEMPLATE; ?>favicon.ico" type="image/x-icon" />
<base href="<?php echo (($request_type=='SSL')?HTTPS_SERVER:HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<link href="<?php echo $metaInfo['canonical']; ?>" rel="canonical" />
<?php // 加载当前模板css文件夹中所有名称为style*.css的样式文件 ?>
	<link rel="stylesheet" type="text/css" href="js/jquery/bootstrap-3.3.7/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="//at.alicdn.com/t/font_375202_rffro0w6xuutmx6r.css" />
<?php $directory_css = $template->get_template_dir('.css', DIR_WS_TEMPLATE, $current_page, 'css'); ?>
<?php $directory_array = $template->get_template_part($directory_css, '/^style/', '.css'); ?>
<?php foreach ($directory_array as $_file) { ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $directory_css . $_file; ?>" />
<?php } ?>
<?php //加载当前页面modules/pages/(当前页面)文件夹中所有名称为style*.css的样式文件 ?>
<?php $directory_array = $template->get_template_part($page_directory, '/^style/', '.css'); ?>
<?php foreach ($directory_array as $_file) { ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $code_page_directory . '/' . $_file; ?>" />
<?php } ?>
	<script type="text/javascript" src="js/jquery/jquery.js"></script>
	<script type="text/javascript" src="js/jquery/base.js"></script>
	<script type="text/javascript" src="js/jquery/validate.js"></script>
<?php if (STORE_LANGUAGE!='en') { ?>
    <script type="text/javascript" src="js/jquery/validate/messages_<?php echo STORE_LANGUAGE; ?>.js"></script>
<?php } ?>
	<script type="text/javascript" src="js/jquery/tabs.js"></script>
<?php //加载当前模板js文件夹中所有名称为jscript_*.js的脚本文件 ?>
<?php $directory_array = $template->get_template_part(DIR_WS_TEMPLATE_JS, '/^jscript_/', '.js'); ?>
<?php foreach ($directory_array as $_file) { ?>
	<script type="text/javascript" src="<?php echo DIR_WS_TEMPLATE_JS . $_file; ?>"></script>
<?php } ?>
<?php //加载当前页面modules/pages/(当前页面)文件夹中所有名称为jscript_*.js的脚本文件 ?>
<?php $directory_array = $template->get_template_part($page_directory, '/^jscript_/', '.js'); ?>
<?php foreach ($directory_array as $_file) { ?>
	<script type="text/javascript" src="<?php echo $code_page_directory . '/' . $_file; ?>"></script>
<?php } ?>
<?php //加载当前页面modules/pages/(当前页面)文件夹中所有名称为jscript_*.php的脚本文件 ?>
<?php $directory_array = $template->get_template_part($page_directory, '/^jscript_/', '.php'); ?>
<?php foreach ($directory_array as $_file) { ?>
	<?php require($page_directory . '/' . $_file); ?>
<?php } ?>
</head>

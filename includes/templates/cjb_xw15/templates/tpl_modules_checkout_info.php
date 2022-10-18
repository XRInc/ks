<form id="form-validate" method="post" action="<?php echo SHOPPING_CART_MODE == 0 ? href_link(FILENAME_SHOPPING_CART, '', 'SSL') : href_link(FILENAME_CHECKOUT, '', 'SSL'); ?>">
	<div class="no-display">
		<input type="hidden" value="<?php echo $_SESSION['securityToken']; ?>" name="securityToken" />
		<input type="hidden" value="process" name="action" />
	</div>
	<div id="checkout-steps" class="row">
		<ol class="col-wide opc col-sm-7 col-md-7 col-xs-12">
			<li class="section active" id="opc-billing">
				<?php require($template->get_template_dir('tpl_modules_checkout_billing.php', DIR_WS_TEMPLATE, $current_page, 'templates') . 'tpl_modules_checkout_billing.php'); ?>
			</li>
			<li class="section" id="opc-shipping">
				<?php require($template->get_template_dir('tpl_modules_checkout_shipping.php', DIR_WS_TEMPLATE, $current_page, 'templates') . 'tpl_modules_checkout_shipping.php'); ?>
			</li>
		</ol>
		<ol class="col-narrow opc col-sm-5 col-md-5 col-xs-12">
			<li class="section active">
				<?php require($template->get_template_dir('tpl_modules_checkout_shipping_method.php', DIR_WS_TEMPLATE, $current_page, 'templates') . 'tpl_modules_checkout_shipping_method.php'); ?>
			</li>
			<li class="section active">
				<?php require($template->get_template_dir('tpl_modules_checkout_payment_method.php', DIR_WS_TEMPLATE, $current_page, 'templates') . 'tpl_modules_checkout_payment_method.php'); ?>
			</li>
			<li class="section active" id="opc-order_review">
				<?php require($template->get_template_dir('tpl_modules_checkout_order_review.php', DIR_WS_TEMPLATE, $current_page, 'templates') . 'tpl_modules_checkout_order_review.php'); ?>
			</li>
		</ol>
	</div>
</form>
<style>
	.shoppingcartBody {position: relative;}
	.masking-layer {display: block; margin: 0 auto; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255, 255, 255,0.97); z-index: 9999; }
	.masking-layer div {width: 210px; height: 60px; position: absolute; top: 50%; left: 50%; margin-top: -30px; margin-left: -105px; font-size: 14px;}
	.masking-layer img {opacity: 0.7;}
</style>
<script type="text/javascript"><!--
function newAddress(prefix) {
	var select = $("#" + prefix + "-address-select");
	var from = $("#" + prefix + "-new-address-form");
	if (select.val()==""||select.length==0) {
		from.show();
	} else {
		from.hide();
	}
}
function same_as_billing()
{
	if ($("input:radio[name='use_for_shipping']:checked").val()==1) {
		$('#opc-shipping').removeClass("active");
	} else {
		$('#opc-shipping').addClass("active");
	}
}
same_as_billing();
newAddress('billing');
newAddress('shipping');
	$('.shoppingcartBody').append('<div class="masking-layer" style="display: none;"><div><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>load.gif?v1"/><br/><?php echo __('Processing, please wait...'); ?></div></div>');
	$('#form-validate').validate({
	submitHandler: function(form){
		if($('#form-validate').valid()) {
			$('html').addClass('noscroll');
			$('.masking-layer').show();
			$('.shoppingcartBody').on('touchmove', function (event) { event.preventDefault(); });
		}
        <?php if (defined('FACEBOOK_ID') && strlen(FACEBOOK_ID) > 0) { ?> fbq('track', 'InitiateCheckout'); <?php } ?>
        <?php if (defined('TIKTOK_ID') && strlen(TIKTOK_ID) > 0) { ?> ttq.track('InitiateCheckout'); <?php } ?>
        setTimeout(function(){form.submit();}, 1000);
	}
});
//--></script>

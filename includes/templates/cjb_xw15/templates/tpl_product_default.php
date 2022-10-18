<?php if ($message_stack->size('product') > 0) echo $message_stack->output('product'); ?>
<div class="product-view">
	<!--<div class="product-headimg">
		<img class="hidden-xs" src="<?php /*echo DIR_WS_TEMPLATE_IMAGES; */?>kendra-scott-buy-more-save-more-memorial-day-sale-dt.jpg" alt="">
		<img class="visible-xs" src="<?php /*echo DIR_WS_TEMPLATE_IMAGES; */?>kendra-scott-buy-more-save-more-memorial-day-sale-mobile.jpg" alt="">
	</div>-->
	<div class="row product-essential">
		<div class="visible-xs col-md-12">
			<div class="product-name">
				<h1><?php echo $productInfo['name']; ?></h1>
			</div>
		</div>
		<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 product-img-box">
			<div class="row">
				<?php require($template->get_template_dir('tpl_modules_additional_image.php', DIR_WS_TEMPLATE, $current_page, 'templates') . 'tpl_modules_additional_image.php'); ?>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 product-shop-box">
			<form id="form-validate" method="post" action="<?php echo href_link(FILENAME_PRODUCT, 'pID=' . $productInfo['product_id'] . '&action=add_product'); ?>">
				<div class="no-display">
					<input type="hidden" value="<?php echo $_SESSION['securityToken']; ?>" name="securityToken" />
					<input type="hidden" value="<?php echo $productInfo['product_id']; ?>" name="pID" />
				</div>
				<div class="hidden-xs">
					<div class="product-name">
						<h1><?php echo $productInfo['name']; ?></h1>
					</div>
				</div>
				<p class="sku"><?php echo __('Model'); ?>: <span><?php echo $productInfo['sku']; ?></span></p>
				<?php if ($productInfo['in_stock']==1) { ?>
					<p class="availability in-stock"><?php echo __('Availability'); ?>: <span><?php echo __('In stock'); ?></span></p>
				<?php } else { ?>
					<p class="availability out-of-stock"><?php echo __('Availability'); ?>: <span><?php echo __('Out of Stock'); ?></span></p>
				<?php } ?>
				<div class="price-box">
					<?php if ($productInfo['specials_price'] > 0) { ?>
						<p class="old-price">
							<span class="price-label"><?php echo __('Regular Price'); ?>:</span>
							<span class="price"><?php echo $currencies->display_price($productInfo['price']); ?></span>
						</p>
						<p class="specials-price">
							<span class="price-label"><?php echo __('Special Price'); ?>:</span>
							<span class="price"><?php echo $currencies->display_price($productInfo['specials_price']); ?></span>
						</p>
						<?php if ($productInfo['save_off']>0) { ?>
							<p class="save-off">
								<span class="price-label"><?php echo __('Save Off'); ?>:</span>
								<span class="price"><?php echo $productInfo['save_off']; ?>%</span>
							</p>
						<?php } ?>
					<?php } else { ?>
						<span class="regular-price">
                			<span class="price"><?php echo $currencies->display_price($productInfo['price']); ?></span>
                		</span>
					<?php } ?>
				</div>
				<?php if (not_null($productInfo['short_description'])) { ?>
					<div class="short-description">
						<h3><?php echo __('Quick Overview'); ?></h3>
						<div class="std"><?php echo $productInfo['short_description']; ?></div>
					</div>
					<div class="divider"></div>
				<?php } ?>
				<?php require($template->get_template_dir('tpl_modules_color.php', DIR_WS_TEMPLATE, $current_page, 'templates') . 'tpl_modules_color.php'); ?>
				<?php require($template->get_template_dir('tpl_modules_product_group.php', DIR_WS_TEMPLATE, $current_page, 'templates') . 'tpl_modules_product_group.php'); ?>
				<?php if (isset($productInfo['attribute']) && count($productInfo['attribute']) > 0) { ?>
					<?php require($template->get_template_dir('tpl_modules_attribute.php', DIR_WS_TEMPLATE, $current_page, 'templates') . 'tpl_modules_attribute.php'); ?>
				<?php } ?>
				<?php if ($productInfo['in_stock']==1) { ?>
					<div class="add-to-cart">
						<?php if ($productInfo['qty'] == true) { ?>
							<label for="qty"></label>
							<div class="num_mobile">
								<select id="qtySelect" class="qty form-control"  name="qty">
									<?php for ($i = 1; $i < 100; $i++) { ?>
										<option value="<?php echo $i ?>"><?php echo $i ?></option>
									<?php } ?>
								</select>
							</div>
						<?php } ?>
						<button type="submit" class="button btn-incart" title="<?php echo __('Add to Cart'); ?>"><span><span><?php echo __('Add to Cart'); ?></span></span></button>
					</div>
				<?php } ?>
			</form>
			<?php if (not_null($productInfo['description'])) { ?>
			<div class="description">
				<h2><?php echo __('details'); ?></h2>
				<div class="std" id="proDescription"><?php echo $productInfo['description']; ?></div>
			</div>
			<?php } ?>
		</div>
	</div>
	<div class="product-collateral">
		<?php require($template->get_template_dir('tpl_modules_related.php', DIR_WS_TEMPLATE, $current_page, 'templates') . 'tpl_modules_related.php'); ?>
		<?php require($template->get_template_dir('tpl_modules_also_purchased.php', DIR_WS_TEMPLATE, $current_page, 'templates') . 'tpl_modules_also_purchased.php'); ?>
		<?php require($template->get_template_dir('tpl_modules_review.php', DIR_WS_TEMPLATE, $current_page, 'templates') . 'tpl_modules_review.php'); ?>
	</div>
</div>
<script type="text/javascript"><!--
$(function () {
    $('#form-validate').validate({submitHandler:function(form){
        <?php if (defined('FACEBOOK_ID') && strlen(FACEBOOK_ID) > 0) { ?> fbq('track', 'AddToCart'); <?php } ?>
        <?php if (defined('TIKTOK_ID') && strlen(TIKTOK_ID) > 0) { ?> ttq.track('AddToCart'); <?php } ?>
        setTimeout(function(){form.submit();}, 1000);
    }});
	$('.cos-listView a.cosTab').tabs();
	// qty操作
	qtyAction();
});

function qtyAction(){
	$('.reduce').on('click',function () {var val = parseInt($('input.qty').val());if(val != 1){$('.qty').val(val-1);}});
	$('.pus').on('click',function () {var val = parseInt($('input.qty').val());$('.qty').val(val+1);});
	$('#qtySelect').on('change',function(){$('#qty').val($(this).val());});
	$('#qty').on('change',function(){$('#qtySelect').val(parseInt($(this).val()));});
}

function reviewTab(){
	if($('#customer-review-tab').length > 0){
		$('#customer-review-tab').click();
	}
	$('html,body').animate({scrollTop:$('#customer-review').offset().top});
}
if(window.location.hash == "#customer-review"){
    reviewTab();
}
//--></script>
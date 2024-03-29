<div class="my-account">
    <?php if ($message_stack->size('address') > 0) echo $message_stack->output('address'); ?>
    <div class="my-account-content">
		<div class="addresses-list">
			<div class="col-1 addresses-primary">
				<div class="box">
					<div class="box-title">
						<h2><?php echo __('Default Addresses'); ?></h2>
					</div>
					<div class="box-content">
						<ol>
							<li class="item">
								<h3><?php echo __('Default Billing Address'); ?></h3>
								<address><?php echo address_format($billingAddress); ?></address>
								<p><a class="btn btn-default mg-t5" href="<?php echo href_link(FILENAME_ADDRESS_EDIT, 'aID='.$billingAddress['address_id'], 'SSL'); ?>"><?php echo __('Change Billing Address'); ?></a></p>
							</li>
							<li class="item">
								<h3><?php echo __('Default Shipping Address'); ?></h3>
								<address><?php echo address_format($shippingAddress); ?></address>
								<p><a class="btn btn-default mg-t5" href="<?php echo href_link(FILENAME_ADDRESS_EDIT, 'aID='.$shippingAddress['address_id'], 'SSL'); ?>"><?php echo __('Change Shipping Address'); ?></a></p>
							</li>
						</ol>
					</div>
				</div>
			</div>
			<div class="col-2 addresses-additional">
				<div class="box">
					<div class="box-title">
						<h2><?php echo __('Additional Address'); ?></h2>
					</div>
					<div class="box-content">
						<ol>
							<?php if (count($additionalAddressList)>0) { ?>
								<?php foreach ($additionalAddressList as $_address) { ?>
									<li class="item">
										<address><?php echo address_format($_address); ?></address>
										<p><a href="<?php echo href_link(FILENAME_ADDRESS_EDIT, 'aID='.$_address['address_id'], 'SSL'); ?>"><?php echo __('Edit Address'); ?></a> <span class="separator">|</span> <a class="link-remove" onclick="return confirm('<?php echo __('Are you sure you want to delete this address?'); ?>');" href="<?php echo href_link(FILENAME_ADDRESS, 'delete=' . $_address['address_id']); ?>"><?php echo __('Delete Address'); ?></a></p>
									</li>
								<?php } ?>
							<?php } else { ?>
								<li class="item empty">
									<p><?php echo __('You have no additional address entries in your address book.'); ?></p>
								</li>
							<?php } ?>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<div class="buttons-set">
			<p class="back-link"><a class="btn btn-default" href="<?php echo href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><small>« </small><?php echo __('Back'); ?></a></p>
			<button class="btn btn-block btn-black" title="Add New Address" type="button" onclick="setLocation('<?php echo href_link(FILENAME_ADDRESS_NEW, '', 'SSL'); ?>');"><?php echo __('Add New Address'); ?></button>
		</div>
	</div>
</div>

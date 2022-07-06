<div class="header-banner">
	
        <?php if (IS_ZP == 0) { ?>
			<div class="banner-block1 container hidden-xs " id="J_owl">
					<!--<a class="item" href="<?php /*echo href_link(FILENAME_CATEGORY, 'cID=10'); */?>"><img src="<?php /*echo DIR_WS_TEMPLATE_IMAGES; */?>banners/banner1127.jpg" alt="" /></a>-->
					<a class="item" href="<?php echo href_link(FILENAME_PRODUCT, 'pID=1266'); ?>"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>banners/banner.jpg" alt="" /></a>
					<!--<a class="item" href="<?php /*echo href_link(FILENAME_PRODUCT, 'pID=1279'); */?>"><img src="<?php /*echo DIR_WS_TEMPLATE_IMAGES; */?>banners/banner1.jpg" alt="" /></a>-->
					<a class="item" href="<?php echo href_link(FILENAME_PRODUCT, 'pID=1279'); ?>"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>banners/banner2.jpg" alt="" /></a>
			</div>
			<div class="banner-block1 container hidden-sm hidden-md  hidden-lg" id="J_owl_1">
				<!--<a class="item" href="<?php /*echo href_link(FILENAME_CATEGORY, 'cID=10'); */?>"><img src="<?php /*echo DIR_WS_TEMPLATE_IMAGES; */?>banners/banner1127_m.jpg" alt="" /></a>-->
				<a class="item" href="<?php echo href_link(FILENAME_PRODUCT, 'pID=1266'); ?>"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>banners/banner_m.jpg" alt="" /></a>
			<!--	<a class="item" href="<?php /*echo href_link(FILENAME_PRODUCT, 'pID=1279'); */?>"><img src="<?php /*echo DIR_WS_TEMPLATE_IMAGES; */?>banners/banner1_m.jpg" alt="" /></a>-->
				<a class="item" href="<?php echo href_link(FILENAME_PRODUCT, 'pID=1279'); ?>"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>banners/banner2_m.jpg" alt="" /></a>
			</div>

			<div class="container category">

				<div class="row">
				<a class="item col-lg-3 col-md-3 col-sm-6 col-xs-6" href="<?php echo href_link(FILENAME_CATEGORY, 'cID=1'); ?>"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>necklaces.jpg" alt="" /></a>
				<a class="item col-lg-3 col-md-3 col-sm-6 col-xs-6" href="<?php echo href_link(FILENAME_CATEGORY, 'cID=2'); ?>"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>earrings.jpg" alt="" /></a>
				<a class="item col-lg-3 col-md-3 col-sm-6 col-xs-6" href="<?php echo href_link(FILENAME_CATEGORY, 'cID=3'); ?>"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>bracelets.jpg" alt="" /></a>
				<a class="item col-lg-3 col-md-3 col-sm-6 col-xs-6" href="<?php echo href_link(FILENAME_CATEGORY, 'cID=4'); ?>"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>rings.jpg" alt="" /></a>
				</div>
			</div>
        <?php } else { ?>
			<div class="banner-block1">
				<a class="item" href="<?php echo href_link(FILENAME_CATEGORY, 'cID=12'); ?>"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>banners/slide1_zp.jpg" alt="" /></a>
			</div>	
       <?php } ?>
</div>
<div class="main-container">
	<div class="container">
		<?php require($template->get_template_dir('tpl_modules_bestsellers.php', DIR_WS_TEMPLATE, $current_page, 'templates') . 'tpl_modules_bestsellers.php'); ?>
		<?php require($template->get_template_dir('tpl_modules_new_products.php', DIR_WS_TEMPLATE, $current_page, 'templates') . 'tpl_modules_new_products.php'); ?>
		<?php if (IS_ZP == 1) { ?>
			<?php require($template->get_template_dir('tpl_modules_new_products.php', DIR_WS_TEMPLATE, $current_page, 'templates') . 'tpl_modules_new_products.php'); ?>
		<?php } else { ?>
		<?php } ?>

	</div>
</div>
<?php if (IS_ZP == 0) { ?>
<div class="header-banner">
	<div class="container" style="display:none!important;">
		<div class="divider1"></div>
	</div>
	<div class="banner-block1 container" id="J_owl_2">
		<a class="item" href=""><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>1.jpg" alt="" /></a>
		<a class="item" href=""><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>2.jpg" alt="" /></a>
		<a class="item" href=""><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>3.jpg" alt="" /></a>
		<a class="item" href=""><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>4.jpg" alt="" /></a>
		<a class="item" href=""><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>5.jpg" alt="" /></a>
		<a class="item" href=""><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>6.jpg" alt="" /></a>
		<a class="item" href=""><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>7.jpg" alt="" /></a>
		<a class="item" href=""><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>9.jpg" alt="" /></a>
		<a class="item" href=""><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>10.jpg" alt="" /></a>
		<a class="item" href=""><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>11.jpg" alt="" /></a>
		<a class="item" href=""><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>12.jpg" alt="" /></a>


	</div>
</div>
<?php } else { ?>
    <div class="container" style="display:none!important;">

    </div>
<?php } ?>

<script type="text/javascript">
$(function () {
	$('#J_owl').owlCarousel({
		items:1,
		loop:true,
		nav:true,
		autoplay:true,
		autoplayTimeout:3000,
		autoplayHoverPause:true
	});
	$('#J_owl_1').owlCarousel({
		items:1,
		loop:true,
		nav:true,
		autoplay:true,
		autoplayTimeout:3000,
		autoplayHoverPause:true
	});
	$('#J_owl_2').owlCarousel({
		items:4,
		loop:true,
		nav:true,
		autoplay:true,
		autoplayTimeout:3000,
		autoplayHoverPause:true,
		margin:10,
		responsive:{
			100:{
				items:2
			},
			750:{
				items:3
			},
			1000:{
				items:4
			}
		}
	})
});
</script>

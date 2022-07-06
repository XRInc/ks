<div class="header-container">
	<div class="container">
		<p class="welcome-msg"><?php echo STORE_WELCOME; ?></p>
	</div>
</div>
<div class="pc-header hidden-xs hidden-sm">
	<div class="header container">
		<ul class="links">
			<li class="link-search">
				<form method="get" action="<?php echo href_link(FILENAME_SEARCH); ?>" id="pc_search_mini_form">
					<div class="form-search">
						<?php if (USE_URL_REWRITE == 0){ ?>
							<input type="hidden" value="search" name="main_page">
						<?php } ?>
						<button class="button" title="<?php echo __('Search'); ?>" type="submit"><i class="fa fa-search"></i></button>
						<input type="text" class="input-text" value="<?php echo isset($_GET['q'])?$_GET['q']:__(''); ?>" name="q" id="pcSearch" placeholder="Search" />
					</div>
				</form>
			</li>
			<li><a class="link-account" title="<?php echo __('My Account'); ?>" href="<?php echo href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><i class="fa fa-user"></i></a></li>
			<li><a class="link-cart" title="<?php echo __('My Cart'); ?>" href="<?php echo href_link(FILENAME_SHOPPING_CART, '', 'SSL'); ?>"><i class="fa fa-shopping-bag"></i></a></li>
			<li><?php require($template->get_template_dir('tpl_currency_header.php', DIR_WS_TEMPLATE, $current_page, 'sidebar') . 'tpl_currency_header.php'); ?></li>
		</ul>
		<div class="nav-container">
			<div id="nav">
				<a href="<?php echo href_link(FILENAME_INDEX); ?>" title="<?php echo HEADER_LOGO_ALT; ?>" class="logo"><strong><?php echo HEADER_LOGO_ALT; ?></strong>
					<?php if (IS_ZP == 1) { ?>
                        <img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>logo_zp.png" alt="<?php echo HEADER_LOGO_ALT; ?>" title="<?php echo HEADER_LOGO_ALT; ?>"/>
					<?php } else { ?>
                        <img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>logo.png" alt="<?php echo HEADER_LOGO_ALT; ?>" title="<?php echo HEADER_LOGO_ALT; ?>"/>
					<?php } ?>
				</a>
			</div>
		</div>
	</div>
	<div class="head-nav">
		<ul class="level1">
			<li>
			<?php if (IS_ZP == 1) { ?>
			<?php } else { ?>
			<a href="<?php echo href_link(FILENAME_INDEX); ?>"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>favicon.png" alt=""></a>
			<?php } ?>
			</li>
			<?php
		$categoryTree = $category_tree->getData();
		ksort($categoryTree);
		?>
		<?php if (isset($categoryTree[0])) { ?>
			<?php foreach ($categoryTree[0] as $val) { ?>
				<?php if (isset($categoryTree[$val['id']])) { ?>
					<li class="category-top">
						<a class="oneline" href="<?php echo href_link(FILENAME_CATEGORY, 'cID=' . $val['id']); ?>"><?php echo $val['name']; ?></a>
						<div class="head-level2">
							<ul class="level2 container">
								<?php foreach ($categoryTree[$val['id']] as $v) { ?>
									<li class="category-product">
										<a class="oneline" href="<?php echo href_link(FILENAME_CATEGORY, 'cID=' . $v['id']); ?>"><?php echo $v['name']; ?></a>
									</li>
								<?php } ?>
							</ul>
						</div>
					</li>
				<?php } else { ?>
					<li class="category-product">
						<a class="oneline" href="<?php echo href_link(FILENAME_CATEGORY, 'cID=' . $val['id']); ?>"><?php echo $val['name']; ?></a>
					</li>
				<?php } ?>
			<?php } ?>
		<?php } ?>
		</ul>
	</div>
</div>
<?php echo ONLINE_SERVICE; ?>
<div class="mobile-header hidden-md hidden-lg">
	<div class="header">
		<div class="m-head-left"><a href="javascript:;" class="category-tree"><i class="fa fa-bars"></i></a></div>
		<div class="m-logo">
			<a class="logo" href="<?php echo href_link(FILENAME_INDEX); ?>">
				<?php if (IS_ZP == 1) { ?>
                    <img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>logo_zp.png" alt="<?php echo HEADER_LOGO_ALT; ?>" title="<?php echo HEADER_LOGO_ALT; ?>"/>
				<?php } else { ?>
				<img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>logo.png" alt="<?php echo HEADER_LOGO_ALT; ?>" title="<?php echo HEADER_LOGO_ALT; ?>"/>
				<?php } ?>
			</a>
		</div>
		<div class="m-head-right"><a class="link-cart" href="<?php echo href_link(FILENAME_SHOPPING_CART, '', 'SSL'); ?>" rel="external nofollow"><i class="fa fa-shopping-bag"></i></a></div>
	</div>
	<div class="header-search" id="header-search">
		<form method="get" action="<?php echo href_link(FILENAME_SEARCH); ?>" id="m_search_mini_form">
			<div class="form-search">
				<?php if (USE_URL_REWRITE == 0){ ?>
					<input type="hidden" value="search" name="main_page">
				<?php } ?>
				<button class="button" title="<?php echo __('Go'); ?>" type="submit" onclick=""><i class="fa fa-search"></i></button>
				<input type="text" class="input-text" value="" name="q" id="mSearch" maxlength="100" placeholder="Search" />
			</div>
		</form>
	</div>
	<div class="left-menu" id="menu" data-scroll="">
		<div class="layer-tree" onclick="hideCategory();"></div>
		<div class="left-category">
			<div class="menu-header">
				<a class="link-account" title="<?php echo __('My Account'); ?>" href="<?php echo href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><i class="fa fa-user"></i><?php echo __('My Account'); ?></a>
			</div>
			<div class="category-list">
				<ul class="level1">
					<li><a href="<?php echo href_link(FILENAME_SEARCH_ORDER, '', 'SSL'); ?>"><?php echo __('Order Check'); ?></a></li>
					<?php
					$categoryTree = $category_tree->getData();
					ksort($categoryTree);
					?>
					<?php if (isset($categoryTree[0])) { ?>
						<?php foreach ($categoryTree[0] as $val) { ?>
							<?php if (isset($categoryTree[$val['id']])) { ?>
								<li class="category-top">
									<span class="all-category"><i class="right"></i></span>
									<a class="oneline" href="<?php echo href_link(FILENAME_CATEGORY, 'cID=' . $val['id']); ?>"><?php echo $val['name']; ?></a>
									<ul class="mobile-memu">
										<?php foreach ($categoryTree[$val['id']] as $v) { ?>
											<li class="category-product">
												<a class="oneline" href="<?php echo href_link(FILENAME_CATEGORY, 'cID=' . $v['id']); ?>"><?php echo $v['name']; ?></a>
											</li>
										<?php } ?>
									</ul>
								</li>
							<?php } else { ?>
								<li class="category-product">
									<a class="oneline" href="<?php echo href_link(FILENAME_CATEGORY, 'cID=' . $val['id']); ?>"><?php echo $val['name']; ?></a>
								</li>
							<?php } ?>
						<?php } ?>
					<?php } ?>
					<?php if (isset($_SESSION['customer_id'])) { ?>
						<li><a title="<?php echo __('Log Out'); ?>" href="<?php echo href_link(FILENAME_LOGOUT, '', 'SSL'); ?>"><?php echo __('Log Out'); ?></a></li>
					<?php } else { ?>
						<li><a title="<?php echo __('Log In'); ?>" href="<?php echo href_link(FILENAME_LOGIN, '', 'SSL'); ?>"><?php echo __('Log In'); ?></a></li>
						<li><a title="<?php echo __('Create Account'); ?>" href="<?php echo href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'); ?>"><?php echo __('Create Account'); ?></a></li>
					<?php } ?>
					<li class="currency"><?php require($template->get_template_dir('tpl_currency_header.php', DIR_WS_TEMPLATE, $current_page, 'sidebar') . 'tpl_currency_header.php'); ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<script language="javascript" type="text/javascript">
$(function(){
	var pTop = $('.head-nav').offset().top;
	$(window).scroll(function(){
		var scrolltop =  $(document).scrollTop();
		if(scrolltop > pTop){
			$('.pc-header .head-nav').addClass('header-fixed');
//			$('.pc-header .head-nav>.level1>li:last-child').css('display','block');
			$('.pc-header .head-nav>.level1>li:first-child').css('display','block');
		} else {
			$('.pc-header .head-nav').removeClass('header-fixed');
//			$('.pc-header .head-nav>.level1>li:last-child').css('display','none');
			$('.pc-header .head-nav>.level1>li:first-child').css('display','none');
		}
	});

	$('.category-tree').on('click',function(){
		$('.left-menu').fadeIn();
		$('html').addClass('noscroll');
		$.smartScroll('menu','.category-list');
	});

	$('.all-category').on('click',function(){
		$(this).siblings('.mobile-memu').slideToggle();
		$(this).children('i').toggleClass('right down');
	})

});

function hideCategory() {
	$('.left-menu').fadeOut();
	$('html').removeClass('noscroll');
}
</script>
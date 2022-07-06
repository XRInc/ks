<?php include(DIR_FS_CATALOG_MODULES . get_module_directory('review.php')); ?>
<style>
    /* 产品评论(review)*/
    ul.util-clearfix {
        height: 60px;
        zoom: 1;
    }
    ul.util-clearfix li {
        position: relative;
        list-style: none;
        margin: 0 10px 0 0 !important;
        float: left;
        width: 42px;
        height: 42px;
        margin-right: 5px;
        text-align: center;
        border: 1px solid #e9e9e9;
        border-radius: 5px;
        cursor: pointer;
    }
    ul.util-clearfix li {
        width: 50px;
        height: 60px;
        display: list-item;
    }

    ul.util-clearfix li.video-item:before {
        content: "";
        background: url(//img.alicdn.com/tfs/TB1C1jIQpXXXXXDXFXXXXXXXXXX-30-30.png) no-repeat center center;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-size: 25px;
    }

    ul.util-clearfix li.active:after {
        display: block;
    }
    ul.util-clearfix li:after {
        content: '';
        display: none;
        position: absolute;
        left: 15px;
        bottom: -13px;
        width: 0;
        height: 0;
        line-height: 0;
        font-size: 0;
        border: transparent 6px dashed;
        border-top: 6px solid #f60;
    }

    ul.util-clearfix li.active {
        border-color: #f60;
    }
    ul.util-clearfix li img {
        width: 40px;
        height: 40px;
        border-radius: 3px;
    }
    ul.util-clearfix li img {
        width: 48px;
        height: 58px;
        margin: -10px;
    }
    .big-photo-view {
        position: relative;
        display: none;
        border: 1px solid #ccc;
        padding-top: 20px;
        width: 500px;
        height: 400px;
        padding-bottom: 3px;
        text-align: center;
        margin-top: 10px;
    }
    .big-photo-view a.close-view {
        position:absolute;
        right: 10px;
        display: block;
        width: 16px;
        height: 15px;
        background: url(<?php echo DIR_WS_TEMPLATE_IMAGES;?>/ui-window-close.png) no-repeat 0 -3px;
        margin-bottom: 10px;
    }
    .big-photo-view a.close-view {
        top: 3px;
    }
    .photo-view-main {
        position: relative;
        height: 378px;
        overflow: hidden;
    }
    .photo-view-content {
        position: relative;
        text-align: center;
    }
    .fb-left-arrow {
        background: #fff url(<?php echo DIR_WS_TEMPLATE_IMAGES;?>/left-arrow.e6a3d32b.png) no-repeat center center;
        left: 0;
    }
    .fb-right-arrow {
        background: #fff url(<?php echo DIR_WS_TEMPLATE_IMAGES;?>/right-arrow.226b8732.png) no-repeat center center;
        right: 0;
    }

    .fb-left-arrow, .fb-right-arrow {
        cursor: pointer;
        position: absolute;
        top: 140px;
        display: none;
        width: 25px;
        height: 50px;
        overflow: hidden;
        text-indent: -999em;
        z-index: 99;
        opacity: .8;
    }
    .photo-view-content img {
        max-height: 378px;
        max-width: 498px;
        min-width: 200px;
        min-height: 200px;
    }
</style>
<a class="cosTab" id="customer-review-tab" href="#customer-review"><span class="cos-arrow"></span><?php echo __('Customer Reviews') ?></a>
<div class="box-collateral box-review" id="customer-review">
<?php if (isset($reviewList) && count($reviewList)>0) { ?>
	<div class="pr-contents-wrapper">
		<div class="pr-pagination-top">
			<h2><?php echo __('Customer Reviews') ?></h2>
		</div>
		<?php require($template->get_template_dir('tpl_modules_pager.php', DIR_WS_TEMPLATE, $current_page, 'templates') . 'tpl_modules_pager.php'); ?>
		<ul class="review-row">
			<?php foreach ($reviewList as $val) { ?>
				<li>
					<p><span class="star star<?php echo $val['rating']; ?>"></span><span class="review-date"><?php echo date_short($val['date_added']); ?></span></p>
					<p class="review-text">
						<?php echo $val['content']; ?>
						<div class="big-photo-view" data-role="show-big-photo" style="display:none;">
                            <a href="javascript:;" class="close-view" data-role="close"></a>
                            <div class="photo-view-main">
                                <div class="photo-view-content" data-role="photo-view" data-curr-index="0" curr-index="0"> </div>
                                <a href="javascript:;" class="fb-arrow fb-left-arrow" data-role="prev" style="display: none;" ></a>
                                <a href="javascript:;" class="fb-arrow fb-right-arrow" data-role="next" style="display: none;"></a>
                            </div>
                        </div>
					</p>
					<p class="a-right review-name"><?php echo __('By <span>%s</span>', $val['nickname']); ?></p>
				</li>
			<?php } ?>
		</ul>
		<?php require($template->get_template_dir('tpl_modules_pager.php', DIR_WS_TEMPLATE, $current_page, 'templates') . 'tpl_modules_pager.php'); ?>
	</div>
<?php } ?>
	<div class="form-add">
		<h2><?php echo __('Write Your Own Review') ?></h2>
		<form action="<?php echo href_link(FILENAME_PRODUCT, 'pID=' . $_GET['pID']); ?>" method="post" id="review-form">
		<div class="no-display">
			<input type="hidden" value="<?php echo $_SESSION['securityToken']; ?>" name="securityToken" />
			<input type="hidden" value="process" name="action" />
		</div>
		<div class="form-group">
			<label class="required"><em>*</em><?php echo __('Rating'); ?></label>
			<div class="input-box">
				<div id="star"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="required" for="review-nickname"><em>*</em><?php echo __('Nickname'); ?></label>
			<input type="text" value="<?php echo isset($_SESSION['customer_firstname'])?$_SESSION['customer_firstname']:'';?>" class="form-control input-text required-entry" id="review-nickname" name="review[nickname]">
		</div>
		<div class="form-group">
			<label class="required" for="review-content"><em>*</em><?php echo __('Review'); ?></label>
			<textarea class="form-control required-entry" rows="5" cols="5" id="review-content" name="review[content]"></textarea>
		</div>
		<div class="buttons-set">
			<button type="submit" class="button" title="<?php echo __('Submit Review') ?>"><span><span><?php echo __('Submit Review') ?></span></span></button>
		</div>
		</form>
	</div>
	<script type="text/javascript">
		$('#star').raty({
			scoreName:'review[rating]',
			score: 5,
			size: 22,
			path:'<?php echo DIR_WS_TEMPLATE_IMAGES; ?>',
			hints:[
				'<?php echo __('Bad'); ?>',
				'<?php echo __('Poor'); ?>',
				'<?php echo __('Regular'); ?>',
				'<?php echo __('Good'); ?>',
				'<?php echo __('Gorgeous'); ?>'
			]
		});
		if(window.location.hash == "#customer-review")
		{
			$('#customer-review').click();
		}
		$('#review-form').validate();
	</script>
	
	<script type="text/javascript">
        var oUl = $('ul li.pic-view-item');
        oUl.click(function() {
            $(this).parent().siblings('.big-photo-view').show();

            var index = $(this).attr('data-index');
            var path  = $(this).attr('data-src');
            var imgMp4 = $(this).children('img').attr('mp4-src');

            var photoView = $(this).parent().siblings('.big-photo-view').children('.photo-view-main').children('.photo-view-content').children();

            if (photoView) {
                photoView.remove();
            }

            $(this).addClass('active');
            $(this).siblings().removeClass('active');

            $(this).parent().siblings('.big-photo-view').children('.photo-view-main').children('.photo-view-content').attr('data-curr-index', index);
            $(this).parent().siblings('.big-photo-view').children('.photo-view-main').children('.photo-view-content').attr('curr-index', index);

            if (imgMp4 !== undefined && imgMp4 != '') {
                $(this).parent().siblings('.big-photo-view').children('.photo-view-main').children('.photo-view-content').append('<video controls="" autoplay="" preload="preload" id="video-viewer" src="'+imgMp4+'" style="width: 398px;height: 268px;"></video>');
                $(this).parent().siblings('.big-photo-view').css('width', '398px')
            } else {
                $(this).parent().siblings('.big-photo-view').children('.photo-view-main').children('.photo-view-content').append('<img src="'+ path+'"/>');
                var imgWidth = $(this).parent().siblings('.big-photo-view').children('.photo-view-main').children('.photo-view-content').children('img').width()
                $(this).parent().siblings('.big-photo-view').css('width', imgWidth)
            }
        })

        $('.close-view').click(function(){
            $(this).parent('.big-photo-view').hide();
            $(this).parent().siblings('.util-clearfix').children('li.pic-view-item').removeClass('active');
        })

        $('.photo-view-main').hover(function(){
            $('.fb-left-arrow').show();
            $('.fb-right-arrow').show();
        },function(){
            $('.fb-left-arrow').hide();
            $('.fb-right-arrow').hide();
        })

        $('.fb-left-arrow').click(function(){
            var image;
            var oUl = $(this).parent().parent().siblings('.util-clearfix').children('li.active');
            var index = oUl.attr('data-index');
            var firstIndex = $(this).parent().parent().siblings('.util-clearfix').children('li:first-child').attr('data-index');

            if (index == firstIndex) {
                oUl.removeClass('active');
                $(this).parent().parent().siblings('.util-clearfix').children('li:last-child').addClass('active');
                image = $(this).parent().parent().siblings('.util-clearfix').children('li:last-child').attr('data-src');
            } else {
                oUl.prev().addClass('active');
                image = oUl.prev().attr('data-src');
            }

            if (index != firstIndex) {
                oUl.removeClass('active');
                var imageSrc = $(this).siblings('.photo-view-content').children();

                if (imageSrc) {
                    imageSrc.remove();
                }

                $(this).siblings('.photo-view-content').append('<img src="'+ image+'"/>');
                var imgWidth = $(this).siblings('.photo-view-content').children('img').width();
                $(this).parent().parent().css('width', imgWidth)
            }
        })

        $('.fb-right-arrow').click(function(){
            var image;
            var oUl = $(this).parent().parent().siblings('.util-clearfix').children('li.active');
            var index = oUl.attr('data-index');
            var lastIndex = $(this).parent().parent().siblings('.util-clearfix').children('li:last-child').attr('data-index');

            if (index == lastIndex) {
                oUl.removeClass('active');
                $(this).parent().parent().siblings('.util-clearfix').children('li:first-child').addClass('active');
                image = $(this).parent().parent().siblings('.util-clearfix').children('li:first-child').attr('data-src');
            } else {
                oUl.next().addClass('active');
                image = oUl.next().attr('data-src');
            }

            if (index != lastIndex) {
                oUl.removeClass('active');
                var imageSrc = $(this).siblings('.photo-view-content').children();

                if (imageSrc) {
                    imageSrc.remove();
                }

                $(this).siblings('.photo-view-content').append('<img src="'+ image+'" />');
                var imgWidth = $(this).siblings('.photo-view-content').children('img').width();
                $(this).parent().parent().css('width', imgWidth)
            }
        })
    </script>
</div>
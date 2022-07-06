<?php require('includes/application_top.php'); ?>
<?php
require('js/tools/phpQuery.php');
require('js/tools/QueryList.php');

$product_review_id = isset($_GET['product_review_id'])?$_GET['product_review_id']:0;
$action = isset($_GET['action'])?$_GET['action']:'';
$availabRating = array('1'=>'1星', '2'=>'2星', '3'=>'3星', '4'=>'4星', '5'=>'5星');
$availabStatus = array('0'=>'禁用', '1'=>'启用');
switch ($action) {
	//抓取产品评论
    case 'get_comment':
        $error       = false;
        $html        = isset($_POST['code']) ? $_POST['code'] : '';
        $productType = isset($_POST['type']) ? $_POST['type'] : '';
        $productId   = isset($_POST['product_id']) ? db_prepare_input($_POST['product_id']) : '';
        $proReviewList = array();
        $proImgList  = array();
        $reviewHtml = '';

        if (empty($productType) || $productType == -1) {
            $error = true;
            $message_stack->add('product_review', '请选择类型。');
        }

        if (empty($productId)) {
            $error = true;
            $message_stack->add('product_review', '产品ID不能为空。');
        }

        if (empty($html)) {
            $error = true;
            $message_stack->add('product_review', '产品源码不能为空。');
        }

        $productIdArr = explode(',', $productId);

        if ($error == true) {
            //nothing
        } else {

            switch ($productType) {
                case 1:
                    //产品评论信息
                    $reviewRule = array(
                        'review_id' => array('div[class=a-section review aok-relative]','id'),
                        'nickname'  => array('div[class=a-section review aok-relative] a[class=a-profile] div[class=a-profile-content] span[class=a-profile-name]','text'),
                        'rating'    => array('div[class=a-section review aok-relative] div[class=a-row] a[class=a-link-normal] span[class=a-icon-alt]','text'),
                        'review'    => array('div[class=a-section review aok-relative] div[class=a-row a-spacing-small review-data] span[class=a-size-base review-text review-text-content] span', 'html')
                    );
                    $reviewList = QueryList::Query($html, $reviewRule)->data;

                    //评论id
                    $reviewIdRule = array(
                        'review_id'=> array('div[class=review-image-tile-section]','data-reviewid')
                    );
                    $reviewIdList = QueryList::Query($html, $reviewIdRule)->data;

                    //获取匹配评论图片
                    $imageRule = array(
                        'image' => array('script','text','', function($content){
                            if (stripos($content, 'P.when(\'A\', \'cr-image-popover-controller\').execute(function(A, imagePopoverController) {') !== false) {
                                if (preg_match_all('/https:\/\/(.*?)\.(jpg|png|gif)/s', $content, $arr)) {
                                    return $arr[0];
                                }
                            }
                        })
                    );
                    $imageList = QueryList::Query($html, $imageRule)->data;

                    //获取图片
                    if (!empty($imageList) && is_array($imageList)) {
                        foreach ($imageList as $key => $val) {
                            if (!empty($imageList[$key]['image'])) {
                                $proImgList[] = $imageList[$key]['image'];
                            }
                        }
                    } else {
                        $message_stack->add('product_review', '未匹配到产品相关的评论信息');
                    }

                    //进行匹配
                    if (!empty($reviewList)) {
                        foreach ($reviewList as $key => $val) {

                            if (isset($reviewList[$key]['review_id'])) {
                                $reviewList[$key]['rating'] = explode('.', $reviewList[$key]['rating'])[0];

                                if (!empty($reviewIdList)) {
                                    foreach ($reviewIdList as $_key => $_value) {
                                        if ($reviewList[$key]['review_id'] == $reviewIdList[$_key]['review_id']) {
                                            $concatCode = '';
                                            $reviewHtml = '<ul class="util-clearfix">';

                                            foreach ($proImgList[$_key] as $__key => $__value) {
                                                set_time_limit(1000);
                                                $imgCode = save_product_review($__value);

                                                if ($imgCode) {
                                                    $reviewHtml .= '<li class="pic-view-item" data-index="' . $__key . '" data-src="' . $imgCode . '">';
                                                    $reviewHtml .= '<img src="' . $imgCode . '" class="comment_img"/>';
                                                    $reviewHtml .= '</li>';
                                                }
                                            }

                                            $reviewHtml .= '</ul>';
                                            $concatCode .= $reviewHtml;

                                            $reviewList[$key]['review'] .= '<br/>' . $concatCode;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $message_stack->add('product_review', '未检索到产品相关的评论数据');
                    }
                break;
                case 2:
                    $productBaseReviewRule = array(
                        'nickname' => array('div[class=feedback-item clearfix] div[class=fb-user-info] span[class=user-name]', 'text'),
                        'rating'   => array('div[class=feedback-item clearfix] div[class=f-rate-info] span[class=star-view]','html','',function($content){
                            if (preg_match('/\d+/', $content, $arr)) {
                                return $arr[0];
                            }
                        }),
                        'review'   => array('div[class=feedback-item clearfix] div[class=f-content] dl[class=buyer-review] dt[class=buyer-feedback]', 'html', '', function($content){
                            if (preg_match('/<span>.*?<\/span>/s', $content, $arr)) {
                                return $arr[0];
                            }
                        }),
                        'image'    => array('div[class=feedback-item clearfix] div[class=f-content] dl[class=buyer-review] dd[class=r-photo-list] ul[class=util-clearfix]', 'html','', function ($content) {
                            $preg = '/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/s';
                            if (preg_match_all($preg, $content, $matches)) {
                                return $matches[0];
                            }
                        })
                    );

                    $productReviewList = QueryList::Query($html, $productBaseReviewRule)->data;
                    $rating = 0;

                    if (!empty($productReviewList)) {
                        foreach ($productReviewList as $key => $val) {
                            if (!empty($val['rating'])) {
                                switch ($val['rating']) {
                                    case 100:
                                        $rating = 5;
                                        break;
                                    case 80:
                                        $rating = 4;
                                        break;
                                    case 60:
                                        $rating = 3;
                                        break;
                                    case 40:
                                        $rating = 2;
                                        break;
                                    case 20:
                                        $rating = 1;
                                        break;
                                }

                                $productReviewList[$key]['rating'] = $rating;
                            }

                            if (is_array($val['image'])) {
                                $concatCode = '';
                                $reviewHtml = '<ul class="util-clearfix">';

                                foreach ($val['image'] as $_key => $_val) {
                                    if (preg_match('/https:\/\/(.*?)\.(jpg|png)/', $_val, $matches)) {
                                        set_time_limit(1000);
                                        $imgCode = save_product_review($matches[0]);

                                        if ($imgCode) {
                                            $reviewHtml .= '<li class="pic-view-item" data-index="' . $_key . '" data-src="' . $imgCode . '">';
                                            $reviewHtml .= '<img src="' . $imgCode . '" class="comment_img"/>';
                                            $reviewHtml .= '</li>';
                                        }
                                    }
                                }

                                $reviewHtml .= '</ul>';

                                $concatCode .= $reviewHtml;
                                $productReviewList[$key]['review'] .= '<br/>' . $concatCode;
                            }

                            $reviewList[] = array(
                                'review_id' => 1,
                                'nickname'  => $productReviewList[$key]['nickname'],
                                'rating'    => $productReviewList[$key]['rating'],
                                'review'    => $productReviewList[$key]['review'],
                            );
                        }
                    } else {
                        $message_stack->add('product_review', '未匹配到有关产品的评论数据');
                    }
                break;
            }

            //保存数据
            $i = 0;
            $proReviewList = $reviewList;

            if (is_array($productIdArr)) {
                foreach ($productIdArr as $k => $v) {
                    foreach ($proReviewList as $_k => $_v) {
                        $currentDate = time(); //当前时间
                        $dateMtRand  = strtotime('-3 day', time()); //前3天的
                        $reviewTime  = date('Y-m-d H:i:s', mt_rand($dateMtRand, $currentDate));

                        if (isset($proReviewList[$k]['review_id'])) {
                            $sqlData = array(
                                array('fieldName' => 'product_id', 'value' => $v, 'type' => 'integer'),
                                array('fieldName' => 'nickname', 'value' => $proReviewList[$_k]['nickname'], 'type' => 'string'),
                                array('fieldName' => 'rating', 'value' => $proReviewList[$_k]['rating'], 'type' => 'integer'),
                                array('fieldName' => 'content', 'value' => $proReviewList[$_k]['review'], 'type' => 'string'),
                                array('fieldName' => 'status', 'value' => 1, 'type' => 'integer'),
                                array('fieldName' => 'date_added', 'value'=> $reviewTime, 'type' => 'string')
                            );

                            $db->perform(TABLE_PRODUCT_REVIEW, $sqlData);
                            $i++;
                        }
                    }
                }
            }

            $message_stack->add_session('product_review', '产品评论成功保存' .$i. '条', 'success');
            redirect(href_link(FILENAME_PRODUCT_REVIEW));
        }
    break;
	case 'save':
		$error = false;
		$productReview = db_prepare_input($_POST['product_review']);
		$securityToken = isset($_POST['securityToken'])?db_prepare_input($_POST['securityToken']):'';
		if ($securityToken != $_SESSION['securityToken']) {
			$error = true;
			$message_stack->add('product_review', '产品评论保存时出现安全错误。');
		}
		if (strlen($productReview['nickname']) < 1) {
			$error = true;
			$message_stack->add('product_review', '产品评论作者不能为空。');
		}
		if ($productReview['product_id'] > 0){
			$sql = "SELECT COUNT(*) AS total FROM " . TABLE_PRODUCT . " WHERE product_id = :productID";
			$sql = $db->bindVars($sql, ':productID', $productReview['product_id'], 'integer');
			$check_product = $db->Execute($sql);
			if ($check_product->fields['total'] > 0) {
				//nothing
			} else {
				$error = true;
				$message_stack->add('product_review', '请填写正确的产品ID。');
			}
		} else {
			$error = true;
			$message_stack->add('product_review', '请填写产品ID。');
		}
		if (strlen($productReview['content']) < 1) {
			$error = true;
			$message_stack->add('product_review', '产品评论内容不能为空。');
		}
		if (strlen($productReview['content']) < 1) {
			$error = true;
			$message_stack->add('product_review', '产品评论内容不能为空。');
		}
		if (!array_key_exists($productReview['rating'], $availabRating)) $productReview['rating'] = 1;
		if (!array_key_exists($productReview['status'], $availabStatus)) $productReview['status'] = 0;
		if ($error==true) {
			//nothing
		} else {
			$sql_data_array = array(
				array('fieldName'=>'product_id', 'value'=>$productReview['product_id'], 'type'=>'integer'),
				array('fieldName'=>'nickname', 'value'=>$productReview['nickname'], 'type'=>'string'),
				array('fieldName'=>'rating', 'value'=>$productReview['rating'], 'type'=>'integer'),
				array('fieldName'=>'content', 'value'=>$productReview['content'], 'type'=>'string'),
				array('fieldName'=>'status', 'value'=>$productReview['status'], 'type'=>'integer')
			);
			if($productReview['product_review_id'] > 0) {
				$db->perform(TABLE_PRODUCT_REVIEW, $sql_data_array, 'UPDATE', 'product_review_id = ' . $productReview['product_review_id']);
			} else {
				$sql_data_array[] = array('fieldName'=>'date_added', 'value'=>'NOW()', 'type'=>'noquotestring');
				$db->perform(TABLE_PRODUCT_REVIEW, $sql_data_array);
				$productReview['product_review_id'] = $db->Insert_ID();
			}
			$message_stack->add_session('product_review', '产品评论设置已保存', 'success');
			redirect(href_link(FILENAME_PRODUCT_REVIEW, 'product_review_id=' . $productReview['product_review_id']));
		}
	break;
	case 'delete':
		$error = false;
		$selected = $_POST['selected'];
		$securityToken = isset($_POST['securityToken'])?db_prepare_input($_POST['securityToken']):'';
		if ($securityToken != $_SESSION['securityToken']) {
			$error = true;
			$message_stack->add_session('product_review', '删除产品评论时出现安全错误。');
		}
		if ($error==true) {
			//nothing
		} else {
			foreach ($selected as $val) {
				$db->Execute("DELETE FROM " . TABLE_PRODUCT_REVIEW . " WHERE product_review_id = " . (int)$val);
			}
			$message_stack->add_session('product_review', '产品评论已删除。', 'success');
		}
		redirect(href_link(FILENAME_PRODUCT_REVIEW));
	break;
	case 'set_status':
		$db->Execute("UPDATE " . TABLE_PRODUCT_REVIEW . " SET status = IF(status = 1, 0, 1) WHERE product_review_id = " . (int)$product_review_id);
		redirect(href_link(FILENAME_PRODUCT_REVIEW));
	break;
	default:
		if ($product_review_id > 0) {
			$sql = "SELECT product_review_id, product_id, rating,
						   nickname, content, status, date_added
					FROM   " . TABLE_PRODUCT_REVIEW . "
					WHERE  product_review_id = :productReviewID";
			$sql = $db->bindVars($sql, ':productReviewID', $product_review_id, 'integer');
			$result = $db->Execute($sql);
			if ($result->RecordCount() > 0) {
				$productReview = array(
					'product_review_id'  => $result->fields['product_review_id'],
					'product_id' => $result->fields['product_id'],
					'rating'     => $result->fields['rating'],
					'nickname'   => $result->fields['nickname'],
					'content'    => $result->fields['content'],
					'status'     => $result->fields['status'],
					'date_added' => $result->fields['date_added']
				);
			}
		} else {
			$sql = "SELECT COUNT(*) AS total FROM " . TABLE_PRODUCT_REVIEW;
			$result = $db->Execute($sql);
			$pagerConfig['total'] = $result->fields['total'];
			require(DIR_FS_ADMIN_CLASSES . 'pager.php');
			$pager = new pager($pagerConfig);
			$sql = "SELECT pr.product_review_id, pr.customer_id, pr.nickname,
						   pr.rating, pr.status, pr.date_added, p.name
					FROM   " . TABLE_PRODUCT_REVIEW . " pr, " . TABLE_PRODUCT . " p
					WHERE  pr.product_id = p.product_id
					ORDER BY pr.product_review_id DESC";
			$result = $db->Execute($sql, $pager->getLimitSql());
			$productReviewList = array();
			while (!$result->EOF) {
				$productReviewList[] = array(
					'product_review_id'    => $result->fields['product_review_id'],
					'customer_id'  => $result->fields['customer_id'],
					'name'         => $result->fields['nickname'],
					'rating'       => $result->fields['rating'],
					'status'       => $result->fields['status'],
					'date_added'   => $result->fields['date_added'],
					'product_name' => $result->fields['name']
				);
				$result->MoveNext();
			}
		}
	break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>产品评论</title>
<meta name="robot" content="noindex, nofollow" />
<base href="<?php echo (($request_type=='SSL')?HTTPS_SERVER:HTTP_SERVER) . DIR_WS_ADMIN; ?>" />
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<link href="css/styles-ie.css" type="text/css" rel="stylesheet" />
<link href="js/umeditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet" />
<script src="js/jquery/jquery.js" type="text/javascript"></script>
<script src="js/jquery/base.js" type="text/javascript"></script>
<script src="js/umeditor/umeditor.config.js" type="text/javascript"></script>
<script src="js/umeditor/umeditor.min.js" type="text/javascript"></script>
<script src="js/umeditor/lang/zh-cn/zh-cn.js" type="text/javascript"></script>
</head>
<body>
<div class="wrapper">
	<?php require(DIR_FS_ADMIN_INCLUDES . 'noscript.php'); ?>
	<div class="page">
    	<?php require(DIR_FS_ADMIN_INCLUDES . 'header.php'); ?>
    	<div class="main-container">
    		<div class="main">
    		<?php if ($message_stack->size('product_review') > 0) echo $message_stack->output('product_review'); ?>
    		<?php if ($action == 'new' || $action == 'save' || $product_review_id > 0) { ?>
    			<form action="<?php echo href_link(FILENAME_PRODUCT_REVIEW, 'action=save'); ?>" method="post">
    			<div class="no-display">
    				<input type="hidden" value="<?php echo $_SESSION['securityToken']; ?>" name="securityToken" />
    				<input type="hidden" value="<?php echo isset($productReview['product_review_id'])?$productReview['product_review_id']:''; ?>" name="product_review[product_review_id]" />
    			</div>
    			<div class="page-title title-buttons">
	    			<h1>产品评论</h1>
	    			<button type="submit" class="button"><span><span>保存</span></span></button>
    				<button type="button" class="button btn-cancel" onclick="setLocation('<?php echo href_link(FILENAME_PRODUCT_REVIEW); ?>');"><span><span>取消</span></span></button>
	    		</div>
	    		<table class="form-list">
				<tbody>
					<tr>
						<td class="label"><label for="product-review-nickname">评论作者 <span class="required">*</span></label></td>
						<td class="value"><input type="text" class="input-text required-entry" value="<?php echo isset($productReview['nickname'])?$productReview['nickname']:''; ?>" name="product_review[nickname]" id="product-review-nickname" /></td>
					</tr>
					<tr>
						<td class="label"><label for="product-review-product_id">产品 <span class="required">*</span></label></td>
						<td class="value"><input type="text" class="input-text required-entry" value="<?php echo isset($productReview['product_id'])?$productReview['product_id']:''; ?>" name="product_review[product_id]" id="product-review-product_id" /></td>
					</tr>
					<tr>
						<td class="label"><label for="product-review-content">评论内容 <span class="required">*</span></label></td>
						<td class="value" id="umWrap"><textarea name="product_review[content]" id="product-review-content"><?php echo isset($productReview['content'])?$productReview['content']:''; ?></textarea></td>
						<script type="text/javascript">
                            $(function(){
                                var fatherWidth = $('#umWrap').width();
                                var um = UM.getEditor('product-review-content', {
                                    initialFrameWidth: fatherWidth * 0.98 //设置编辑器宽度
                                    ,initialFrameHeight:300
                                    ,toolbar:[
                                        'source | undo redo | bold italic underline strikethrough horizontal | superscript subscript | forecolor backcolor | removeformat |',
                                        'insertorderedlist insertunorderedlist | selectall cleardoc paragraph | fontfamily fontsize' ,
                                        '| justifyleft justifycenter justifyright justifyjustify'
                                    ]
                                });
                            });
                        </script>
					</tr>
					<tr>
						<td class="label"><label for="product-review-rating">评级 <span class="required">*</span></label></td>
						<td class="value">
							<select name="product_review[rating]" id="product-review-rating">
								<?php foreach ($availabRating as $_key=>$_val) { ?>
								<option<?php if (isset($productReview['rating'])&&$productReview['rating']==$_key) { ?> selected="selected"<?php } ?> value="<?php echo $_key; ?>"><?php echo $_val; ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<td class="label"><label for="product-review-status">状态 <span class="required">*</span></label></td>
						<td class="value">
							<select name="product_review[status]" id="product-review-status">
								<option<?php if (isset($productReview['status'])&&$productReview['status']==1) { ?> selected="selected"<?php } ?> value="1">启用</option>
								<option<?php if (isset($productReview['status'])&&$productReview['status']!=1) { ?> selected="selected"<?php } ?> value="0">禁用</option>
							</select>
						</td>
					</tr>
				</tbody>
    			</table>
    			</form>
			<?php } elseif ($action == 'get' || $action == 'get_comment') {?>
                <form action="<?php echo href_link(FILENAME_PRODUCT_REVIEW, 'action=get_comment'); ?>" method="post" onsubmit="autoSub()">
                    <div class="page-title title-buttons">
                        <h1>采集产品评论</h1>
                        <button type="submit" class="button" id="submit"><span><span>获取</span></span></button>
                        <button type="button" class="button btn-cancel" onclick="setLocation('<?php echo href_link(FILENAME_PRODUCT_REVIEW); ?>');"><span><span>取消</span></span></button>
                    </div>
                    <table class="form-list">
                        <tbody>
                        <tr>
                            <td class="label"><label for="product_code">网站类型 <span class="required">*</span></label></td>
                            <td class="value">
                                <select id="product_type" name="type">
                                    <option value="-1" selected>请选择</option>
                                    <option value="1">Amazon</option>
                                    <option value="2">aliExpress</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="label"><label for="product-product_id">产品ID <span class="required">*</span></label></td>
                            <td class="value"><input type="text" class="input-text required-entry" value="" name="product_id" id="product-product_id" /></td>
                        </tr>
                        <tr>
                            <td class="label"><label for="product_code">产品评论源码 <span class="required">*</span></label></td>
                            <td class="value">
                                <textarea class="form-control required-entry" rows="5" id="product_code" name="code"></textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
    		<?php } else { ?>
				<form action="<?php echo href_link(FILENAME_PRODUCT_REVIEW, 'action=delete'); ?>" method="post">
	    		<div class="no-display">
    				<input type="hidden" value="<?php echo $_SESSION['securityToken']; ?>" name="securityToken" />
    			</div>
	    		<div class="page-title title-buttons">
	    			<h1>产品评论</h1>
					<button type="button" class="button button-new" onclick="setLocation('<?php echo href_link(FILENAME_PRODUCT_REVIEW, 'action=get'); ?>');"><span><span>采集</span></span></button>
    				<button type="button" class="button button-new" onclick="setLocation('<?php echo href_link(FILENAME_PRODUCT_REVIEW, 'action=new'); ?>');"><span><span>新增</span></span></button>
    				<button type="submit" class="button" onclick="return confirm('删除或卸载后您将不能恢复，请确定要这么做吗？');"><span><span>删除</span></span></button>
	    		</div>
	    		<?php require(DIR_FS_ADMIN_INCLUDES . 'pager.php'); ?>
	    		<table class="data-table">
	    		<colgroup>
	    			<col width="10" />
	    			<col />
	    			<col />
	    			<col width="40" />
	    			<col width="40" />
	    			<col width="60" />
	    			<col width="140" />
	    			<col width="60" />
	    		</colgroup>
	    		<thead>
	    			<tr>
	    				<th><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
	    				<th>产品名</th>
	    				<th>评论作者</th>
	    				<th class="a-center">类型</th>
	    				<th class="a-center">评级</th>
	    				<th class="a-center">状态</th>
	    				<th>评论日期</th>
	    				<th class="a-center">管理</th>
	    			</tr>
	    		</thead>
	    		<?php if (count($productReviewList)>0) { ?>
	    		<tbody>
	    			<?php foreach ($productReviewList as $val) { ?>
	    			<tr>
	    				<td><input type="checkbox" value="<?php echo $val['product_review_id']; ?>" name="selected[]" /></td>
	    				<td><?php echo $val['product_name']; ?></td>
	    				<td><?php echo $val['name']; ?></td>
	    				<td class="a-center"><?php echo $val['customer_id']>0?'客户':'游客'; ?></td>
	    				<td class="a-center"><?php echo $availabRating[$val['rating']]; ?></td>
	    				<td class="a-center">[ <a title="点击 状态:<?php echo $val['status']==1?$availabStatus[0]:$availabStatus[1]; ?>" href="<?php echo href_link(FILENAME_PRODUCT_REVIEW, 'action=set_status&product_review_id=' . $val['product_review_id']); ?>"><?php echo $availabStatus[$val['status']]; ?></a> ]</td>
	    				<td><?php echo datetime_short($val['date_added']); ?></td>
	    				<td class="a-center">[ <a href="<?php echo href_link(FILENAME_PRODUCT_REVIEW, 'product_review_id=' . $val['product_review_id']); ?>">编辑</a> ]</td>
	    			</tr>
	    			<?php } ?>
	    		</tbody>
	    		<?php } else { ?>
	    		<tbody>
					<tr>
						<td class="a-center" colspan="8">没有结果！</td>
					</tr>
				</tbody>
	    		<?php } ?>
	    		</table>
	    		<?php require(DIR_FS_ADMIN_INCLUDES . 'pager.php'); ?>
	    		</form>
    		<?php } ?>
    		</div>
        </div>
        <?php require(DIR_FS_ADMIN_INCLUDES . 'footer.php'); ?>
    </div>
</div>
</body>
<script type="text/javascript">
    function autoSub() {
        var subBtn = document.getElementById('submit');
        subBtn.disabled = true;
        return true;
    }
</script>
</html>
<?php require('includes/application_bottom.php'); ?>
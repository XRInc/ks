<?php

class ipasspay
{
    function before()
    {
        $monthStr = '<option value="">' . __('Month') . '</option>';
        for ($i = 1; $i <= 12; $i++) {
            $monthStr .= '<option value="' . str_pad($i, 2, '0', STR_PAD_LEFT) . '">' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>';
        }

        $year    = date('Y');
        $yearStr = '<option value="">' . __('Year') . '</option>';
        for ($i = 0; $i < 25; $i++) {
            $yearStr .= '<option value="' . substr($year + $i, -2, 2) . '">' . substr($year + $i, -2, 2) . '</option>';
        }

        $txtCardNumber = __('Card Number');
        $txtCVC        = __('CVV');
        $v             = DIR_WS_CATALOG_IMAGES . 'payment/icon/v.png';
        $m             = DIR_WS_CATALOG_IMAGES . 'payment/icon/m.png';
        $j             = DIR_WS_CATALOG_IMAGES . 'payment/icon/j.png';
        $a             = DIR_WS_CATALOG_IMAGES . 'payment/icon/a.png';
        $vmj           = DIR_WS_CATALOG_IMAGES . 'payment/icon/vmj.png';
        $security      = DIR_WS_CATALOG_IMAGES . 'payment/icon/security.jpg';
        $notesTitle    = __('Notes');
        $notesContent  = __('You are now connected to a secure payment site with certificate issued by VeriSign, Your payment details will be securely transmitted to the Bank for transaction authorization in full accordance with PCI standards.');

        $html = <<<HTML
<ul class="inside-payform inside-payform-ipass">
    <li class="field-card form-group">
        <div class="input-box">
            <input type="tel" class="form-control input-text required-entry creditcard" name="ipasspay_card[number]" id="ipassTxtCardNumber" maxLength="16" onkeyup="ipasspayCheckCardNumber();" oninput="ipasspayCheckCardNumber();" placeholder="$txtCardNumber" />
        </div>
        <span class="brand brand-card" id="ipassBrandCard"></span>
    </li>
    <li class="field-date form-group">
        <select class="form-control required-entry field-date-month" name="ipasspay_card[month]">$monthStr</select>
        <select class="form-control required-entry" name="ipasspay_card[year]">$yearStr</select>
    </li>
    <li class="field-cvv form-group">
        <div class="input-box">
            <input type="tel" class="form-control input-text required-entry digits" name="ipasspay_card[cvv]" id="txtCardCVV" minLength="3" maxLength="4" onkeyup="this.value=this.value.replace(/\D/g,'')" oninput="this.value=this.value.replace(/\D/g,'')" placeholder="$txtCVC"/>
        </div>
    </li>
    <li class="field-notes">
        <div class="title">$notesTitle</div>
        <div class="content"><p class="std">$notesContent</p></div>
        <img src="$security" />
    </li>
</ul>
<style type="text/css">
.inside-payform.inside-payform-ipass li.field-card,
.inside-payform.inside-payform-ipass li.field-date,
.inside-payform.inside-payform-ipass li.field-cvv {border: 1px solid #E4E4E4; margin-bottom: 10px; position: relative; height: 62px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
.inside-payform.inside-payform-ipass li .input-box {position: absolute; height: 32px; width: auto; left: 11px; top: 11px; right: 40px; margin-top: 5px; z-index: 1;}
.inside-payform.inside-payform-ipass li.field-date {border: none;}
.inside-payform.inside-payform-ipass li select {float:left;width:50%;padding:3px 0;border:none;box-shadow:none;color:#32325d;line-height:24px;font-size:16px;height:30px;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;}
.inside-payform.inside-payform-ipass li.field-date .form-control {padding: 16px 22px; height: 62px; line-height: 30px; width: 49%; margin-right: 2%; border: 1px solid #e4e4e4; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; border-radius: 0;}
.inside-payform.inside-payform-ipass li.field-date .form-control:last-child {margin-right: 0;}
.inside-payform.inside-payform-ipass li .form-control.valid {border: none !important; background: none !important;}
.inside-payform.inside-payform-ipass li.field-date .form-control.valid {border: 1px solid #e4e4e4 !important;}
.inside-payform.inside-payform-ipass input,
.inside-payform.inside-payform-ipass input:focus,
.inside-payform.inside-payform-ipass select,
.inside-payform.inside-payform-ipass select:focus {background-color: transparent; background-image: none; border: none; outline: 0 none;}
.inside-payform.inside-payform-ipass li input.input-text {color: #32325d; line-height: 30px; font-size: 16px; width: 100%; height: 30px; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; box-shadow: none;}
.inside-payform.inside-payform-ipass li .brand {position:absolute;top:11px;right:5px;display:block;margin-top:5px;width:32px;height:32px;background-repeat:no-repeat;background-position:center center;background-size:80%;z-index:2;}
.inside-payform.inside-payform-ipass li .brand.brand-card {background-image: url("$vmj"); top: 11px; margin-top: 5px;}
</style>
<script type="text/javascript">
function ipasspayCheckCardNumber(){
    var txtCardNumber = document.getElementById('ipassTxtCardNumber'),
        brandCard = document.getElementById('ipassBrandCard');

    txtCardNumber.value = txtCardNumber.value.replace(/\D/g, '');
    if ((/^[4]{1}/).test(txtCardNumber.value)) {
        brandCard.style.backgroundImage = 'url("$v")';
    } else if ((/^[5]{1}[1-5]{1}/).test(txtCardNumber.value)) {
        brandCard.style.backgroundImage = 'url("$m")';
    } else if ((/^[3]{1}[5]{1}/).test(txtCardNumber.value)) {
        brandCard.style.backgroundImage = 'url("$j")';
    } else if ((/^[3]{1}[47]{1}/).test(txtCardNumber.value)) {
        brandCard.style.backgroundImage = 'url("$a")';
    } else {
        brandCard.style.backgroundImage = 'url("$vmj")';
    }
}
</script>
HTML;

        return $html;
    }

    function after()
    {
        global $message_stack, $error, $current_page;

        if (isset($_POST['ipasspay_card'])) {
            $ipasspay_card = $_POST['ipasspay_card'];
            if (strlen($ipasspay_card['number']) < 1) {
                $error = true;
                $message_stack->add($current_page, __('"Card Number" is a required value. Please enter the card number.'));
            } elseif (!validate_creditcard($ipasspay_card['number'])) {
                $error = true;
                $message_stack->add($current_page, __('"Card Number" is not a valid card number.'));
            }
            if (strlen($ipasspay_card['month']) < 1
                || strlen($ipasspay_card['year']) < 1) {
                $error = true;
                $message_stack->add($current_page, __('"Expiry Date" is a required value. Please enter the expiry date.'));
            }
            if (strlen($ipasspay_card['cvv']) < 1) {
                $error = true;
                $message_stack->add($current_page, __('"CVC/CVV2" is a required value. Please enter the cvc/cvv2.'));
            }
            if ($error == true) {
                //nothing
            } else {
                $_SESSION['ipasspay_card'] = array(
                    'number' => $ipasspay_card['number'],
                    'month'  => $ipasspay_card['month'],
                    'year'   => $ipasspay_card['year'],
                    'cvv'    => $ipasspay_card['cvv'],
                );
            }
        }
    }

    public function process($payment)
    {
        /**
         * $orderInfo 订单信息
         * $orderProductInfo 产品信息
         * $currencies 币种信息
         * $db payment_method表的信息
         */
        global $orderInfo, $orderProductInfo, $currencies;

        if ($payment->get_is_inside() == 0
            && !isset($_POST['ipasspay_card_number'])) {
            redirect(href_link('ipasspay_process', '', 'SSL'));
        }

        // 商品集合
        foreach ($orderProductInfo as $_product) {
            $price = $currencies->get_price($_product['price'], $orderInfo['currency']['code'], $orderInfo['currency']['value']);

            $goods[] = array(
                'goods_name' => $_product['name'],
                'quality'    => $_product['qty'],
                'price'      => $price
            );
        }
        $goods = json_encode($goods);

        // 获取国家代码
        $billCountryIso = get_country_iso($orderInfo['billing']['country_id']);
        $shipCountryIso = get_country_iso($orderInfo['shipping']['country_id']);

        // 获取州省代码
        if (!empty($orderInfo['billing']['region'])) {
            if (!empty($orderInfo['billing']['region_id'])) {
                $billRegionCode = get_region_code($orderInfo['billing']['region_id']);
            } else {
                $billRegionCode = $orderInfo['billing']['region'];
            }
        } else {
            $billRegionCode = $orderInfo['billing']['city'];
        }

        if (!empty($orderInfo['shipping']['region'])) {
            if (!empty($orderInfo['shipping']['region_id'])) {
                $shipRegionCode = get_region_code($orderInfo['shipping']['region_id']);
            } else {
                $shipRegionCode = $orderInfo['shipping']['region'];
            }
        } else {
            $shipRegionCode = $orderInfo['shipping']['city'];
        }

        // 多个ip切割为单个
        if (isset($_SESSION['customer_ip_address']) && !empty($_SESSION['customer_ip_address'])){
            $sourceIp = $_SESSION['customer_ip_address'];
        } else {
            $sourceIp = get_ip_address();
        }

        if (strpos($sourceIp, ',') != false) {
            $sourceIpAtr = explode(',', $sourceIp);
            $sourceIp    = reset($sourceIpAtr);
        }

        $request_type = (((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')))
            ||(isset($_SERVER['HTTP_X_FORWARDED_BY']) && strpos(strtoupper($_SERVER['HTTP_X_FORWARDED_BY']), 'SSL') !== false)
            ||(isset($_SERVER['HTTP_X_FORWARDED_HOST']) && (strpos(strtoupper($_SERVER['HTTP_X_FORWARDED_HOST']), 'SSL') !== false
                    ||strpos(strtoupper($_SERVER['HTTP_X_FORWARDED_HOST']), str_replace('https://', '', HTTPS_SERVER)) !== false))
            ||(isset($_SERVER['SCRIPT_URI']) && strtolower(substr($_SERVER['SCRIPT_URI'], 0, 6)) == 'https:')
            ||(isset($_SERVER["HTTP_SSLSESSIONID"]) && $_SERVER["HTTP_SSLSESSIONID"] != '')
            ||(isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443')) ? 'SSL' : 'NONSSL';

        $data = array(
            'merchant_id'     => trim($payment->get_account()),
            'app_id'          => trim($payment->get_mark1()),
            'order_no'        => put_orderNO($orderInfo['order_id']),
            'order_currency'  => $orderInfo['currency']['code'],
            'order_amount'    => $currencies->get_price($orderInfo['order_total'], $orderInfo['currency']['code'], $orderInfo['currency']['value']),
            'order_items'     => $goods,
            'card_no'         => $payment->get_is_inside() == 1 ? $_SESSION['ipasspay_card']['number'] : $_POST['ipasspay_card_number'],
            'card_ex_year'    => $payment->get_is_inside() == 1 ? $_SESSION['ipasspay_card']['year'] : $_POST['ipasspay_card_year'],
            'card_ex_month'   => $payment->get_is_inside() == 1 ? $_SESSION['ipasspay_card']['month'] : $_POST['ipasspay_card_month'],
            'card_cvv'        => $payment->get_is_inside() == 1 ? $_SESSION['ipasspay_card']['cvv'] : $_POST['ipasspay_card_cvv'],
            'source_ip'       => $sourceIp,
            'source_url'      => $request_type == 'SSL' ? HTTPS_SERVER : HTTP_SERVER,
            'asyn_notify_url' => href_link('ipasspay_notify', '', 'SSL'),
            'syn_notify_url'  => href_link(FILENAME_CHECKOUT_RESULT, '', 'SSL'),
            'version'         => '3.0',
            'bill_firstname'  => $orderInfo['billing']['firstname'],
            'bill_lastname'   => $orderInfo['billing']['lastname'],
            'bill_email'      => $orderInfo['customer']['email_address'],
            'bill_phone'      => $orderInfo['billing']['telephone'],
            'bill_country'    => $billCountryIso['iso_code_2'],
            'bill_state'      => $billRegionCode,
            'bill_city'       => $orderInfo['billing']['city'],
            'bill_street'     => $orderInfo['billing']['street_address'],
            'bill_zip'        => $orderInfo['billing']['postcode'],
            'ship_firstname'  => $orderInfo['shipping']['firstname'],
            'ship_lastname'   => $orderInfo['shipping']['lastname'],
            'ship_email'      => $orderInfo['customer']['email_address'],
            'ship_phone'      => $orderInfo['shipping']['telephone'],
            'ship_country'    => $shipCountryIso['iso_code_2'],
            'ship_state'      => $shipRegionCode,
            'ship_city'       => $orderInfo['shipping']['city'],
            'ship_street'     => $orderInfo['shipping']['street_address'],
            'ship_zip'        => $orderInfo['shipping']['postcode']
        );

        // 拼接参数获取密钥
        $signString        = $data['merchant_id'] . $data['app_id'] . $data['order_no'] . $data['order_amount'] . $data['order_currency'] . trim($payment->get_md5key());
        $data['signature'] = hash('sha256', $signString);

        // curl请求
        $result = $this->post($payment->get_submit_url(), $data);
        $result = json_decode($result, true);

        if ($result['data']['pay_mode'] == '3D' && $result['data']['order_status'] == '1') {
            $redirect_url = urldecode($result['data']['pay_url']);
            Header("HTTP/1.1 303 See Other");
            Header("Location: $redirect_url");
        } else {
            // 拼接form表单字符串
            $paymentForm = '<form method="post" action="' . href_link(FILENAME_CHECKOUT_RESULT, '', 'SSL') . '" id="checkout" name="checkout" target="_top">' . "\n";

            foreach ($result['data'] as $key => $val) {
                $paymentForm .= '<input type="hidden" value="' . urlencode($val) . '" name="' . $key . '">' . "\n";
            }

            $paymentForm .= '</form>' . "\n";
            $paymentForm .= '<script type="text/javascript">' . "\n";
            $paymentForm .= '$(function() {' . "\n";
            $paymentForm .= 'document.checkout.submit();' . "\n";
            $paymentForm .= '});' . "\n";
            $paymentForm .= '</script>' . "\n";

            echo $paymentForm;

            die;
        }
    }

    function result($payment)
    {
        global $orderInfo;

        $result      = array('order_status_id' => 0, 'billing' => '-', 'remarks' => '');
        $merchant_id = trim($payment->get_account());
        $app_id      = trim($payment->get_mark1());
        $api_secret  = trim($payment->get_md5key());

        $_REQUEST   = array_map('urldecode', $_REQUEST);

        $signature       = hash('sha256', $merchant_id . $app_id . $_REQUEST['order_no'] . $_REQUEST['gateway_order_no'] . $_REQUEST['order_currency'] . $_REQUEST['order_amount'] . $_REQUEST['order_status'] . $api_secret);
        $signatureReturn = $_REQUEST['signature'];

        if ($signature == $signatureReturn) {
            if ($_REQUEST['order_status'] == '2') {
                $result['order_status_id'] = 3;
            } else if ($_REQUEST['order_status'] == '1'){
                $result['order_status_id'] = 1;
            } else {
                $result['order_status_id'] = 4;
            }
            $result['remarks'] = $_REQUEST['errmsg'];
        } else {
            $result['order_status_id'] = 4;
            $result['remarks']         = 'Authentication failed';
        };
        return $result;
    }

    /**
     * @param $url 请求路径
     * @param array $data 请求参数
     * @return bool|string
     */
    public function post($url, array $data)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $response = curl_exec($ch);
        $errno    = curl_errno($ch);
        if ($errno > 0) {
            $info          = curl_getinfo($ch);
            $info['errno'] = $errno;
        }
        curl_close($ch);

        return $response;
    }
}
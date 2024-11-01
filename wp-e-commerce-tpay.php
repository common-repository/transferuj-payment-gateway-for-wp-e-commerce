<?php

/*
  Plugin Name: Tpay Payment Gateway for WP e-Commerce
  Plugin URI: Tpay.com
  Description: Bramka płatnosci tpay.com dla dodatku WP e-Commerce 
  Version: 2.0.1
  Author: tpay.com
  Author URI: https://tpay.com
 */

global $nzshpcrt_gateways, $num;
$nzshpcrt_gateways[$num] = [
    'name'            => 'tpay',
    'internalname'    => 'tpay',
    'function'        => 'tpay_gateway',
    'form'            => 'tpay_form',
    'submit_function' => 'tpay_submit',
    'display_name'    => 'Tpay.com',
    'requirements'    => [
        'php_version'   => 5.6,
        'extra_modules' => [],
    ],
];

use tpayLibs\examples\BasicPaymentForm;
use tpayLibs\examples\TransactionNotification;
use tpayLibs\src\_class_tpay\Utilities\Util;

spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = 'tpayLibs';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/' . $prefix;

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it

    if (file_exists($file)) {
        require $file;
    }
});

$tpay = new TpayPaymentGateway();

class TpayPaymentGateway
{

    const OPTIONS = ['transferuj_merchantid', 'transferuj_secretpass', 'transferuj_view'];

    protected $id;

    protected $secret;

    public function __construct()
    {
        add_action('init', array(&$this, 'callback'));

        // Set default values.
        foreach (static::OPTIONS as $key) {
            if (empty(get_option($key))) {
                update_option($key, 0);
            }
        }
        $this->id = (int)get_option('transferuj_merchantid');
        $this->secret = $secretKey = get_option('transferuj_secretpass');
    }

    // Process the callback and replies from tpay.
    public function callback()
    {
        $is_callback = (isset($_GET['tpay_callback']) && (int)$_GET['tpay_callback'] === 1) ? true : false;
        // Process the callback.
        if ($is_callback) {
            $res = (new TransactionNotification($this->id, $this->secret))->checkPayment();
            $sessionId = trim(stripslashes(base64_decode($res['tr_crc'])));

            if ($res['tr_status'] === 'TRUE' && $res['tr_error'] === 'none') {

                $purchase_log = new WPSC_Purchase_Log($sessionId, 'sessionid');

                if (!$purchase_log->exists() || $purchase_log->is_transaction_completed()) {
                    return;
                }
                // Order is accepted.
                $notes = "Transakcja opłacona w systemie Tpay.com : " . $res['tr_id'];
                $notes = sanitize_text_field($notes);
                $purchase_log->set('processed', WPSC_Purchase_Log::ACCEPTED_PAYMENT);
                $purchase_log->set('transactid', $res['tr_id']);
                $purchase_log->set('notes', $notes);
                $purchase_log->save();
            } else {
                $purchase_log = new WPSC_Purchase_Log($sessionId, 'sessionid');
                if (!$purchase_log->exists() || $purchase_log->is_transaction_completed()) {
                    return;
                }
                $notes = "Transakcja  nie została opłacona poprawnie w Tpay.com : " . $res['tr_id'];
                $purchase_log->set('transactid', $res['tr_id']);
                $purchase_log->set('notes', $notes);
                $purchase_log->save();
            }
        }
    }

    public function getAdminOptions()
    {
        return $this->parseContent('adminOptions.phtml');
    }

    private function parseContent($content)
    {
        $buffer = false;

        if (ob_get_length() > 0) {
            $buffer = ob_get_contents();
            ob_clean();
        }
        ob_start();
        include_once $content;
        $parsedHTML = ob_get_contents();
        ob_clean();

        if ($buffer !== false) {
            ob_start();
            echo $buffer;
        }

        return $parsedHTML;
    }

    public function saveConfig()
    {
        foreach (static::OPTIONS as $key) {
            if (isset($_POST[$key]) && !is_null($_POST[$key])) {
                update_option($key, sanitize_text_field($_POST[$key]));
            }
        }
        return true;

    }

// Validates and saves the settings .

    public function getPaymentForm($seperator, $sessionId)
    {
        global $wpdb, $wpsc_cart;

        $transaction_id = uniqid(md5(rand(1, 666)),
            true); // Set the transaction id to a unique value for reference in the system.
        $time = time();
        $wpdb->query($wpdb->prepare("UPDATE " . WPSC_TABLE_PURCHASE_LOGS . " SET processed = '1', transactid = %s, date = '%%' WHERE sessionid =%s  LIMIT 1",
            $transaction_id, $time, $sessionId));

        $purchase_log = $wpdb->get_row($wpdb->prepare("SELECT * FROM `" . WPSC_TABLE_PURCHASE_LOGS . "` WHERE `sessionid`= %s LIMIT 1",
            $sessionId), ARRAY_A);

        $usersql = $wpdb->prepare("SELECT `" . WPSC_TABLE_SUBMITED_FORM_DATA . "`.value,
	`" . WPSC_TABLE_CHECKOUT_FORMS . "`.`name`,
	`" . WPSC_TABLE_CHECKOUT_FORMS . "`.`unique_name` FROM
	`" . WPSC_TABLE_CHECKOUT_FORMS . "` LEFT JOIN
	`" . WPSC_TABLE_SUBMITED_FORM_DATA . "` ON
	`" . WPSC_TABLE_CHECKOUT_FORMS . "`.id =
	`" . WPSC_TABLE_SUBMITED_FORM_DATA . "`.`form_id` WHERE
	`" . WPSC_TABLE_SUBMITED_FORM_DATA . "`.`log_id`=%s
	ORDER BY `" . WPSC_TABLE_CHECKOUT_FORMS . "`.`checkout_order`", $purchase_log['id']);

        $userInfo = $wpdb->get_results($usersql, ARRAY_A);
        $orderFields = [
            'billingfirstname' => 'imie',
            'billinglastname'  => 'nazwisko',
            'billingaddress'   => 'adres',
            'billingcity'      => 'miasto',
            'billingemail'     => 'email',
            'billingphone'     => 'telefon',
            'billingcountry'   => 'kraj',
            'billingpostcode'  => 'kod',
        ];
        $paymentFields = [
            'opis'         => 'Zamówienie nr ' . $wpdb->get_var($wpdb->prepare("SELECT id FROM " .
                    WPSC_TABLE_PURCHASE_LOGS . " WHERE sessionid = '%s' LIMIT 1;", $sessionId)),
            'crc'          => base64_encode($sessionId),
            'kwota'        => round($wpsc_cart->total_price, 2),
            'wyn_url'      => $this->getCallbackUrl(),
            'pow_url'      => $this->getSuccessUrl($transaction_id, $sessionId),
            'pow_url_blad' => $this->getErrorUrl($transaction_id, $sessionId),
        ];

        foreach ($userInfo as $key => $value) {

            foreach ($orderFields as $key1 => $value1) {
                if ($value['unique_name'] === $key1) {
                    $paymentFields[$value1] = $value['value'];
                }
            }
        }
        // Generate the form output.
        $formsProvider = new BasicPaymentForm($this->id, $this->secret);
        Util::$path = plugins_url('transferuj-payment-gateway-for-wp-e-commerce/tpayLibs/src/');
        switch ((int)get_option('transferuj_view')) {
            case 0:
                echo $formsProvider->getBankSelectionForm($paymentFields, false, false);
                break;
            case 1:
                echo $formsProvider->getBankSelectionForm($paymentFields, true, false);
                break;
            case 2:
                echo $formsProvider->getTransactionForm($paymentFields, true);
                break;

        }
        exit();
    }

    private function getCallbackUrl()
    {
        $callbackUrl = get_option('siteurl');

        $string_end = substr($callbackUrl, strlen($callbackUrl) - 1);

        if ($string_end != '/') {
            $callbackUrl .= '/';
        }

        $params = ['tpay_callback' => '1',];
        if (is_ssl()) {
            $callbackUrl = str_replace('http://', 'https://', $callbackUrl);
        }
        return add_query_arg($params, $callbackUrl);
    }

    private function getSuccessUrl($transaction_id, $session_id)
    {
        $successUrl = get_option('transact_url');

        $params = array('tpay_accept' => '1', 'transactionId' => $transaction_id, 'sessionid' => $session_id);
        return add_query_arg($params, $successUrl);
    }

    private function getErrorUrl($transactionId, $sessionId)
    {
        $errorUrl = get_option('shopping_cart_url');

        $params = array('tpay_cancel' => '1', 'transactionId' => $transactionId, 'sessionid' => $sessionId);
        return add_query_arg($params, $errorUrl);
    }

    private function tpay_form_hint($s)
    {
        return '<small style="line-height:14px;display:block;padding:2px 0 6px;">' . $s . '</small>';
    }

}

function tpay_gateway($separates, $sessionId)
{
    (new TpayPaymentGateway())->getPaymentForm($separates, $sessionId);
}

function tpay_submit()
{
    (new TpayPaymentGateway())->saveConfig();
}

function tpay_form()
{
    return (new TpayPaymentGateway())->getAdminOptions();
}

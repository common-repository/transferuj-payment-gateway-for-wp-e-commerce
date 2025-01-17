<?php

/*
 * Created by tpay.com
 */

namespace tpayLibs\src\_class_tpay\Utilities;

use Exception;

/**
 * Class TException
 *
 * @package tpay
 */
class TException extends Exception
{
    /**
     * @param string $message error message
     * @param int $code error code
     */
    public function __construct($message, $code = 0)
    {
        require_once(dirname(__FILE__) . '/Util.php');

        $message .= ' in file ' . $this->getFile() . ' line: ' . $this->getLine();
        Util::log('TException', $message . "\n\n" . $this->getTraceAsString());

        $this->message = $code . ' : ' . $message;
        return $code . ' : ' . $message;
    }
}

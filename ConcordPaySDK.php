<?php

/**
 * ConcordPay Payment Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category        ConcordPay
 * @package         webdevconcord/concordpay
 * @version         1.0
 * @author          ConcordPay
 * @copyright       Copyright (c) 2021 ConcordPay
 * @license         http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 *
 * EXTENSION INFORMATION
 *
 * ConcordPay API   https://pay.concord.ua/docs/docs/en/dispatcher/
 *
 */

/**
 * Payment method ConcordPay process
 *
 * @author ConcordPay
 */
class ConcordPaySDK
{
    const CURRENCY_UAH     = 'UAH';
    const FIELDS_DELIMITER = ';';

    const PURCHASE_URL = 'https://pay.concord.ua/api/';
    const REVERSE_URL  = 'https://pay.concord.ua/api/reverse';
    const CHECK_URL    = 'https://pay.concord.ua/api/check';
    const BALANCE_URL  = 'https://pay.concord.ua/api/balance';

    const MASTERPASS_TOKEN_URL = 'https://pay.concord.ua/api/mptoken';

    const MODE_PURCHASE    = 'Purchase';
    const MODE_CALLBACK    = 'Callback';
    const MODE_REC_PAYMENT = 'RecPayment';
    const MODE_VERIFY      = 'Verify';
    const MODE_REVERSE     = 'Reverse';
    const MODE_COMPLETE    = 'Complete';
    const MODE_CHECK       = 'Check';
    const MODE_P2P_CREDIT  = 'P2PCredit';
    const MODE_GET_BALANCE = 'GetBalance';
    const MODE_P2PDEBIT    = 'P2PDebit';
    const MODE_CONFIRM_3DS = '3DS';

    const MODE_PURCHASE_ON_MERCHANT  = 'PurchaseOnMerchant';
    const MODE_GET_TOKEN_MASTER_PASS = 'TOKEN_MASTER_PASS';
    const MODE_PURCHASE_MASTER_PASS  = 'purchaseMasterpass';

    /** @var string */
    private $apiUrl = "https://pay.concord.ua/api/";

    /** @var string */
    private $privateKey;

    /** @var string */
    private $action;

    /** @var array */
    private $params;

    /** @var string[] */
    protected $supportedCurrencies = array(
        self::CURRENCY_UAH
    );

    /** @var int */
    protected $server_response_code;

    /**
     * ConcordPaySDK constructor.
     *
     * @param $privateKey
     * @param null $apiUrl
     */
    public function __construct($privateKey, $apiUrl = null)
    {
        if (empty($privateKey)) {
            throw new InvalidArgumentException('private_key is empty');
        }

        $this->privateKey = $privateKey;

        if (null !== $apiUrl) {
            $this->apiUrl = $apiUrl;
        }
    }

    /**
     * Generate form with correct POST data
     *
     * @return string
     */
    private function buildForm()
    {
        $form = sprintf('<form method="POST" action="%s" accept-charset="utf-8"  id="sdk-concord">', $this->apiUrl);

        foreach ($this->params as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $sub_key => $field) {
                    /* Split payments and add_params block*/
                    if (is_array($field)) {
                        foreach ($field as $split_key => $item) {
                            $form .= sprintf('<input type="hidden" name="%s" value="%s" />', $key . '[' . $sub_key . ']'. '[' . $split_key . ']', htmlspecialchars($item));
                        }
                    } else {
                        $form .= sprintf('<input type="hidden" name="%s" value="%s" />', $key . '[' . $sub_key . ']', htmlspecialchars($field));
                    }
                    /* END Split payments and add_params block */
                }
            } else {
                $form .= sprintf('<input type="hidden" name="%s" value="%s" />', $key, htmlspecialchars($value));
            }
        }

        $form .= '<input type="submit" value="Submit"></form>';

        return $form;
    }

    /**
     * @param $fields
     * @return string
     */
    public function purchase($fields)
    {
        $this->prepare(self::MODE_PURCHASE, $fields);
        $this->apiUrl = self::PURCHASE_URL;

        return $this->buildForm();
    }

    /**
     * @param $fields
     * @return string
     */
    public function callback($fields)
    {
        $this->prepare(self::MODE_CALLBACK, $fields);

        return $this->buildForm();
    }

    /**
     * @param $fields
     * @return bool|string
     */
    public function recPayment($fields)
    {
        $this->prepare(self::MODE_REC_PAYMENT, $fields);

        return  $this->query();
    }

    /**
     * @param $fields
     * @return bool|string
     */
    public function verify($fields)
    {
        $this->prepare(self::MODE_VERIFY, $fields);

        return  $this->query();
    }

    /**
     * @param $fields
     * @return mixed
     */
    public function reverse($fields)
    {
        $this->prepare(self::MODE_REVERSE, $fields);
        $this->apiUrl = self::REVERSE_URL;

        return $this->query();
    }

    /**
     * @param array $fields
     * @return mixed
     */
    public function complete($fields)
    {
        $this->prepare(self::MODE_COMPLETE, $fields);

        return $this->query();
    }

    /**
     * @param array $fields
     * @return mixed
     */
    public function check($fields)
    {
        $this->prepare(self::MODE_CHECK, $fields);
        $this->apiUrl = self::CHECK_URL;

        return $this->query();
    }

    /**
     * @param array $fields
     * @return mixed
     */
    public function p2pCredit($fields)
    {
        $this->prepare(self::MODE_P2P_CREDIT, $fields);

        return $this->buildForm();
    }

    /**
     * @param array $fields
     * @return mixed
     */
    public function getBalance($fields)
    {
        $this->prepare(self::MODE_GET_BALANCE, $fields);
        $this->apiUrl = self::BALANCE_URL;

        return $this->query();
    }

    /**
     * @param array $fields
     * @return mixed
     */
    public function p2pDebit($fields)
    {
        $this->prepare(self::MODE_P2PDEBIT, $fields);

        return $this->buildForm();
    }

    /**
     * @param  $fields
     * @return mixed
     */
    public function purchaseOnMerchant($fields)
    {
        $this->prepare(self::MODE_PURCHASE_ON_MERCHANT, $fields);
        $this->apiUrl = self::PURCHASE_URL;

        return $this->buildForm();
    }

    /**
     * @param array $fields
     * @return mixed
     */
    public function getMasterpassToken($fields)
    {
        $this->prepare(self::MODE_GET_TOKEN_MASTER_PASS, $fields);
        $this->apiUrl = self::MASTERPASS_TOKEN_URL;

        return $this->query();
    }

    /**
     * @param $fields
     * @return bool|string
     */
    public function purchaseMasterpass($fields)
    {
        $this->prepare(self::MODE_PURCHASE_MASTER_PASS, $fields);

        return $this->query();
    }

    /**
     * @param array $fields
     * @return mixed
     */
    public function confirm3DS($fields)
    {
        $this->prepare(self::MODE_CONFIRM_3DS, $fields);

        return $this->query();
    }

    /**
     * @return bool|string
     */
    private function query()
    {
        $postfields = http_build_query($this->params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        $this->server_response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $server_output;
//        return json_decode($server_output);
    }

    /**
     * @param $action
     * @param array $params
     * @throws \InvalidArgumentException
     */
    private function prepare($action, array $params)
    {
        $this->action = $action;

        if (empty($params)) {
            throw new \InvalidArgumentException('Arguments must be not empty');
        }

        $this->params = $params;
        $this->params['signature'] = $this->buildSignature();
        $this->checkFields();
    }

    /**
     * Check required fields
     *
     * @return bool
     */
    private function checkFields()
    {
        $required = $this->getRequiredFields();
        $error = array();

        foreach ($required as $item) {
            if (array_key_exists($item, $this->params)) {
                if (empty($this->params[$item])) {
                    $error[] = $item;
                }
            } else {
                $error[] = $item;
            }
        }

        if (!empty($error)) {
            throw new \InvalidArgumentException('Missed required field(s): ' . implode(', ', $error) . '.');
        }

        return true;
    }

    /**
     * Required fields
     *
     * @return array
     */
    private function getRequiredFields()
    {
        switch ($this->action) {
            case self::MODE_PURCHASE:
                return array(
                    'operation',
                    'merchant_id',
                    'amount',
                    'signature',
                    'order_id',
                    'currency_iso',
                    'description',
                    'approve_url',
                    'decline_url',
                    'cancel_url',
                    'callback_url'
                );
            case self::MODE_REC_PAYMENT:
                return array(
                    'operation',
                    'merchant_id',
                    'amount',
                    'recurring_token',
                    'order_id',
                    'description',
                    'currency_iso',
                    'signature'
                );
            case self::MODE_VERIFY:
                return array(
                    'operation',
                    'merchant_id',
                    'order_id',
                    'amount',
                    'currency_iso',
                    'approve_url',
                    'decline_url',
                    'cancel_url',
                    'callback_url',
                    'signature'
                );
            case self::MODE_CHECK:
            case self::MODE_REVERSE:
                return array(
                    'merchant_id',
                    'order_id',
                    'signature'
                );
            case self::MODE_COMPLETE:
                return array(
                    'merchant_id',
                    'operation',
                    'order_id',
                    'amount',
                    'signature'
                );
            case self::MODE_P2P_CREDIT:
                return array(
                    'operation',
                    'merchant_id',
                    'order_id',
                    'amount',
                    'card_number',
                    'currency_iso',
                    'signature'
                );
            case self::MODE_GET_BALANCE:
                return array(
                    'operation',
                    'merchant_id',
                    'date',
                    'signature'
                );
            case self::MODE_P2PDEBIT:
                return array(
                    'operation',
                    'merchant_id',
                    'order_id',
                    'amount',
                    'currency_iso',
                    'description',
                    'approve_url',
                    'decline_url',
                    'cancel_url',
                    'signature'
                );
            case self::MODE_PURCHASE_ON_MERCHANT:
                return array(
                    'operation',
                    'merchant_id',
                    'signature',
                    'order_id',
                    'amount',
                    'card_num',
                    'card_exp_month',
                    'card_exp_year',
                    'card_cvv',
                    'card_holder',
                    'payment_type',
                    'secure_type',
                    'currency_iso',
                    'description',
                    'callback_url',
                    'signature'
                );
            case self::MODE_GET_TOKEN_MASTER_PASS:
                return array(
                    'msisdn',
                    'client_id'
                );
            case self::MODE_PURCHASE_MASTER_PASS:
                return array(
                    'operation',
                    'merchant_id',
                    'amount',
                    'order_id',
                    'currency_iso',
                    'description',
                    'approve_url',
                    'decline_url',
                    'cancel_url',
                    'callback_url',
                    'add_params',
                    'signature'
                );

            case self::MODE_CONFIRM_3DS:
                return array(
                    'operation',
                    'transaction_key',
                    'merchant_id',
                    'd3ds_md',
                    'd3ds_pares',
                    'signature'
                );
            case self::MODE_CALLBACK:
                return array(
                    'merchantAccount',
                    'orderReference',
                    'amount',
                    'currency'
                );
            default:
                throw new \InvalidArgumentException('Unknown transaction type');
        }
    }

    /**
     * @param $fields
     * @return string
     */
    public function createSignature($fields)
    {
        return hash_hmac('md5', implode(self::FIELDS_DELIMITER, $fields), $this->privateKey);
    }

    /**
     * Generate signature hash
     *
     * @return string
     */
    private function buildSignature()
    {
        $signFields = $this->getFieldsNameForSignature();
        $data = array();
        $error = array();

        foreach ($signFields as $item) {
            if (array_key_exists($item, $this->params)) {
                $value = $this->params[$item];
                if (is_array($value)) {
                    $data[] = implode(self::FIELDS_DELIMITER, $value);
                } else {
                    $data[] = (string)$value;
                }
            } else {
                $error[] = $item;
            }
        }

        if (!empty($error)) {
            throw new \InvalidArgumentException('Missed signature field(s): ' . implode(', ', $error) . '.');
        }

        return hash_hmac('md5', implode(self::FIELDS_DELIMITER, $data), $this->privateKey);
    }


    /**
     * Signature fields
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    private function getFieldsNameForSignature()
    {
        switch ($this->action) {
            case self::MODE_PURCHASE:
                return array(
                    'merchant_id',
                    'order_id',
                    'amount',
                    'currency_iso',
                    'description'
                );
            case self::MODE_CALLBACK:
                return array(
                    'merchantAccount',
                    'orderReference',
                    'amount',
                    'currency'
                );
            case self::MODE_REC_PAYMENT:
                return array(
                    'merchant_id',
                    'order_id',
                    'amount',
                    'recurring_token',
                    'currency_iso',
                    'description'
                );
            case self::MODE_VERIFY:
                return array(
                    'merchant_id',
                    'order_id',
                    'amount',
                    'currency_iso'
                );
            case self::MODE_REVERSE:
                return array(
                    'merchant_id',
                    'order_id'
                );
            case self::MODE_COMPLETE:
                return array(
                    'merchant_id',
                    'order_id',
                    'amount'
                );
            case self::MODE_CHECK:
                return array(
                    'merchant_id',
                    'order_id'
                );
            case self::MODE_P2P_CREDIT:
                return array(
                    'merchant_id',
                    'order_id',
                    'amount',
                    'card_number',
                    'currency_iso'
                );
            case self::MODE_GET_BALANCE:
                return array(
                    'merchant_id',
                    'date'
                );
            case self::MODE_P2PDEBIT:
                return array(
                    'merchant_id',
                    'order_id',
                    'amount',
                    'currency_iso',
                    'description',
                    'approve_url',
                    'decline_url',
                    'cancel_url'
                );
            case self::MODE_PURCHASE_MASTER_PASS:
            case self::MODE_PURCHASE_ON_MERCHANT:
                return array(
                    'merchant_id',
                    'order_id',
                    'amount',
                    'currency_iso',
                    'description'
                );
            case self::MODE_CONFIRM_3DS:
                return array(
                    'merchant_id',
                    'transaction_key',
                    'd3ds_md',
                    'd3ds_pares'
                );
            case self::MODE_GET_TOKEN_MASTER_PASS:
                return array();
            default:
                throw new \InvalidArgumentException('Unknown transaction type: ' . $this->action);
        }
    }

    /**
     * @param $url
     * @param array $data
     * @param array|null $headers
     * @throws Exception
     */
    public function redirect_post($url, array $data, array $headers = null)
    {
        $data['signature'] = $this->buildSignature();

        $params = array(
            'http' => array(
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        if ($headers !== null) {
            $params['http']['header'] = '';
            foreach ($headers as $k => $v) {
                $params['http']['header'] .= "$k: $v\n";
            }
        }
        $ctx = stream_context_create($params);
        $fp = @fopen($url, 'rb', false, $ctx);
        if ($fp) {
            echo @stream_get_contents($fp);
            die();
        }

        // Error
        throw new Exception("Error loading '$url', $php_errormsg");
    }
}

<?php
require_once('ConcordPaySDK.php');
if (file_exists('./env.php')) {
    include './env.php'; // define  const PRIVATE_KEY, MERCHANT_ID etc in env.php
}

const APPROVE_URL  = 'http://sdk.loc/approve';
const DECLINE_URL  = 'http://sdk.loc/decline';
const CANCEL_URL   = 'http://sdk.loc/cancel';
const CALLBACK_URL = 'http://sdk.loc/callback';

$sdk = new ConcordPaySDK(PRIVATE_KEY);

#-----------------------Payment on the payment processing page--------------------------------------------------------

//$params = [
//    'operation'    => 'Purchase',
//    'merchant_id'  => MERCHANT_ID,
//    'amount'       => 79,
//    'order_id'     => 1687,
//    'currency_iso' => 'UAH',
//    'description'  => 'Оплата картой на сайте sdk.loc, Petro Petrenko, +38-050-734-5005',
//    'add_params'   => [],
//    'approve_url'  => APPROVE_URL,
//    'decline_url'  => DECLINE_URL,
//    'cancel_url'   => CANCEL_URL,
//    'callback_url' => CALLBACK_URL
//];
// echo $sdk->purchase($params);

#-----------------------Callback to send payment status notification--------------------------

// Attention! This is not a complete list of the payment system server response parameters!
// Only those parameters are listed here that are required to generate the signature.
$params = [
    'merchantAccount' => MERCHANT_ID,
    'orderReference'  => htmlspecialchars($_GET['orderReference']),
    'amount'          => htmlspecialchars($_GET['amount']),
    'currency'        => 'UAH'
];
echo $sdk->callback($params);

#-----------------------RecPayment--------------------------------------------------------------------------

//$params = [
//    'operation'       => 'RecPayment',
//    'merchant_id'     => MERCHANT_ID,
//    'amount'          => 0.3,
//    'order_id'        => 90,
//    'currency_iso'    => 'UAH',
//    'recurring_token' => TOKEN,
//    'description'     => 'Рекуррентный платеж. Тестирование SDK',
//];
//
//echo  $sdk->recPayment($params);

#-----------------------Verify---------------------------------------------------

//$params = [
//    'operation'    => 'Verify',
//    'merchant_id'  => MERCHANT_ID,
//    'order_id'     => 'WgtRVIaKHy',
//    'amount'       => 0.3,
//    'currency_iso' => 'UAH',
//    'approve_url'  => APPROVE_URL,
//    'decline_url'  => DECLINE_URL,
//    'cancel_url'   => CANCEL_URL,
//    'callback_url' => CALLBACK_URL
//];
//
//print_r($sdk->verify($params));

#-----------------------Reverse------------------------------------------------------

//$reverse = [
//    'merchant_id' => MERCHANT_ID,
//    'order_id'    => 60
//];
//$data = $sdk->reverse($reverse);
//print_r($data);

#-----------------------Complete----------------------------------------------------

//$complete = [
//    'operation'   => 'Complete',
//    'merchant_id' => MERCHANT_ID,
//    'order_id'    => 60,
//    'amount'      => 0.3
//];
//$data = $sdk->complete($complete);
//print_r($data);

#-----------------------Check----------------------------------------------------

//$check = [
//    'merchant_id' => MERCHANT_ID,
//    'order_id'    => 60
//];
//
//$data = $sdk->check($check);
//print_r($data);

//Пример ответа:
//stdClass Object(
//    [code] => 0
//    [merchantAccount]   => abyrvalg
//    [orderReference]    => 60
//    [amount]            => 0.10
//    [currency]          => UAH
//    [phone]             => 380970001234
//    [createdDate]       => 2020 - 04 - 21 17:14:49
//    [cardPan]           => 403021 ******9876
//    [cardType]          => Visa
//    [fee]               => 0.00
//    [transactionId]     => 28122001
//    [transactionStatus] => REFUNDED
//    [reverseAmount]     => 0.10
//    [reverseDate]       => 2020 - 04 - 21 19:07:48
//    [reason]            => ОПЕРАЦИЯ РАЗРЕШЕНА
//    [reasonCode]        => 1
//)

#-----------------------P2PCredit----------------------------------------------------

//$params = [
//    'operation'    => 'P2PCredit',
//    'merchant_id'  => MERCHANT_ID,
//    'order_id'     => 601,
//    'amount'       => 1,
//    'card_number'  => CARD_HOLDER,
//    'currency_iso' => 'UAH',
//    'add_params'   => []
//];
//
//$data = $sdk->p2pCredit($params);
//print_r($data);

#-----------------------GetBalance----------------------------------------------------

//$params = [
//    'operation'   => 'GetBalance',
//    'merchant_id' => MERCHANT_ID,
//    'date'        => '2020-04-10 15:45:00' //YYYY-MM-DD HH:II:SS
//];
//$data = $sdk->getBalance($params);
//print_r($data);
// print_r($sdk->createSignature([$params['merchant_id'],$params['date']]));

#-----------------------P2PDebit--------------------------------------------------------

//$params = [
//    'operation'    => 'P2PDebit',
//    'merchant_id'  => MERCHANT_ID,
//    'amount'       => 1,
//    'order_id'     => 61,
//    'currency_iso' => 'UAH',
//    'description'  => 'test',
//    'approve_url'  => APPROVE_URL,
//    'decline_url'  => DECLINE_URL,
//    'cancel_url'   => CANCEL_URL,
//    'add_params'   => []
//];
//
//echo $sdk->p2pDebit($params);

#-----------------------PurchaseOnMerchant--------------------------------------------------------

//$params = [
//    'operation'      => 'PurchaseOnMerchant',
//    'merchant_id'    => MERCHANT_ID,
//    'amount'         => 1,
//    'order_id'       => 600,
//    'currency_iso'   => 'UAH',
//    'description'    => 'PurchaseOnMerchant test',
//    'add_params'     => [],
//    'card_num'       => CARD_NUM,
//    'card_exp_month' => CARD_EXP_MONTH,
//    'card_exp_year'  => CARD_EXP_YEAR,
//    'card_cvv'       => CARD_CVV,
//    'card_holder'    => CARD_HOLDER,
//    'payment_type'   => 'Purchase',
//    'secure_type'    => '3DS',
//    'callback_url'   => CALLBACK_URL
//];
//
//echo $sdk->purchaseOnMerchant($params);

#-----------------------Get a token for Masterpass--------------------------------------------------------

//$params = [
//    'msisdn'      => PHONE,
//    'client_id'   => '1234567890765',
//    'merchant_id' => MERCHANT_ID
//];
//
//$token = $sdk->getMasterpassToken($params);
//print_r($token);

#-----------------------Making a payment through Masterpass (uncomment the previous block to get the token)--------

//$params = [
//    'operation'    => 'PurchaseMasterpass',
//    'merchant_id'  => MERCHANT_ID,
//    'amount'       => 1,
//    'order_id'     => 23,
//    'currency_iso' => 'UAH',
//    'description'  => 'test123',
//    'approve_url'  => APPROVE_URL,
//    'decline_url'  => DECLINE_URL,
//    'cancel_url'   => CANCEL_URL,
//    'callback_url' => CALLBACK_URL,
//    'add_params' => [
//        'wallet'     => 'masterpass',
//        'msisdn'     => PHONE,
//        'token'      => $token,
//        'card_name'  => 'alias from server Master Pass',
//        'client_id'  => 'Client Id',
//        'ret_ref_no' => 'Number unic from Masterpass',
//    ]
//];
// print_r($sdk->purchaseMasterpass($params));

#-----------------------3DS--------------------------------------------------------

//$params = [
//    'operation'       => 'Complete3DS',
//    'transaction_key' => 'KEY',
//    'merchant_id'     => MERCHANT_ID,
//    'd3ds_md'         => D3DS_MD,
//    'd3ds_pares'      => D3DS_PARES,
//];
//print_r($sdk->confirm3DS($params));

#-----------------------Payment on the payment processing page with payment splitting--------------------

//$params = [
//    'operation'    => 'Purchase',
//    'merchant_id'  => MERCHANT_ID,
//    'amount'       => 79,
//    'order_id'     => 1687,
//    'currency_iso' => 'UAH',
//    'description'  => 'Оплата картой на сайте sdk.loc, Petro Petrenko, +38050-734-5005',
//    'add_params'   => [],
//    'split'        => 1,
//    'split_rules'  =>  [
//        [
//            'sub_merchant_id' => 'test1',
//            'amount'          => 50
//        ],
//        [
//            'sub_merchant_id' => 'test2',
//            'amount'          => 29
//        ]
//    ],
//    'approve_url'  => APPROVE_URL,
//    'decline_url'  => DECLINE_URL,
//    'cancel_url'   => CANCEL_URL,
//    'callback_url' => CALLBACK_URL
//];
//echo $sdk->purchase($params);
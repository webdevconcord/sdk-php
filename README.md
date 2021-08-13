# ConcordPay SDK-PHP

[ConcordPay SDK Documentation](https://pay.concord.ua/docs/docs/en/dispatcher/)

## Connect ConcordPaySDK.php to your project

    require_once('ConcordPaySDK.php');

## Create constants env.php file with constants:

    const PRIVATE_KEY = '';
    const MERCHANT_ID = '';

*The sample file (env.example.php) is in the root of the project.*

## Create Object SDK in your php code

    $sdk = new ConcordPaySDK(PRIVATE_KEY);

## Create array with necessary params

The list of params you can see in the [ConcordPay SDK documentation](https://pay.concord.ua/docs/docs/en/dispatcher/).
 
For Example:

    $params = [
        'operation'    => 'Purchase',
        'merchant_id'  => MERCHANT_ID,
        'amount'       => 0.1,
        'order_id'     => '60#20210810',
        'currency_iso' => 'UAH',
        'description'  => 'test',
        'add_params'   => [],
        'approve_url'  => 'http://sdk.loc/reciver.php',
        'decline_url'  => 'http://sdk.loc/decline',
        'callback_url' => 'http://sdk.loc/reciver.php',
        'cancel_url'   => 'http://sdk.loc/cancel'
    ];

In case you need to specify button *"Send"* you can use ID of the form : `sdk-concord`.

## Call method with params

For Example:

    echo $sdk->purchase($params); 

## Available methods are the same as in ConcordPay Documentation

| Method | Operation |
| --- | --- |
| purchase | Payment on the payment processing page |
| recPayment | Recurrent payments RecPayment (payment by token) |
| verify | Verify Operation |
| reversal | Operation Reversal |
| complete | Operation Complete |
| check | Operation Check |
| p2pCredit | Operation P2PCredit |
| getBalance | Get Balance Operation (GetBalance) |
| p2pDebit | Operation P2PDebit |
| purchaseOnMerchant | Operation PurchaseOnMerchant |
| getMasterpassToken | Getting a token for Masterpass |
| purchaseMasterpass | Making a payment through Masterpass |
| confirm3DS | The confirmation of 3DS Verification Verification |

    $sdk   = new ConcordPaySDK(PRIVATE_KEY);
    $token = $sdk->getMasterpassToken($params);
    ...

## Other

Each method in **ConcordPaySDK** can  return  `query` or method `buildForm` methods.
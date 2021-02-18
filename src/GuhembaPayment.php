<?php
namespace RWBuild\Guhemba;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use RWBuild\Guhemba\Traits\TransactionRequest;
use RWBuild\Guhemba\Exceptions\GuhembaPayException;

class GuhembaPayment
{
    use TransactionRequest;
    
    /**
     * Endpoint for generating a qrcode
     */
    private static $qrcodeUrl = 'third-party/generate-qrcode/payment';

    /**
     * endpoint to request for transaction info
     */
    private static $transactionUrl = 'third-party/transaction/exist';

    /**
     * endpoint to request for transaction info from a reference code
     */
    private static $transCodeUrl = 'third-party/transaction-from-code';

    /**
     * Url where user will be redirected when click on pay button
     */
    private static $redirectGuhembaUrl = 'rwpay-element/process-qrcode'; 

    private static $isPartner = false;

    /**
     * The param name of the redirect url
     * 
     * @var string: for partner integration it will be : dru
     */
    private static $redirectFieldName = 'redirect_url';

    /**
     * The response comming from guhemba
     */
    public $response = null;

    public static $dynamicKeys = [
        'GUHEMBA_API_KEY' => null,
        'GUHEMBA_MERCHANT_KEY' => null,
        'GUHEMBA_PUBLIC_KEY' => null,
        'GUHEMBA_BASE_URL' => null,
        'GUHEMBA-PARTNER_KEY' => null,
        'GUHEMBA-PUBLIC_PARTNER_KEY' => null,
        'GUHEMBA_REDIRECT_URL' => null
    ];

    /**
     * A payment reference when generating a qrcode
     * 
     * @var string
     */
    public static $paymentRef = null;

    public static $confirmPaymentKey = null;

    /**
     * Set partner keys 
     */
    public static function partnerKeys(array $partnerKeys)
    {
        static::$dynamicKeys = [
            'GUHEMBA_PUBLIC_PARTNER_KEY' => $partnerKeys['GUHEMBA_PUBLIC_PARTNER_KEY'],
            'GUHEMBA_PARTNER_KEY' => $partnerKeys['GUHEMBA_PARTNER_KEY'],
            'GUHEMBA_BASE_URL' => $partnerKeys['GUHEMBA_BASE_URL'],
        ];

        return new static;
    }

    /**
     * Get all config guhemba keys or get a single value of a passed key
     * 
     * This method will return keys from config file or from dynamic keys
     * depending on its integration
     * 
     * @return string|array
     */
    public static function getKeys($keyName = null)
    {
        if (! $keyName) {
            $keys = self::$isPartner ? 
                        self::$dynamicKeys : 
                        config('guhemba-webelement.option');
           
            if (! $keys && !self::$isPartner) throw new GuhembaPayException(
                'You should publish first the config tag'
            );

        } else {
            $keys = self::$isPartner ? 
                        self::$dynamicKeys[$keyName] :
                        config("guhemba-webelement.option.{$keyName}");
        }

        return $keys;
    }

    /**
     * This is applied to partener who manage multiple merchant wallet 
     * in his system
     * 
     * The provided array of keys should contain:
     * 
     * [
     * 
     *  'GUHEMBA_API_KEY' => '', // provided in merchant wallet->integration info
     * 
     *  'GUHEMBA_MERCHANT_KEY' => '', // the merchant key of a wallet
     * 
     *  'GUHEMBA_PUBLIC_KEY' => '', // provided in merchant wallet->integration info
     * 
     *  'GUHEMBA_BASE_URL' => '', // Guhemba url
     * 
     *  'GUHEMBA-PARTNER-KEY' => '', // it's the partner secret key
     * 
     *  'GUHEMBA_PUBLIC_PARTNER_KEY' => '',
     * 
     *  'GUHEMBA_REDIRECT_URL' => '' // the redirect url that guhemba will use on complete trans
     * 
     * ]
     * 
     * @param $dynamicKeys
     * 
     * @return self 
     */
    public static function dynamicMerchant(array $dynamicKeys)
    {
        static::$dynamicKeys = array_merge(static::$dynamicKeys, [
            'GUHEMBA_API_KEY' => $dynamicKeys['GUHEMBA_API_KEY'],
            
            'GUHEMBA_MERCHANT_KEY' => $dynamicKeys['GUHEMBA_MERCHANT_KEY'],

            'GUHEMBA_PUBLIC_KEY' => $dynamicKeys['GUHEMBA_PUBLIC_KEY'],

            'GUHEMBA_REDIRECT_URL' => $dynamicKeys['GUHEMBA_REDIRECT_URL'],
        ]);

        static::$isPartner = true;
        static::$redirectFieldName = 'dru';

        return new static;
    }

    /**
     * Guzzle client instance
     */
    private static function client()
    {
        return new Client();
    }

    /**
     * Grab the qrcode object received in the response
     * 
     * @return object
     */
    public function getQrcode()
    {
        return optional($this->response)->qrcode;
    }

    /**
     * Grab the transaction object received in the response
     * 
     * @return object
     */
    public function getTransaction()
    {
        return optional($this->response)->transaction;
    }

    /**
     * Grab the response of the request
     * 
     * @return object
     */
    public function getResponse()
    {
       return  $this->response;
    }

    /**
     * Check if the request was successfully done
     * 
     * @return boolean
     */
    public function isOk()
    {
       return  optional($this->response)->success;
    }

    /**
     * Grab the response message of the request
     * 
     * @return string
     */
    public function getMessage()
    {
       return  optional($this->response)->message;
    }

    /**
     * Add a slash on the base url then concatainate it with endpoint url
     * 
     * @return string
     */
    private static function joinUrl($baseUrl, $endpointUrl, $isWeb = false)
    {
        return Str::finish($baseUrl, '/') . ($isWeb ?'':'api/') .$endpointUrl;
    }

    /**
     * Request for a payment qrcode at guhemba
     * 
     * @param double $amount
     * @param string $paymentRef : the reference of the payment
     * @param string $confirmPaymentKey: security layer to authenticate feedback
     * 
     * @return self
     */
    public static function generateQrcode($amount, $paymentRef = null, $confirmPaymentKey = null)
    {
        $pay = new self();

        self::$paymentRef = $paymentRef;

        self::$confirmPaymentKey = $confirmPaymentKey;

        $pay->response = self::caller(
            'sendQrcodeRequest', 
            $amount
        );

        return $pay;
    }

    /**
     * Get the public information about a transaction using its
     * token
     * 
     * @return self
     */
    public static function transactionFromToken(string $token)
    {
        $pay = new self();

        $pay->response = self::caller('sendTransactionRequest', $token);

        return $pay;
    }

    /**
     * Get the public information about a transaction using the
     * code that reference a transaction: this method is used on
     * callback==> when a direct payment is done
     * 
     * @return self
     */
    public static function transaction()
    {
        $validateRequest = self::checkSessionState();

        $pay = new self();

        if ($validateRequest !== true) {
            $pay->response = $validateRequest;

            return $pay;
        }

        $pay->response = self::caller('sendTransactionCodeRequest');

        return $pay;
    }

    /**
     * Check if the callback request has the same state with 
     * the state sent in the redirect to guhemba request 
     * 
     * @return boolean|object
     */
    public static function checkSessionState()
    {
        
        $state = request()->session()->pull('guhemba_state');
        $requestState = request()->state;

        if (! $requestState) return self::fireError('Request state not available', 400, [
            'hint' => 'Please make sure you are coming from guhemba'
        ]);

        if (! $state) return self::fireError('Session state was not set', 400, [
            'hint' => 'Please make sure you have been using the same' . 
            'browser when completing payment on guhemba'
        ]);

        if ($state != $requestState) return self::fireError("Request state don't match", 400, [
            'hint' => 'Please make sure you are not using the callback url twice'
        ]);

        return true;
    }

    /**
     * Redirect user to guhemba when they hit the "pay button" on
     * the web element 
     * 
     * @param string $qrcodeSlug: the qrcode identifier
     * @param string $paymentRef: a code that refer to an order
     */
    public static function redirect(string $qrcodeSlug, string $paymentRef)
    {
        $keys = self::getKeys();
        $baseUrl = $keys['GUHEMBA_BASE_URL'];
        $url = self::joinUrl($baseUrl, self::$redirectGuhembaUrl, true);

        request()->session()
            ->put('guhemba_state', $state = Str::random(40));
        
        $query = http_build_query([
            'public_key' => $keys['GUHEMBA_PUBLIC_KEY'],
            static::$redirectFieldName => $keys['GUHEMBA_REDIRECT_URL'],
            'payment_ref' => $paymentRef,
            'state' => $state,
            'ppk' => $keys['GUHEMBA_PUBLIC_PARTNER_KEY'] ?? null
        ]);
    
        return redirect()->away($url . "/{$qrcodeSlug}?" . $query);
    }
    
}
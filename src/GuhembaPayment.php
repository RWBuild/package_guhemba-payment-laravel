<?php
namespace RWBuild\Guhemba;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use RWBuild\Guhemba\Exceptions\GuhembaPayException;

class GuhembaPayment
{
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

    /**
     * The response comming from guhemba
     */
    public $response = null;

    /**
     * Get all config guhemba keys or get a single value of a passed key
     * 
     * @return string|array
     */
    public static function getKeys($keyName = null)
    {
        if (! $keyName) {
            $keys = config('guhemba-webelement.option');
           
            if (! $keys) throw new GuhembaPayException('You should publish first the config tag');
        } else {
            $keys = config("guhemba-webelement.option.{$keyName}");
        }

        return $keys;
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
     * @return self
     */
    public static function generateQrcode($amount)
    {
        $pay = new GuhembaPayment();

        $pay->response = self::caller('sendQrcodeRequest', $amount);

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
        $pay = new GuhembaPayment();

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

        $pay = new GuhembaPayment();

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
            'redirect_url' => $keys['GUHEMBA_REDIRECT_URL'],
            'payment_ref' => $paymentRef,
            'state' => $state,
        ]);
       
        return redirect()->away($url . "/{$qrcodeSlug}?" . $query);
    }

    /**
     * Send a request to generate a qrcode
     * 
     * @param number $amount
     * @return object: qrcode info
     */
    private static function sendQrcodeRequest($amount)
    {
        $baseUrl = self::getKeys('GUHEMBA_BASE_URL');
        $url = self::joinUrl($baseUrl, self::$qrcodeUrl);
     
        $response =  self::client()->request(
            'POST', $url, self::buildRequestData($amount)
        );
    
        return json_decode($response->getBody()->getContents()); 
    }

    /**
     * Send a request to fetch a transaction info using a token
     * 
     * @param string $token
     * @return object: transaction info
     */
    private static function sendTransactionRequest($token)
    {
        $baseUrl = self::getKeys('GUHEMBA_BASE_URL');
        $url = self::joinUrl($baseUrl, self::$transactionUrl);
    
        $response =  self::client()->request(
            'POST', $url, self::buildRequestData($token)
        );
    
        return json_decode($response->getBody()->getContents()); 
    }

    /**
     * Send a request to fetch a transaction info using a reference code
     * of a transaction
     * 
     * @return object: transaction info
     */
    private static function sendTransactionCodeRequest()
    {
        $baseUrl = self::getKeys('GUHEMBA_BASE_URL');
        $url = self::joinUrl($baseUrl, self::$transCodeUrl);
        $code = request()->code;
    
        $response =  self::client()->request(
            'POST', $url, self::buildRequestData($code)
        );
    
        return json_decode($response->getBody()->getContents()); 
    }

    /**
     * Build the header and body to be sent with the request 
     * 
     * @param string $value: can be a "token" or "amount" 
     * @return array
     */
    private static function buildRequestData($value)
    {
        $keys = self::getKeys();

        return [
            'headers' => [
                'Accept' => 'application/json',
                'API-KEY' => $keys['GUHEMBA_API_KEY'],
                'MERCHANT-KEY' => $keys['GUHEMBA_MERCHANT_KEY'],
                'REDIRECT-URL' => $keys['GUHEMBA_REDIRECT_URL'],
                'PUBLIC-KEY' => $keys['GUHEMBA_PUBLIC_KEY']
            ],
            'form_params' => [
                // used when user needs to fetch a transaction using a token
                'token' => $value,
                // used when user needs to generate a qrcode
                'amount' => $value,
                // used when user needs to fetch a transaction using a ref code
                'code' => $value
            ]
        ];
    }

    /**
     * A method to call other method and catch their error 
     */
    private static function caller($callableMethod, $param = null)
    {
        try {
            return self::$callableMethod($param);
        } catch (ClientException | ConnectException | Exception  $e) {
            return self::handleError($e);
        }
    }

    /**
     * Handle error fired by guzzle request
     * 
     * @return object
     */
    private static function handleError($exception)
    {
        $response = $exception->getResponse();
        
        if (! $response) return  self::fireError($exception->getMessage());
       
        $statusCode = $response->getStatusCode();
        $errorResp = json_decode($response->getBody());
       
        $errorMessage = $errorResp->message ?? $errorResp->error;

        return self::fireError($errorMessage, $statusCode, [
            'hint' => $exception->getMessage()
        ]);
    }

    /**
     * Instantiate the throwable exception class
     * 
     * @return object
     */
    private static function fireError($msg, $status = 400, $withData = null)
    {
        try {
            if (! $withData) throw new GuhembaPayException($msg, $status);

            throw ((new GuhembaPayException($msg, $status))->withData($withData));
        } catch (GuhembaPayException $e) {
            return $e->getFormatedMessage();
        }
    }
    
    
}
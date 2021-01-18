<?php
namespace RWBuild\Guhemba\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use RWBuild\Guhemba\Exceptions\GuhembaPayException;

trait TransactionRequest
{

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
        return [
            'headers' => self::buildRequestHeader(),
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

    private static function buildRequestHeader() 
    {
        $keys = self::getKeys();

        return [
            'Accept' => 'application/json',
            'API-KEY' => $keys['GUHEMBA_API_KEY'],
            'MERCHANT-KEY' => $keys['GUHEMBA_MERCHANT_KEY'],
            self::$isPartner ? 'DYNAMIC-REDIRECT-URL' : 'REDIRECT-URL' => $keys['GUHEMBA_REDIRECT_URL'],
            'PUBLIC-KEY' => $keys['GUHEMBA_PUBLIC_KEY'], // merchant integration public key
            'PARTNER-KEY' => $keys['GUHEMBA_PARTNER_KEY'] ?? null
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
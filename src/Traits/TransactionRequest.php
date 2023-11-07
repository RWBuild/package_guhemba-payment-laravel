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
    private function sendQrcodeRequest($amount)
    {
        $url = self::joinUrl(self::$qrcodeUrl);

        $response =  self::client()->request(
            'POST',
            $url,
            $this->buildRequestData($amount)
        );

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Send a request to fetch a transaction info using a token
     * 
     * @param string $token
     * @return object: transaction info
     */
    private function sendTransactionRequest($token)
    {
        $url = self::joinUrl(self::$transactionUrl);

        $response =  self::client()->request(
            'POST',
            $url,
            $this->buildRequestData($token)
        );

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Send a request to fetch a transaction info using a reference code
     * of a transaction
     * 
     * @return object: transaction info
     */
    private function sendTransactionCodeRequest()
    {
        $url = self::joinUrl(self::$transCodeUrl);
        $code = request()->code;

        $response =  self::client()->request(
            'POST',
            $url,
            $this->buildRequestData($code)
        );

        return json_decode($response->getBody()->getContents());
    }

    /**
     * check if a transaction exist based on a given reference
     * (qrcode, payment_ref)
     */
    private function checkTransactionStatusRequest()
    {
        $url = self::joinUrl(self::$transactionStatusUrl);

        $response =  self::client()->request(
            'POST',
            $url,
            $this->buildRequestData()
        );

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Build the header and body to be sent with the request 
     * 
     * @param string $value: can be a "token" or "amount" 
     * @return array
     */
    private function buildRequestData($value = null)
    {
        return [
            'headers' => $this->buildRequestHeader(),
            'form_params' => [
                // used when user needs to fetch a transaction using a token
                'token' => $value,
                // used when user needs to generate a qrcode
                'amount' => $value,
                // used when user needs to fetch a transaction using a ref code
                'code' => $value,

                // used when generating a qrcode, but also can be used
                // when checking the transaction status
                'payment_ref' => $this->paymentRef,

                // Provided to secure the feedback from guhemba, but also can be used
                // when checking the transaction status
                'confirm_payment_key' => $this->confirmPaymentKey,

                // use only when fetching something using qrcode
                'qrcode_id' => $this->qrcodeId,

                // used when checking the status of a transaction from a reference
                'check_by' => $this->checkTransactionStatusBy,

                'wallet_merchant_key' => self::getKeys()['GUHEMBA_MERCHANT_KEY'] ?? null

            ]
        ];
    }

    private function buildRequestHeader()
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
    private  function caller($callableMethod, ...$params)
    {
        try {
            return $this->$callableMethod(...$params);
        } catch (ClientException | ConnectException | Exception  $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Handle error fired by guzzle request
     * 
     * @return object
     */
    private function handleError($exception)
    {
        $response = $exception->getResponse();

        if (!$response) return  self::fireError($exception->getMessage());

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
    public static function fireError($msg, $status = 400, $withData = null)
    {
        try {
            if (!$withData) throw new GuhembaPayException($msg, $status);

            throw ((new GuhembaPayException($msg, $status))->withData($withData));
        } catch (GuhembaPayException $e) {
            return $e->getFormatedMessage();
        }
    }
}

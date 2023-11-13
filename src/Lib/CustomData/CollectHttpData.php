<?php

namespace RWBuild\Guhemba\Lib\CustomData;

use RWBuild\Guhemba\Lib\CustomData\PaymentConfigData;


$initialData = [
    'verb' => null,
    'endpoint' => null,
    'headers' => [],
    'body' => [],
    'response_custom_data' =>  null,
    'config' => null
];

/**
 * collect data for http request
 */
class CollectHttpData
{

    /**
     * Data we need to send the http request to guhemba
     */
    protected $httpData = [];

    /**
     * Create the instance
     */
    public static function make()
    {
        return new self();
    }

    /**
     * chain http property based on condition
     */
    public function when($statement, callable $mycallback)
    {
        if ($statement) $mycallback($this);

        return $this;
    }

    /**
     * Define the http verb: post.put,get,delete
     */
    public function verb($verb): CollectHttpData
    {
        $this->httpData['verb'] = $verb;

        return $this;
    }

    /**
     * Define the endpoint without the base url
     */
    public function endpoint($endpoint): CollectHttpData
    {
        $this->httpData['endpoint'] = $endpoint;

        return $this;
    }

    /**
     * Define headers
     */
    public function headers(array $headers): CollectHttpData
    {
        $existingHeaders = $this->httpData['headers'] ?? [];
        $this->httpData['headers'] = array_merge($existingHeaders, $headers);

        return $this;
    }

    /**
     * added authorization bearer token to the headers
     * 
     * @param string $token
     */
    public function withToken($token)
    {
        return $this->headers([
            'Authorization' => "Bearer {$token}"
        ]);
    }

    /**
     * Define the request body
     */
    public function body(array $body): CollectHttpData
    {
        $existingBody = $this->httpData['body'] ?? [];
        $this->httpData['body'] = array_merge($existingBody, $body);

        return $this;
    }

    /**
     * Define the customData class that will format the response
     */
    public function responseDataClass($verb): CollectHttpData
    {
        $this->httpData['response_custom_data'] = $verb;

        return $this;
    }

    /**
     * Define other configuration
     */
    public function config(PaymentConfigData $configData): CollectHttpData
    {
        $this->httpData['config'] = $configData;

        return $this;
    }

    /**
     * get all the http request configuration
     */
    public function all(): array
    {
        return $this->httpData;
    }
}

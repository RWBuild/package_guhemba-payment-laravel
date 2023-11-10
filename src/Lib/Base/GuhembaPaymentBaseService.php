<?php

namespace RWBuild\Guhemba\Lib\Base;

use Kakaprodo\CustomData\CustomData;
use RWBuild\Guhemba\Lib\Actions\SendHttpAction;
use RWBuild\Guhemba\Lib\CustomData\HttpDataType;
use RWBuild\Guhemba\Lib\CustomData\CollectHttpData;
use RWBuild\Guhemba\Lib\CustomData\PaymentConfigData;


abstract class GuhembaPaymentBaseService
{
    /**
     * Configuration keys
     * 
     * @var \RWBuild\Guhemba\Lib\CustomData\PaymentConfigData
     */
    protected $config;

    public function __construct(PaymentConfigData $config)
    {
        $this->config = $config;
    }

    /**
     * The service constructor
     */
    public static function make(PaymentConfigData $config)
    {
        return (new static($config));
    }

    /**
     * Added core data to inputed information
     */
    protected function inputs(array $options): array
    {
        return  array_merge($options, [
            'config' =>  $this->config
        ]);
    }

    /**
     * Send http request to guhemba
     */
    public function sendRequest(HttpDataType $data)
    {
        $httpData = CollectHttpData::make();

        $httpData->config($this->config);

        $data->httpConfig($httpData);

        return SendHttpAction::process($httpData->all());
    }
}

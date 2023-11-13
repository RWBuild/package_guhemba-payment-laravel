<?php

namespace RWBuild\Guhemba\Lib\Base;

use RWBuild\Guhemba\GuhembaPayment;
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

    /**
     * @var GuhembaPayment
     */
    protected $guhembaPayment;

    public function __construct(GuhembaPayment $guhembaPayment)
    {
        $this->guhembaPayment = $guhembaPayment;
        $this->config = $guhembaPayment->configData;
    }

    /**
     * The service constructor
     */
    public static function make(GuhembaPayment $guhembaPayment)
    {
        return (new static($guhembaPayment));
    }

    /**
     * Added core data to inputed information
     */
    protected function inputs(array $options): array
    {
        return  array_merge($options, [
            'config' =>  $this->config,
            'guhemba_payment' => $this->guhembaPayment
        ]);
    }

    /**
     * The guhemba payment gate
     */
    public function gate(): GuhembaPayment
    {
        return $this->guhembaPayment;
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

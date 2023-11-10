<?php

namespace RWBuild\Guhemba\Lib\Services;

use RWBuild\Guhemba\Lib\CustomData\PaymentConfigData;


abstract class GuhembaPaymentService
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
}

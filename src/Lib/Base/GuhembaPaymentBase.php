<?php

namespace RWBuild\Guhemba\Lib\Base;

use RWBuild\Guhemba\GuhembaPayment;
use RWBuild\Guhemba\Lib\CustomData\PaymentConfigData;
use RWBuild\Guhemba\Lib\Base\OldVersion\GeneralGuhembaPaymentBase;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\GroupPurchaseService;

/**
 * @property GroupPurchaseService $groupPurchase
 */
abstract class GuhembaPaymentBase extends GeneralGuhembaPaymentBase
{
    /**
     * the configuration keys 
     * 
     * @var PaymentConfigData
     */
    protected $configData;

    /**
     * Registered services
     */
    protected $services = [];

    public function __construct()
    {
        $this->configData = PaymentConfigData::make(config('guhemba-webelement'));

        $this->serviceRegister();
    }

    /**
     * Build the instance
     */
    public static function init(): GuhembaPaymentBase
    {
        return new static();
    }

    /**
     * register a service with it corresponding handler callback
     */
    public function serviceRegister()
    {
        $this->services = [
            'groupPurchase' => fn () => GroupPurchaseService::make($this->configData)
        ];
    }


    public function __get($service)
    {
        $serviceHandler = $this->services[$service] ?? null;

        if (!is_callable($serviceHandler)) return null;

        return $serviceHandler();
    }
}

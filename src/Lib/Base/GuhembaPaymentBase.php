<?php

namespace RWBuild\Guhemba\Lib\Base;

use RWBuild\Guhemba\Lib\CustomData\PaymentConfigData;
use RWBuild\Guhemba\Lib\Services\Qrcode\QrCodeService;
use RWBuild\Guhemba\Lib\Interface\AuthServiceInterface;
use RWBuild\Guhemba\Lib\Interface\GroupPurchaseServiceInterface;
use RWBuild\Guhemba\Lib\Base\OldVersion\GeneralGuhembaPaymentBase;

/**
 * @property GroupPurchaseServiceInterface $groupPurchase : The groupPurchase service
 * @property AuthServiceInterface $auth : The auth service
 * @property QrCodeService $qrCode : The qrcode service
 */
abstract class GuhembaPaymentBase extends GeneralGuhembaPaymentBase
{
    /**
     * the configuration keys 
     * 
     * @var PaymentConfigData
     */
    public $configData;

    /**
     * Registered services
     */
    protected $services = [];

    public function __construct()
    {
        $this->configData = PaymentConfigData::make(config('guhemba-webelement'));

        $this->services = $this->serviceRegister();
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
    abstract public function serviceRegister(): array;

    /**
     * Build services based on accessor property
     */
    public function __get($service)
    {
        $serviceHandler = $this->services[$service] ?? null;

        if (!$serviceHandler) return null;

        return $serviceHandler::make($this);
    }
}

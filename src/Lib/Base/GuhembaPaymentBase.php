<?php

namespace RWBuild\Guhemba\Lib\Base;

use RWBuild\Guhemba\Lib\Services\Auth\AuthService;
use RWBuild\Guhemba\Lib\CustomData\PaymentConfigData;
use RWBuild\Guhemba\Lib\Services\QrCode\QrCodeService;
use RWBuild\Guhemba\Lib\Services\Transaction\TransactionService;
use RWBuild\Guhemba\Lib\Base\OldVersion\GeneralGuhembaPaymentBase;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\GroupPurchaseService;

/**
 * @property GroupPurchaseService $groupPurchase : The groupPurchase service
 * @property AuthService $auth : The auth service
 * @property QrCodeService $qrCode : The qrcode service
 * @property TransactionService $transaction : the transaction service
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

    /**
     * Keeps the error handling callback
     */
    protected $errorHandlerCallback = null;

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
     * Register a global error handling
     */
    public function globalHttpErrorListener(callable $error)
    {
        $this->errorHandlerCallback = $error;

        return $this;
    }

    /**
     * Execute an action and catch any occured error
     */
    public function executeAction(callable $actionToExecute, $actionErrorHandler = null)
    {
        try {
            return $actionToExecute();
        } catch (\Throwable $th) {

            $errorHnadler = $actionErrorHandler  ?? $this->errorHandlerCallback;

            if (is_callable($errorHnadler)) return $errorHnadler($th);

            throw $th;
        }
    }

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

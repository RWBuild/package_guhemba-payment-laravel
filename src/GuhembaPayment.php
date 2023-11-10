<?php

namespace RWBuild\Guhemba;

use RWBuild\Guhemba\Lib\Base\GuhembaPaymentBase;
use RWBuild\Guhemba\Lib\Services\Auth\AuthService;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\GroupPurchaseService;

class GuhembaPayment extends GuhembaPaymentBase
{
    /**
     * Service names with their correspondind handler
     */
    public function serviceRegister(): array
    {
        return [
            'groupPurchase' => GroupPurchaseService::class,
            'auth' => AuthService::class
        ];
    }
}

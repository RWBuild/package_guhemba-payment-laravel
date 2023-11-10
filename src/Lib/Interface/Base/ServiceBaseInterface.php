<?php

namespace RWBuild\Guhemba\Lib\Interface\Base;

use RWBuild\Guhemba\Lib\CustomData\PaymentConfigData;

interface ServiceBaseInterface
{
    /**
     * Load dependency of the service
     */
    public static function make(PaymentConfigData $config);
}

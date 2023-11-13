<?php

namespace RWBuild\Guhemba\Lib\Interface\Base;

use RWBuild\Guhemba\GuhembaPayment;
use RWBuild\Guhemba\Lib\CustomData\PaymentConfigData;

interface ServiceBaseInterface
{
    /**
     * create instance and Load dependencies of the service
     */
    public static function make(GuhembaPayment $guhembaPayment);
}

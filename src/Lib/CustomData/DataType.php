<?php

namespace RWBuild\Guhemba\Lib\CustomData;

use RWBuild\Guhemba\GuhembaPayment;
use Kakaprodo\CustomData\CustomData;

/**
 * @property \RWBuild\Guhemba\Lib\CustomData\PaymentConfigData $config
 */
abstract class DataType extends CustomData
{
    /**
     * Payment services gate
     */
    protected $guhembaPayment;

    /**
     * Access to the main class of the package
     */
    public function gate(): GuhembaPayment
    {
        if ($this->guhembaPayment) return $this->guhembaPayment;

        return $this->guhembaPayment = GuhembaPayment::init();
    }
}

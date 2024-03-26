<?php

namespace RWBuild\Guhemba\Lib\CustomData;

use RWBuild\Guhemba\GuhembaPayment;
use Kakaprodo\CustomData\CustomData;

/**
 * @property \RWBuild\Guhemba\Lib\CustomData\PaymentConfigData $config
 * @property GuhembaPayment guhemba_payment
 */
abstract class DataType extends CustomData
{
    /**
     * Access to the main class of the package
     */
    public function gate(): GuhembaPayment
    {
        return $this->guhemba_payment ?? GuhembaPayment::init();
    }

    /**
     * Encrypt some merchant keys for future authentication
     */
    public function encryptedKey()
    {
        $config = $this->gate()->configData;

        return  $config->encryptedKey();
    }
}

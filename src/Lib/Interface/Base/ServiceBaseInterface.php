<?php

namespace RWBuild\Guhemba\Lib\Interface\Base;

use RWBuild\Guhemba\GuhembaPayment;

interface ServiceBaseInterface
{
    /**
     * create instance and Load dependencies of the service
     */
    public static function make(GuhembaPayment $guhembaPayment);

    /**
     * Listen to errors that are thrown 
     */
    public function onError(callable $handler);
}

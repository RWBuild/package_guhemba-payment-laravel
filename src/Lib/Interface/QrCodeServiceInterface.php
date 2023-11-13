<?php

namespace RWBuild\Guhemba\Lib\Interface;

use RWBuild\Guhemba\Lib\Interface\Base\ServiceBaseInterface;
use RWBuild\Guhemba\Lib\Services\QrCode\Data\Response\QrCodeResponseData;

interface QrCodeServiceInterface extends ServiceBaseInterface
{
    /**
     * Generate a payment Qrcode
     */
    public function generate(array $options = []): QrCodeResponseData;
}

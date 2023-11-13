<?php

namespace RWBuild\Guhemba\Lib\Services\Qrcode;

use RWBuild\Guhemba\Lib\Base\GuhembaPaymentBaseService;
use RWBuild\Guhemba\Lib\Interface\QrCodeServiceInterface;
use RWBuild\Guhemba\Lib\Services\QrCode\Data\GenerateQrcodeData;
use RWBuild\Guhemba\Lib\Services\QrCode\Data\Response\QrCodeResponseData;

class QrCodeService extends GuhembaPaymentBaseService implements QrCodeServiceInterface
{
    public function generate(array $options = []): QrCodeResponseData
    {
        return $this->sendRequest(
            GenerateQrcodeData::make($this->inputs($options))
        );
    }
}

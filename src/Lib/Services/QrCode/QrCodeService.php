<?php

namespace RWBuild\Guhemba\Lib\Services\QrCode;

use Illuminate\Http\RedirectResponse;
use RWBuild\Guhemba\Lib\Base\GuhembaPaymentBaseService;
use RWBuild\Guhemba\Lib\Interface\QrCodeServiceInterface;
use RWBuild\Guhemba\Lib\CustomData\Response\SuccessResponseData;
use RWBuild\Guhemba\Lib\Services\QrCode\Data\GenerateQrcodeData;
use RWBuild\Guhemba\Lib\Services\QrCode\Data\RequestPaymentApprovalData;
use RWBuild\Guhemba\Lib\Services\QrCode\Data\Response\QrCodeResponseData;
use RWBuild\Guhemba\Lib\Services\QrCode\Data\RedirectQrcodeToPaymentPageData;

class QrCodeService extends GuhembaPaymentBaseService implements QrCodeServiceInterface
{
    public function generate(array $options = []): QrCodeResponseData
    {
        return $this->sendRequest(
            GenerateQrcodeData::make($this->inputs($options))
        );
    }

    public function redirect(array $options = []): RedirectResponse
    {
        return $this->sendRequest(
            RedirectQrcodeToPaymentPageData::make($this->inputs($options))
        );
    }

    public function requestPaymentApproval(array $options = []): SuccessResponseData
    {
        return $this->sendRequest(
            RequestPaymentApprovalData::make($this->inputs($options))
        );
    }
}

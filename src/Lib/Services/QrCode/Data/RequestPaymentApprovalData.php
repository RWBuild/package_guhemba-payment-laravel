<?php

namespace RWBuild\Guhemba\Lib\Services\QrCode\Data;

use RWBuild\Guhemba\Lib\CustomData\HttpDataType;
use RWBuild\Guhemba\Lib\Services\Auth\AuthService;
use RWBuild\Guhemba\Lib\CustomData\CollectHttpData;

class RequestPaymentApprovalData extends HttpDataType
{
    protected function expectedProperties(): array
    {
        return [
            'customer_atname' => $this->dataType()->string(request()->customer_atname),
            'qrcode_slug' => $this->dataType()->string(request()->qrcode_slug),
        ];
    }

    public function httpConfig(CollectHttpData $collectData)
    {
        $partnerToken = $this->gate()->auth->partnerAccessToken([
            'intent' => AuthService::INTENT_REQUEST_PAYMENT_APPROVAL
        ]);

        $collectData->verb('post')
            ->endpoint('qrcode/request-payment-approval')
            ->withToken($partnerToken->token)
            ->body($this->onlyValidated());
    }
}

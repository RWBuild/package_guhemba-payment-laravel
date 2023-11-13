<?php

namespace RWBuild\Guhemba\Lib\Services\QrCode\Data;

use Illuminate\Support\Arr;
use Kakaprodo\CustomData\Lib\TypeHub\DataTypeHub;
use RWBuild\Guhemba\Lib\CustomData\HttpDataType;
use RWBuild\Guhemba\Lib\Services\Auth\AuthService;
use RWBuild\Guhemba\Lib\CustomData\CollectHttpData;
use RWBuild\Guhemba\Lib\Services\QrCode\Data\Response\QrCodeResponseData;

class GenerateQrcodeData extends HttpDataType
{
    protected function expectedProperties(): array
    {
        return [
            'amount' => $this->dataType()->numeric(),
            'payment_ref' => $this->dataType()->string(),
            'confirm_payment_key' => $this->dataType()->string(),
            'support_group_purchase' => $this->dataType()->bool(false),
            'group_purchase_code?' => $this->dataType()->string(),
        ];
    }

    public function boot()
    {
        if ($this->support_group_purchase) {
            $this->throwWhenFieldAbsent('group_purchase_code', 'When the qrcode supports group purchase,'
                . ' Then you should provide the group_purchase_code as well');
        }
    }

    public function httpConfig(CollectHttpData $collectData)
    {
        $collectData->verb('post')
            ->endpoint('generate-qrcode/payment')
            ->when($this->support_group_purchase, function (CollectHttpData $collectData) {
                $partnerToken = $this->gate()->auth->partnerAccessToken([
                    'intent' => AuthService::INTENT_GEN_GROUP_PURCHASE_QRCODE
                ]);

                $collectData->endpoint('group-purchases/generate-qrcode')
                    ->withToken($partnerToken->token);
            })
            ->body(Arr::except($this->onlyValidated(), 'support_group_purchase'))
            ->responseDataClass(QrCodeResponseData::class);
    }
}

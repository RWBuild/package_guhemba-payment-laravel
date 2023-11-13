<?php

namespace RWBuild\Guhemba\Lib\Services\GroupPurchase\Data;

use RWBuild\Guhemba\Lib\Services\Auth\AuthService;
use RWBuild\Guhemba\Lib\CustomData\CollectHttpData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Base\GroupPurchaseBaseData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response\ReleaseGroupPurchaseResponseData;

class ReleaseGroupPurchaseData extends GroupPurchaseBaseData
{
    protected function expectedProperties(): array
    {
        return [
            'group_purchase_code' => $this->dataType()->string(),
            'released_amount' => $this->dataType()->numeric()
        ];
    }

    public function httpConfig(CollectHttpData $collectData)
    {
        $partnerToken = $this->gate()->auth->partnerAccessToken([
            'intent' => AuthService::INTENT_RELEASE_GROUP_PURCHASE
        ]);

        $collectData->verb('post')
            ->endpoint('group-purchases/release')
            ->withToken($partnerToken->token)
            ->body($this->onlyValidated())
            ->responseDataClass(ReleaseGroupPurchaseResponseData::class);
    }
}

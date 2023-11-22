<?php

namespace RWBuild\Guhemba\Lib\Services\GroupPurchase\Data;

use RWBuild\Guhemba\Lib\Services\Auth\AuthService;
use RWBuild\Guhemba\Lib\CustomData\CollectHttpData;
use RWBuild\Guhemba\Lib\CustomData\Response\SuccessResponseData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Base\GroupPurchaseBaseData;

class AddGroupPurchaseMerchantData extends GroupPurchaseBaseData
{
    protected function expectedProperties(): array
    {
        return [
            'group_purchase_code' => $this->dataType()->string(),
            'merchant_wallet_key' => $this->dataType()->string(),
            'partner_key?' => $this->dataType()->string($this->config->partner->key)
        ];
    }

    public function httpConfig(CollectHttpData $collectData)
    {
        $partnerToken = $this->gate()->auth->partnerAccessToken([
            'intent' => AuthService::INTENT_CREATE_GROUP_PURCHASE
        ]);

        $collectData->verb('post')
            ->endpoint('group-purchases/add-merchant')
            ->withToken($partnerToken->token)
            ->body($this->onlyValidated())
            ->responseDataClass(SuccessResponseData::class);
    }
}

<?php

namespace RWBuild\Guhemba\Lib\Services\GroupPurchase\Data;

use Kakaprodo\CustomData\Lib\TypeHub\DataTypeHub;
use RWBuild\Guhemba\Lib\CustomData\CollectHttpData;
use RWBuild\Guhemba\Lib\Services\Auth\AuthService;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Base\GroupPurchaseBaseData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response\CreateGroupPurchaseResponseData;

class CreateGroupPurchaseData extends GroupPurchaseBaseData
{
    protected function expectedProperties(): array
    {
        return [
            'merchant_wallet_key?' => $this->dataType()->string(),
            'release_callback_url?' => $this->dataType()->string(null),
            'cancel_callback_url' => $this->dataType()->string(),
            'description' => $this->dataType()->customValidator(function ($description, DataTypeHub $validator) {
                if (!is_string($description)) {
                    $validator->message("The group purchase description must be a string");
                    return false;
                }

                if (strlen($description) > 50) {
                    $validator->message("The group purchase 'description' must have 50 characters maximum");
                    return false;
                }

                return true;
            }),
            'partner_key?' => $this->dataType()->string($this->config->partner->key)
        ];
    }

    public function httpConfig(CollectHttpData $collectData)
    {
        $partnerToken = $this->gate()->auth->partnerAccessToken([
            'intent' => AuthService::INTENT_CREATE_GROUP_PURCHASE
        ]);

        $collectData->verb('post')
            ->endpoint('group-purchases/create')
            ->withToken($partnerToken->token)
            ->body($this->onlyValidated())
            ->responseDataClass(CreateGroupPurchaseResponseData::class);
    }
}

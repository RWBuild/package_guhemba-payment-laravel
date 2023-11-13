<?php

namespace RWBuild\Guhemba\Lib\Services\GroupPurchase\Data;

use RWBuild\Guhemba\Lib\CustomData\CollectHttpData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Base\GroupPurchaseBaseData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response\GroupPurchaseDetailResponseData;

class FetchGroupPurchaseDetailData extends GroupPurchaseBaseData
{
    protected function expectedProperties(): array
    {
        return [
            'group_purchase_code' => $this->dataType()->string(),
        ];
    }

    public function boot()
    {
        $this->config->partner->throwWhenFieldAbsent('key', 'The partner key is missing in the config file');
    }

    public function httpConfig(CollectHttpData $collectData)
    {
        $collectData->verb('get')
            ->endpoint('group-purchases/show/' . $this->group_purchase_code)
            ->headers([
                'PARTENER-KEY' => $this->config->partner->key
            ])->responseDataClass(GroupPurchaseDetailResponseData::class);
    }
}

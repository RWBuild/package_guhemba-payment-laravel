<?php

namespace RWBuild\Guhemba\Lib\Services\Transaction\Data;

use RWBuild\Guhemba\Lib\CustomData\HttpDataType;
use RWBuild\Guhemba\Lib\CustomData\CollectHttpData;
use RWBuild\Guhemba\Lib\Services\Transaction\Data\Response\TransactionResponseData;

class FetchTransactionFromTokenData extends HttpDataType
{
    protected function expectedProperties(): array
    {
        return [
            'token' => $this->dataType()->string()
        ];
    }

    public function httpConfig(CollectHttpData $collectData)
    {
        $collectData->verb('post')
            ->endpoint('transaction/exist')
            ->body($this->onlyValidated())
            ->responseDataClass(TransactionResponseData::class);
    }
}

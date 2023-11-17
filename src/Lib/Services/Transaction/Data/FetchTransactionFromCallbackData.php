<?php

namespace RWBuild\Guhemba\Lib\Services\Transaction\Data;

use RWBuild\Guhemba\Lib\CustomData\HttpDataType;
use RWBuild\Guhemba\Lib\CustomData\CollectHttpData;
use RWBuild\Guhemba\Lib\Services\Transaction\Data\Response\TransactionResponseData;

class FetchTransactionFromCallbackData extends HttpDataType
{
    protected function expectedProperties(): array
    {
        return [
            'code' => $this->dataType()->string(request()->code)
        ];
    }

    public function httpConfig(CollectHttpData $collectData)
    {
        $collectData->verb('post')
            ->endpoint('transaction-from-code')
            ->body($this->onlyValidated())
            ->responseDataClass(TransactionResponseData::class);
    }
}

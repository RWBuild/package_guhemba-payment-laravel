<?php

namespace RWBuild\Guhemba\Lib\Services\Transaction\Data;

use RWBuild\Guhemba\Lib\CustomData\HttpDataType;
use RWBuild\Guhemba\Lib\CustomData\CollectHttpData;
use RWBuild\Guhemba\Lib\Services\Transaction\Data\Response\TransactionResponseData;

class FetchTransactionFromReferenceData  extends HttpDataType
{
    protected function expectedProperties(): array
    {
        return [
            'payment_ref' => $this->dataType()->string(),
            'confirm_payment_key?' => $this->dataType()->string(),
            'check_by?' => $this->dataType()->string('payment_ref'),
            'wallet_merchant_key?' => $this->dataType()->string($this->config->option->merchant_key)
        ];
    }

    public function httpConfig(CollectHttpData $collectData)
    {
        $collectData->verb('post')
            ->endpoint('transaction/check-status/from-payment-reference')
            ->body($this->onlyValidated())
            ->responseDataClass(TransactionResponseData::class);
    }
}

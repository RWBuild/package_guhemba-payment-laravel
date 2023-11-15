<?php

namespace RWBuild\Guhemba\Lib\Services\Transaction\Data;

use RWBuild\Guhemba\Lib\CustomData\HttpDataType;
use RWBuild\Guhemba\Lib\CustomData\CollectHttpData;
use RWBuild\Guhemba\Lib\Services\Transaction\Data\Response\TransactionResponseData;

class FetchTransactionFromQrcodeData extends HttpDataType
{
    protected function expectedProperties(): array
    {
        return [
            'qrcode_id' => $this->dataType()->number(),
            'payment_ref' => $this->dataType()->string('undefined'),
            'check_by?' => $this->dataType()->string('qrcode_id'),
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

<?php

namespace RWBuild\Guhemba\Lib\Services\Transaction\Data;

use RWBuild\Guhemba\Lib\CustomData\HttpDataType;
use RWBuild\Guhemba\Lib\Services\Auth\AuthService;
use RWBuild\Guhemba\Lib\CustomData\CollectHttpData;
use RWBuild\Guhemba\Lib\Services\Transaction\Data\Response\TransactionResponseData;

class RefundTransactionData  extends HttpDataType
{
    protected function expectedProperties(): array
    {
        return [
            'transaction_id' => $this->dataType()->number(),
        ];
    }

    public function httpConfig(CollectHttpData $collectData)
    {
        $partnerToken = $this->gate()->auth->partnerAccessToken([
            'intent' => AuthService::INTENT_REFUND_PAYMENT
        ]);

        $collectData->verb('post')
            ->endpoint('transactions/refund')
            ->withToken($partnerToken->token)
            ->body($this->onlyValidated())
            ->responseDataClass(TransactionResponseData::class);
    }
}

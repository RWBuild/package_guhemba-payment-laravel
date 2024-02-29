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
            'merchant_is_partner?' => $this->dataType()->bool(true)
        ];
    }

    public function httpConfig(CollectHttpData $collectData)
    {
        $authService = $this->gate()->auth;
        $accessToken = $this->merchant_is_partner ?
            $authService->partnerAccessToken(['intent' => AuthService::INTENT_REFUND_PAYMENT])
            : $authService->walletPersonalAccessToken([
                'intents' => [AuthService::TOKENSCOPE_REFUND_FROM_3DPARTY]
            ]);

        $collectData->verb('post')
            ->endpoint('transactions/refund')
            ->withToken($accessToken->token)
            ->body($this->onlyValidated())
            ->responseDataClass(TransactionResponseData::class);
    }
}

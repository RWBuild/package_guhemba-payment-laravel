<?php

namespace RWBuild\Guhemba\Lib\Services\Transaction;

use RWBuild\Guhemba\Lib\Base\GuhembaPaymentBaseService;
use RWBuild\Guhemba\Lib\Interface\TransactionServiceInterface;
use RWBuild\Guhemba\Lib\Services\Transaction\Data\FetchTransactionFromCallbackData;
use RWBuild\Guhemba\Lib\Services\Transaction\Data\FetchTransactionFromQrcodeData;
use RWBuild\Guhemba\Lib\Services\Transaction\Data\FetchTransactionFromReferenceData;
use RWBuild\Guhemba\Lib\Services\Transaction\Data\FetchTransactionFromTokenData;
use RWBuild\Guhemba\Lib\Services\Transaction\Data\Response\TransactionResponseData;

class TransactionService extends GuhembaPaymentBaseService implements TransactionServiceInterface
{
    public function fetchFromCallback(): TransactionResponseData
    {
        $options = [
            'code' => request()->code
        ];

        return $this->sendRequest(
            FetchTransactionFromCallbackData::make($this->inputs($options))
        );
    }

    public function fetchFromToken(array $options): TransactionResponseData
    {
        return $this->sendRequest(
            FetchTransactionFromTokenData::make($this->inputs($options))
        );
    }

    public function fetchFromQrcode(array $options): TransactionResponseData
    {
        return $this->sendRequest(
            FetchTransactionFromQrcodeData::make($this->inputs($options))
        );
    }

    public function fetchFromReference(array $options): TransactionResponseData
    {
        return $this->sendRequest(
            FetchTransactionFromReferenceData::make($this->inputs($options))
        );
    }
}

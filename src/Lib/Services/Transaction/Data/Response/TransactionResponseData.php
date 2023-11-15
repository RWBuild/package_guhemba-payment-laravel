<?php

namespace RWBuild\Guhemba\Lib\Services\Transaction\Data\Response;

use RWBuild\Guhemba\Lib\CustomData\ResponseBaseData;
use RWBuild\Guhemba\Lib\Services\Transaction\Data\Response\TransactionFeeData;
use RWBuild\Guhemba\Lib\Services\Transaction\Data\Response\TransactionReferenceData;

/**
 * @property numeric id
 */
class TransactionResponseData  extends ResponseBaseData
{
    protected function expectedProperties(): array
    {
        return [
            "id?",
            "qr_code_id?",
            "amount?",
            "description?",
            "transaction_type?",
            "transaction_token?",
            "created_at?",
            "updated_at?",
            "fees?",
            "net_amount?",
            "tip_amount?",
            "reference?" => $this->dataType(TransactionReferenceData::class),
            "discount_amount?",
            "payment_method?",
            "transaction_fee?" => $this->dataType(TransactionFeeData::class),
        ];
    }

    /**
     * Format response payload based on the expected properties
     */
    public static function formatResponse(array $responseJson): array
    {
        // it will empty in case no transaction was found
        return $responseJson['transaction'] ?? [];
    }
}

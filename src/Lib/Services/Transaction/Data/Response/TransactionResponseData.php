<?php

namespace RWBuild\Guhemba\Lib\Services\Transaction\Data\Response;

use RWBuild\Guhemba\Lib\CustomData\ResponseBaseData;
use RWBuild\Guhemba\Lib\Services\Transaction\Data\Response\TransactionFeeData;
use RWBuild\Guhemba\Lib\Services\Transaction\Data\Response\TransactionReferenceData;

/**
 * @property numeric id
 * @property numeric qr_code_id
 * @property bool is_pending
 * @property numeric amount
 * @property string description
 * @property string transaction_type
 * @property string transaction_token
 * @property string created_at
 * @property string updated_at
 * @property string net_amount
 * @property TransactionReferenceData reference
 * @property string payment_method
 * @property TransactionFeeData transaction_fee
 */
class TransactionResponseData  extends ResponseBaseData
{
    protected function expectedProperties(): array
    {
        return [
            "id?",
            "qr_code_id?",
            'is_pending?',
            "amount?",
            "description?",
            "transaction_type?",
            "transaction_token?",
            "created_at?",
            "updated_at?",
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

    /**
     * check if the transaction
     */
    public function isCompleted(): bool
    {
        if (!$this->id) return false;

        return $this->is_pending == false;
    }
}

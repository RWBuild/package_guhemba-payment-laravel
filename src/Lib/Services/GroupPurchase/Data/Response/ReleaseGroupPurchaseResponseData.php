<?php

namespace RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response;

use RWBuild\Guhemba\Lib\CustomData\ResponseBaseData;

/**
 * @property number id
 * @property string purchase_code
 * @property string status
 * @property string created_at
 * @property number total_amount
 * @property number released_amount
 * @property string closed_at
 * @property string released_transaction_token
 */
class ReleaseGroupPurchaseResponseData extends ResponseBaseData
{
    protected function expectedProperties(): array
    {
        return [
            "id",
            "purchase_code",
            "status",
            "created_at",
            'total_amount?',
            'released_amount?',
            'closed_at?',
            'released_transaction_token'
        ];
    }
}

<?php

namespace RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response;

use RWBuild\Guhemba\Lib\CustomData\ResponseBaseData;

/**
 * @property number id
 * @property string purchase_code
 * @property string status
 * @property string created_at
 */
class CreateGroupPurchaseResponseData  extends ResponseBaseData
{
    protected function expectedProperties(): array
    {
        return [
            "id",
            "purchase_code",
            "status",
            "created_at",
        ];
    }
}

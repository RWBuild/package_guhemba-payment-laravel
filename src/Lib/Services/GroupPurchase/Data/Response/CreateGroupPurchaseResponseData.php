<?php

namespace RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response;

use RWBuild\Guhemba\Lib\CustomData\DataType;

/**
 * @property number id
 * @property string purchase_code
 * @property string status
 * @property string created_at
 */
class CreateGroupPurchaseResponseData  extends DataType
{
    protected function expectedProperties(): array
    {
        return [
            "id" => $this->dataType()->numeric(),
            "purchase_code" => $this->dataType()->string(),
            "status" => $this->dataType()->string(),
            "created_at" => $this->dataType()->string()
        ];
    }
}

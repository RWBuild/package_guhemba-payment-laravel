<?php

namespace RWBuild\Guhemba\Lib\Services\GroupPurchase\Data;

use Kakaprodo\CustomData\Lib\TypeHub\DataTypeHub;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Base\GroupPurchaseBaseData;

class CreateGroupPurchaseData extends GroupPurchaseBaseData
{
    protected function expectedProperties(): array
    {
        return [
            'merchant_key' => $this->dataType()->string(),
            'description' => $this->dataType()->customValidator(function ($description, DataTypeHub $validator) {
                if (!is_string($description)) {
                    $validator->message("The group purchase description must be a string");
                    return false;
                }

                if (strlen($description) > 50) {
                    $validator->message("The group purchase 'description' must have 50 characters maximum");
                    return false;
                }

                return true;
            })
        ];
    }
}

<?php

namespace RWBuild\Guhemba\Lib\CustomData\Partial;

use RWBuild\Guhemba\Lib\CustomData\DataType;

/**
 * @property string key
 * @property string wallet_key
 */
class ConfigPartnerData extends DataType
{

    protected function expectedProperties(): array
    {
        return [
            'key?' => $this->dataType()->string(),
            'wallet_key?' => $this->dataType()->string(),
        ];
    }
}

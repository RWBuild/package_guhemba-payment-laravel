<?php

namespace RWBuild\Guhemba\Lib\CustomData;

use RWBuild\Guhemba\Lib\CustomData\DataType;
use RWBuild\Guhemba\Lib\CustomData\Partial\ConfigPartnerData;
use RWBuild\Guhemba\Lib\CustomData\Partial\ConfigMerchantOptionData;

/**
 * @property ConfigMerchatOptionData option
 * @property ConfigPartnerData partner
 * @property string redirect_url
 */
class PaymentConfigData extends DataType
{

    protected function expectedProperties(): array
    {
        return [
            'option' => $this->dataType(ConfigMerchantOptionData::class),
            'redirect_url' => $this->dataType()->string(),
            'partner' => $this->dataType(ConfigPartnerData::class)
        ];
    }
}

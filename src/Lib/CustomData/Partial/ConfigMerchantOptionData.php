<?php

namespace RWBuild\Guhemba\Lib\CustomData\Partial;

use RWBuild\Guhemba\Lib\CustomData\DataType;

/**
 * @property string api_key
 * @property string merchant_key
 * @property string redirect_url
 * @property string public_key
 * @property string base_url
 */
class ConfigMerchantOptionData extends DataType
{

    protected function expectedProperties(): array
    {
        return [
            'GUHEMBA_API_KEY?' => $this->dataType()->string(),
            'GUHEMBA_MERCHANT_KEY?' => $this->dataType()->string(),
            'GUHEMBA_REDIRECT_URL?' => $this->dataType()->string(),
            'GUHEMBA_PUBLIC_KEY?' => $this->dataType()->string(),
            'GUHEMBA_BASE_URL?' => $this->dataType()->string(),
        ];
    }

    public function boot()
    {
        $this->transformProperties();
    }

    private function transformProperties()
    {
        $newData = [];

        foreach ($this->data as $key => $value) {
            $newKey = strtolower(str_replace('GUHEMBA_', '', $key));
            $newData[$newKey] = $value;
        }

        $this->data = $newData;
    }
}

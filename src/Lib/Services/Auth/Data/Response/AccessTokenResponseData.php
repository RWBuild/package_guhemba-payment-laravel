<?php

namespace RWBuild\Guhemba\Lib\Services\Auth\Data\Response;

use RWBuild\Guhemba\Lib\CustomData\ResponseBaseData;

/**
 * @property string token
 * @property string expires_at
 */
class AccessTokenResponseData extends ResponseBaseData
{
    protected function expectedProperties(): array
    {
        return [
            "token" =>  $this->dataType()->string(),
            "expires_at" => $this->dataType()->string()
        ];
    }
}

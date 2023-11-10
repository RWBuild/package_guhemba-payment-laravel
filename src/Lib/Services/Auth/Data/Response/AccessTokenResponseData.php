<?php

namespace RWBuild\Guhemba\Lib\Services\Auth\Data\Response;

use RWBuild\Guhemba\Lib\CustomData\DataType;

class AccessTokenResponseData extends DataType
{
    protected function expectedProperties(): array
    {
        return [
            "token" =>  $this->dataType()->string(),
            "expires_at" => $this->dataType()->string()
        ];
    }
}

<?php

namespace RWBuild\Guhemba\Lib\Services\Auth\Data;

use RWBuild\Guhemba\Lib\CustomData\HttpDataType;
use RWBuild\Guhemba\Lib\Services\Auth\AuthService;
use RWBuild\Guhemba\Lib\CustomData\CollectHttpData;
use RWBuild\Guhemba\Lib\Services\Auth\Data\Response\AccessTokenResponseData;

class RequestPartnerAccessTokenData extends HttpDataType
{
    protected function expectedProperties(): array
    {
        $this->config->partner->throwWhenFieldAbsent('key', 'Missing partner key in the package configuration');

        $this->config->partner->throwWhenFieldAbsent('wallet_key', 'Missing partner wallet key in the package configuration');

        return [
            'partner_key' => $this->dataType()->string($this->config->partner->key),
            'partner_wallet_key' => $this->dataType()->string($this->config->partner->wallet_key),
            'intent' => $this->dataType()->inArray(AuthService::$supportedPartnerIntents),
        ];
    }

    public function httpConfig(CollectHttpData $collectData)
    {
        $collectData->verb('post')
            ->endpoint('token/partner')
            ->body($this->onlyValidated())
            ->responseDataClass(AccessTokenResponseData::class);
    }
}

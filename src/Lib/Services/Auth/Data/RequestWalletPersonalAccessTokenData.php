<?php

namespace RWBuild\Guhemba\Lib\Services\Auth\Data;

use RWBuild\Guhemba\Lib\CustomData\HttpDataType;
use RWBuild\Guhemba\Lib\Services\Auth\AuthService;
use RWBuild\Guhemba\Lib\CustomData\CollectHttpData;
use RWBuild\Guhemba\Lib\Services\Auth\Data\Response\AccessTokenResponseData;

class RequestWalletPersonalAccessTokenData extends HttpDataType
{
    protected function expectedProperties(): array
    {
        $this->config->option->throwWhenFieldAbsent('api_key', 'Missing guhemba api key in the package configuration');

        $this->config->option->throwWhenFieldAbsent('public_key', 'Missing guhemba public key in the package configuration');

        $tokenIntents = AuthService::$supportedWalletIntents;

        return [
            'merchant_key' => $this->property()->string($this->config->option->merchant_key),
            'intents' =>  $this->dataType()
                ->isArrayOf(function ($intent) use ($tokenIntents) {

                    if (!in_array($intent, $tokenIntents)) return false;

                    return true;
                }),
            'is_from_3d_party' => $this->dataType()->bool(true),
            'api_key' => $this->dataType()->string($this->config->option->api_key),
            'wallet_public_key' => $this->dataType()->string($this->config->option->public_key),
        ];
    }

    public function httpConfig(CollectHttpData $collectData)
    {
        $collectData->verb('post')
            ->endpoint('token/request-personal-access-token')
            ->body($this->onlyValidated())
            ->responseDataClass(AccessTokenResponseData::class);
    }
}

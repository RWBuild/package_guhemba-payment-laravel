<?php

namespace RWBuild\Guhemba\Lib\Interface;

use RWBuild\Guhemba\Lib\Interface\Base\ServiceBaseInterface;
use RWBuild\Guhemba\Lib\Services\Auth\Data\Response\AccessTokenResponseData;

interface AuthServiceInterface extends ServiceBaseInterface
{
    /**
     * Request partner access token from guhemba
     */
    public function partnerAccessToken(array $options = []): AccessTokenResponseData;
}

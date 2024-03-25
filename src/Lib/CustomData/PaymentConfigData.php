<?php

namespace RWBuild\Guhemba\Lib\CustomData;

use RWBuild\Guhemba\Lib\CustomData\DataType;
use RWBuild\Guhemba\Exceptions\GuhembaPayException;
use RWBuild\Guhemba\Lib\CustomData\Partial\ConfigPartnerData;
use RWBuild\Guhemba\Lib\CustomData\Partial\ConfigMerchantOptionData;

/**
 * @property ConfigMerchantOptionData option
 * @property ConfigPartnerData partner
 * @property string redirect_url
 * @property string base_url
 */
class PaymentConfigData extends DataType
{

    protected function expectedProperties(): array
    {
        return [
            'option' => $this->dataType(ConfigMerchantOptionData::class),
            'redirect_url' => $this->dataType()->string(),
            'base_url' => $this->dataType()->string(),
            'partner' => $this->dataType(ConfigPartnerData::class)
        ];
    }

    private function authKey()
    {
        $this->option->throwWhenFieldAbsent(
            ['api_key', 'public_key'],
            'Make sure the api_key and the public_key are set in the config file'
        );

        return $this->option->api_key . '_authkey_' . $this->option->public_key;
    }

    /**
     * Encrypt some public keys for security
     * This key will only be used on third-party
     * an an auth key for the requests defined in this
     * paclage
     */
    public function encryptedKey()
    {
        return encrypt($this->authKey());
    }

    /**
     * check if the encypted matches the merchant auth key
     */
    public function checkAuthKey($encryptedValue)
    {
        try {
            return decrypt($encryptedValue) === $this->authKey();
        } catch (\Throwable $th) {
            throw new GuhembaPayException("GUHEMBA WEB-ELEMENT - Request UnAuthorized - Please check the auth key", 403);
        }
    }
}

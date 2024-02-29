<?php

namespace RWBuild\Guhemba\Lib\Services\Auth;

use RWBuild\Guhemba\Lib\Base\GuhembaPaymentBaseService;
use RWBuild\Guhemba\Lib\Interface\AuthServiceInterface;
use RWBuild\Guhemba\Lib\Services\Auth\Data\RequestPartnerAccessTokenData;
use RWBuild\Guhemba\Lib\Services\Auth\Data\Response\AccessTokenResponseData;
use RWBuild\Guhemba\Lib\Services\Auth\Data\RequestWalletPersonalAccessTokenData;

class AuthService extends GuhembaPaymentBaseService implements AuthServiceInterface
{
    /**
     * Partners intens or scopes names
     */
    const INTENT_CREATE_GROUP_PURCHASE = "create-group-purchase";
    const INTENT_RELEASE_GROUP_PURCHASE = "release-group-purchase";
    const INTENT_CANCEL_GROUP_PURCHASE = "cancel-group-purchase";
    const INTENT_REQUEST_PAYMENT_APPROVAL = "request-payment-approval";
    const INTENT_GEN_GROUP_PURCHASE_QRCODE = "generate-group-purchase-qrcode";
    const INTENT_REFUND_PAYMENT = "refund-payment";

    /**
     * all supported partner INTENT names
     */
    static $supportedPartnerIntents = [
        self::INTENT_CREATE_GROUP_PURCHASE,
        self::INTENT_RELEASE_GROUP_PURCHASE,
        self::INTENT_CANCEL_GROUP_PURCHASE,
        self::INTENT_REQUEST_PAYMENT_APPROVAL,
        self::INTENT_GEN_GROUP_PURCHASE_QRCODE,
        self::INTENT_REFUND_PAYMENT
    ];

    /**
     * Non partner  merchant wallets scopes names
     */
    const TOKENSCOPE_REFUND_FROM_3DPARTY = 'refund_from_3dparty';
    static $supportedWalletIntents = [
        self::TOKENSCOPE_REFUND_FROM_3DPARTY
    ];

    public  function partnerAccessToken(array $options = []): AccessTokenResponseData
    {
        return $this->sendRequest(
            RequestPartnerAccessTokenData::make($this->inputs($options))
        );
    }

    public  function walletPersonalAccessToken(array $options = []): AccessTokenResponseData
    {
        return $this->sendRequest(
            RequestWalletPersonalAccessTokenData::make($this->inputs($options))
        );
    }
}

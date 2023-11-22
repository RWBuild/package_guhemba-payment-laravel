<?php

namespace RWBuild\Guhemba\Lib\Services\Auth;

use RWBuild\Guhemba\Lib\Base\GuhembaPaymentBaseService;
use RWBuild\Guhemba\Lib\Interface\AuthServiceInterface;
use RWBuild\Guhemba\Lib\Services\Auth\Data\RequestPartnerAccessTokenData;
use RWBuild\Guhemba\Lib\Services\Auth\Data\Response\AccessTokenResponseData;

class AuthService extends GuhembaPaymentBaseService implements AuthServiceInterface
{
    const INTENT_CREATE_GROUP_PURCHASE = "create-group-purchase";
    const INTENT_RELEASE_GROUP_PURCHASE = "release-group-purchase";
    const INTENT_CANCEL_GROUP_PURCHASE = "cancel-group-purchase";
    const INTENT_REQUEST_PAYMENT_APPROVAL = "request-payment-approval";
    const INTENT_GEN_GROUP_PURCHASE_QRCODE = "generate-group-purchase-qrcode";
    const INTENT_REFUND_PAYMENT = "refund-payment";

    /**
     * all supported partner INTENT names
     */
    static $supportedIntents = [
        self::INTENT_CREATE_GROUP_PURCHASE,
        self::INTENT_RELEASE_GROUP_PURCHASE,
        self::INTENT_CANCEL_GROUP_PURCHASE,
        self::INTENT_REQUEST_PAYMENT_APPROVAL,
        self::INTENT_GEN_GROUP_PURCHASE_QRCODE,
        self::INTENT_REFUND_PAYMENT
    ];

    public  function partnerAccessToken(array $options = []): AccessTokenResponseData
    {
        return $this->sendRequest(
            RequestPartnerAccessTokenData::make($this->inputs($options))
        );
    }
}

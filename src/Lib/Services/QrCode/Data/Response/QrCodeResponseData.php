<?php

namespace RWBuild\Guhemba\Lib\Services\QrCode\Data\Response;

use RWBuild\Guhemba\Lib\CustomData\DataType;
use RWBuild\Guhemba\Lib\CustomData\ResponseBaseData;

/**
 * @property numeric id
 * @property string content
 * @property string image
 * @property numeric amount
 * 
 * @property string transaction_type
 * @property string expires_at
 * @property string wallet_name
 * @property string slug
 * @property string payment_ref
 * @property string confirm_payment_key
 * @property string web_element_page
 * @property array supported_payment_options
 */
class QrCodeResponseData  extends ResponseBaseData
{
    protected function expectedProperties(): array
    {
        return [
            'id',
            'content',
            'image',
            'amount',
            'transaction_type',
            'expires_at?',
            'wallet_name?',
            'slug',
            'payment_ref?',
            'supported_payment_options?',

            // used by the package
            'public_auth_key?' => $this->dataType()->string()->default($this->encryptedKey())
        ];
    }

    public static function formatResponse(array $responseJson): array
    {
        return $responseJson['qrcode'];
    }
}

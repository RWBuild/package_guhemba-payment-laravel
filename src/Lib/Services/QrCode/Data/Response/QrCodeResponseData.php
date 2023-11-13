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
 */
class QrCodeResponseData  extends ResponseBaseData
{
    protected function expectedProperties(): array
    {
        return [
            'id' => $this->dataType()->numeric(),
            'content' => $this->dataType()->string(),
            'image' => $this->dataType()->string(),
            'amount' => $this->dataType()->numeric(),
            'transaction_type' => $this->dataType()->string(),
            'expires_at?' => $this->dataType()->string(),
            'wallet_name?' => $this->dataType()->string(),
            'slug' => $this->dataType()->string(),
        ];
    }

    public static function formatResponse(array $responseJson): array
    {
        return $responseJson['qrcode'];
    }
}

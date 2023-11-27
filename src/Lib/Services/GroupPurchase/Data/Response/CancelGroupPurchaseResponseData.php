<?php

namespace RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response;

use RWBuild\Guhemba\Lib\CustomData\ResponseBaseData;

/**
 * @property number id
 * @property string purchase_code
 * @property string status
 * @property string created_at
 * @property number total_amount
 * @property string closed_at
 * @property bool has_webhook_confirmation
 */
class CancelGroupPurchaseResponseData extends ResponseBaseData
{
    protected function expectedProperties(): array
    {
        return [
            "id?",
            "purchase_code?",
            "status?",
            "created_at?",
            'total_amount?',
            'closed_at?',
            'has_webhook_confirmation'
        ];
    }

    public static function formatResponse(array $responseJson): array
    {
        return array_merge($responseJson['group_purchase'], [
            'has_webhook_confirmation' => $responseJson['has_webhook_confirmation']
        ]);
    }

    /**
     * It's true when Guhemba is processing the cancelation request
     * in the background and will confirm it once the process is done. 
     */
    public function shouldExpectWebHook(): bool
    {
        return (bool)$this->has_webhook_confirmation;
    }
}

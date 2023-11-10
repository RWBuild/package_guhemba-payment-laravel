<?php

namespace RWBuild\Guhemba\Lib\Interface;

use RWBuild\Guhemba\Lib\CustomData\PaymentConfigData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\GroupPurchaseService;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response\CancelGroupPurchaseResponseData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response\CreateGroupPurchaseResponseData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response\GroupPurchaseDetailResponseData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response\ReleaseGroupPurchaseResponseData;

interface GroupPurchaseServiceInterface
{
    /**
     * Create a group purchase
     */
    public function create(array $options): CreateGroupPurchaseResponseData;

    /**
     * release a group purchase
     */
    public function release(array $options): ReleaseGroupPurchaseResponseData;

    /**
     * cancel a group purchase
     */
    public function cancel(array $options): CancelGroupPurchaseResponseData;

    /**
     * get the details info of a given group purchase
     */
    public function info(array $options): GroupPurchaseDetailResponseData;

    /**
     * Load dependency of the service
     */
    public static function make(PaymentConfigData $config);
}

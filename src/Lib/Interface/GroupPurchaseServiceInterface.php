<?php

namespace RWBuild\Guhemba\Lib\Interface;

use RWBuild\Guhemba\Lib\Interface\Base\ServiceBaseInterface;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response\CancelGroupPurchaseResponseData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response\CreateGroupPurchaseResponseData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response\GroupPurchaseDetailResponseData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response\ReleaseGroupPurchaseResponseData;
use RWBuild\Guhemba\Lib\Services\QrCode\Data\Response\QrCodeResponseData;

interface GroupPurchaseServiceInterface extends ServiceBaseInterface
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
     * Generate payment qrcode for a given group purchase code
     */
    public function generateQrcode(array $options): QrCodeResponseData;
}

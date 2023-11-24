<?php

namespace RWBuild\Guhemba\Lib\Services\GroupPurchase;

use RWBuild\Guhemba\Lib\Base\GuhembaPaymentBaseService;
use RWBuild\Guhemba\Lib\CustomData\Response\SuccessResponseData;
use RWBuild\Guhemba\Lib\Interface\GroupPurchaseServiceInterface;
use RWBuild\Guhemba\Lib\Services\QrCode\Data\Response\QrCodeResponseData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\CancelGroupPurchaseData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\CreateGroupPurchaseData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\ReleaseGroupPurchaseData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\AddGroupPurchaseMerchantData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\FetchGroupPurchaseDetailData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response\CancelGroupPurchaseResponseData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response\CreateGroupPurchaseResponseData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response\GroupPurchaseDetailResponseData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response\ReleaseGroupPurchaseResponseData;

class GroupPurchaseService extends GuhembaPaymentBaseService implements GroupPurchaseServiceInterface
{
    public function create(array $options): CreateGroupPurchaseResponseData
    {
        return $this->sendRequest(
            CreateGroupPurchaseData::make($this->inputs($options))
        );
    }

    public function info(array $options): GroupPurchaseDetailResponseData
    {
        return $this->sendRequest(
            FetchGroupPurchaseDetailData::make($this->inputs($options))
        );
    }

    public function release(array $options): ReleaseGroupPurchaseResponseData
    {
        return $this->sendRequest(
            ReleaseGroupPurchaseData::make($this->inputs($options))
        );
    }

    public function cancel(array $options): CancelGroupPurchaseResponseData
    {
        return $this->sendRequest(
            CancelGroupPurchaseData::make($this->inputs($options))
        );
    }

    public function generateQrcode(array $options): QrCodeResponseData
    {
        $options = array_merge($options, [
            'support_group_purchase' => true
        ]);

        return $this->gate()->qrCode->generate($options);
    }

    public function addMerchant(array $options): SuccessResponseData
    {
        return $this->sendRequest(
            AddGroupPurchaseMerchantData::make($this->inputs($options))
        );
    }
}

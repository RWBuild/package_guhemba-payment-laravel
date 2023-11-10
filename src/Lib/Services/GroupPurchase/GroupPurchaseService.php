<?php

namespace RWBuild\Guhemba\Lib\Services\GroupPurchase;

use RWBuild\Guhemba\Lib\Services\GuhembaPaymentService;
use RWBuild\Guhemba\Lib\Interface\GroupPurchaseServiceInterface;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Actions\CancelGroupPurchaseAction;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Actions\CreateGroupPurchaseAction;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Actions\ReleaseGroupPurchaseAction;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Actions\FetchGroupPurchaseDetailAction;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response\CancelGroupPurchaseResponseData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response\CreateGroupPurchaseResponseData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response\GroupPurchaseDetailResponseData;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\Response\ReleaseGroupPurchaseResponseData;

class GroupPurchaseService extends GuhembaPaymentService implements GroupPurchaseServiceInterface
{
    public function create(array $options): CreateGroupPurchaseResponseData
    {
        return CreateGroupPurchaseAction::process($this->inputs($options));
    }

    public function info(array $options): GroupPurchaseDetailResponseData
    {
        return FetchGroupPurchaseDetailAction::process($this->inputs($options));
    }

    public function release(array $options): ReleaseGroupPurchaseResponseData
    {
        return ReleaseGroupPurchaseAction::process($this->inputs($options));
    }

    public function cancel(array $options): CancelGroupPurchaseResponseData
    {
        return CancelGroupPurchaseAction::process($this->inputs($options));
    }
}

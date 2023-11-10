<?php

namespace RWBuild\Guhemba\Lib\Services\GroupPurchase\Actions;

use Kakaprodo\CustomData\Helpers\CustomActionBuilder;
use RWBuild\Guhemba\Lib\Services\GroupPurchase\Data\CreateGroupPurchaseData;

class CreateGroupPurchaseAction extends CustomActionBuilder
{
    public function handle(CreateGroupPurchaseData $data)
    {
        dd($data->all());
    }
}

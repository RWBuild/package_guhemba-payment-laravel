<?php

namespace RWBuild\Guhemba\Lib\Actions;

use RWBuild\Guhemba\Exceptions\GuhembaHttpException;
use Kakaprodo\CustomData\Helpers\CustomActionBuilder;
use RWBuild\Guhemba\Lib\CustomData\HandleHttpErrorData;

class HandleHttpErrorAction extends CustomActionBuilder
{
    public function handle(HandleHttpErrorData $data)
    {
        throw new GuhembaHttpException($data);
    }
}

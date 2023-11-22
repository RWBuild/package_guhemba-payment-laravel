<?php

namespace RWBuild\Guhemba\Lib\CustomData\Response;

use RWBuild\Guhemba\Lib\CustomData\ResponseBaseData;

/**
 * @property bool success
 * @property string message
 */
class SuccessResponseData  extends ResponseBaseData
{
    protected function expectedProperties(): array
    {
        return [
            'success?',
            'message?',
        ];
    }
}

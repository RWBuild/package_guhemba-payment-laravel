<?php

namespace RWBuild\Guhemba\Lib\CustomData;

use RWBuild\Guhemba\Lib\CustomData\DataType;
use RWBuild\Guhemba\Lib\CustomData\CollectHttpData;


abstract class HttpDataType extends DataType
{
    /**
     * Where to config http data
     */
    abstract public function httpConfig(CollectHttpData $collectData);
}

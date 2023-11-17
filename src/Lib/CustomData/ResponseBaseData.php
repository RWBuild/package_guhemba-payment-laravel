<?php

namespace RWBuild\Guhemba\Lib\CustomData;

use RWBuild\Guhemba\Lib\CustomData\DataType;


abstract class ResponseBaseData extends DataType
{
    /**
     * Format response payload based on the expected properties
     */
    public static function formatResponse(array $responseJson): array
    {
        return $responseJson;
    }

    /**
     * convert data array to object
     */
    public function toObject()
    {
        return (object) $this->all();
    }
}

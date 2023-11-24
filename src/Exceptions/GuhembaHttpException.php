<?php

namespace RWBuild\Guhemba\Exceptions;

use Exception;
use Illuminate\Support\Arr;
use RWBuild\Guhemba\Lib\CustomData\HandleHttpErrorData;

class GuhembaHttpException extends Exception
{
    /**
     * will contain additional data in the response
     * @var HandleHttpErrorData
     */
    public $data;

    /**
     * will contain the error status
     * default = 400
     * @var int
     */
    protected $status = 400;

    public function __construct(HandleHttpErrorData $data)
    {
        parent::__construct($data->message);
        $this->status = $data->status;
        $this->data = $data;
    }

    public function render()
    {
        return Arr::except($this->data->all(), ['response_json', 'response']);
    }

    public function __get($name)
    {
        return $this->data->$name;
    }
}

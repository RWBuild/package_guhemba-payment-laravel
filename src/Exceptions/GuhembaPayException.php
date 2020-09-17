<?php

namespace RWBuild\Guhemba\Exceptions;

use Exception;

class GuhembaPayException extends Exception
{
    /**
     * will contain additional data in the response
     * @var Array
     */
    protected $data = [];

    /**
     * will contain the error status
     * default = 400
     * @var int
     */
    protected $status = 400;

    public function __construct($message, $status = null)
    {
        parent::__construct($message);
        $this->status = !$status? $this->status : $status;
    }

    public function getStatusCode()
    {
        return $this->status;
    }

    public function isSuccess()
    {
        return false;
    }

    public function getFormatedMessage()
    {
        return (object) $this->buildData();
    }

    /**
     * Laravel property
     * This will be called by the framework
     */
    public function render()
    {
        $resp = $this->buildResponse();

        return $resp;
    }

    /**
     * Data that will be appended to the response
     */
    public function withData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Set a status
     */
    public function withStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Response to return for this exception
     */
    public function buildResponse()
    {
        return response()->json($this->buildData(), $this->status);
    }


    /**
     * arrange the response message
     */
    public function buildData()
    {
        if ($this->data == []) {
            return [
                'success' => false,
                'message' => $this->getMessage()
            ];
        }

        return [
            'success' => false,
            'message' => $this->getMessage(),
            'details' => $this->data,
        ];
    }
}

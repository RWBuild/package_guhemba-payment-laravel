<?php

namespace RWBuild\Guhemba\Lib\Interface;

use RWBuild\Guhemba\Lib\Interface\Base\ServiceBaseInterface;
use RWBuild\Guhemba\Lib\Services\Transaction\Data\Response\TransactionResponseData;

interface TransactionServiceInterface extends ServiceBaseInterface
{
    /**
     * Fetch transaction from a code that is provided in 
     * guhemba callback request
     */
    public function fetchFromCallback(): TransactionResponseData;

    /**
     * Fetch transaction info from its token
     */
    public function fetchFromToken(array $options): TransactionResponseData;

    /**
     * Fetch transaction info from the generated Qrcode id
     */
    public function fetchFromQrcode(array $options): TransactionResponseData;

    /**
     * Fetch transaction from qrcode payment_ref
     */
    public function fetchFromReference(array $options): TransactionResponseData;

    /**
     * Refund a payment transaction
     */
    public function refund(array $options): TransactionResponseData;
}

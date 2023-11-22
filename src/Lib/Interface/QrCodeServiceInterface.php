<?php

namespace RWBuild\Guhemba\Lib\Interface;

use Illuminate\Http\RedirectResponse;
use RWBuild\Guhemba\Lib\Interface\Base\ServiceBaseInterface;
use RWBuild\Guhemba\Lib\CustomData\Response\SuccessResponseData;
use RWBuild\Guhemba\Lib\Services\QrCode\Data\Response\QrCodeResponseData;

interface QrCodeServiceInterface extends ServiceBaseInterface
{
    /**
     * Generate a payment Qrcode
     */
    public function generate(array $options = []): QrCodeResponseData;

    /**
     * Redirect to the guhemba payment page of the qrcode
     */
    public function redirect(array $options = []): RedirectResponse;

    /**
     * Send the payment approval process to a guhemba account
     */
    public function requestPaymentApproval(array $options = []): SuccessResponseData;
}

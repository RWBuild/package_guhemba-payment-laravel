<?php

namespace RWBuild\Guhemba\Lib\Services\QrCode\Data;

use Illuminate\Support\Arr;
use RWBuild\Guhemba\Lib\CustomData\HttpDataType;
use RWBuild\Guhemba\Lib\CustomData\CollectHttpData;

class RedirectQrcodeToPaymentPageData extends HttpDataType
{
    /**
     * The payment option page.based on this value
     * the package will redirect user to an appropriate
     * page
     */
    public static $paymentOption = 'choice';

    static $supportedpaymentOptions = [
        'choice',
        'card',
        'mtn'
    ];

    /**
     * For partner thordt-party it can be dru(dynamic redirect urk) 
     */
    protected $redirectFieldName = "redirect_url";

    protected function expectedProperties(): array
    {
        return [
            'qrcode_slug' => $this->dataType()->string(request()->qrcode_slug),
            'payment_ref' => $this->dataType()->string(request()->payment_ref),
            'payment_option?' => $this->dataType()
                ->inArray(static::$supportedpaymentOptions)
                ->default(request()->payment_option ?? static::$paymentOption),

            // when it provided we will consider it as the partner's: dynamic redirect url
            'redirect_url?' => $this->dataType()->string()
        ];
    }

    public function boot()
    {
        if ($this->redirect_url) {
            $this->redirectFieldName = 'dru';

            // check if the partner public key exists
            $this->config->partner->throwWhenFieldAbsent(
                'public_key',
                'When you provide a dynamic redirect_url, You must consider providing'
                    . ' the partner public key in the configuration file'
            );
        } else {
            // check if the merchant redirect_url  exists
            $this->config->option->throwWhenFieldAbsent(
                'redirect_url',
                'The redirect url is missing in the configuration file'
            );
        }
    }

    public function httpConfig(CollectHttpData $collectData)
    {
        $url = $this->config->base_url . "/rwpay-element/process-qrcode/{$this->qrcode_slug}";

        $collectData->verb('redirect')
            ->endpoint($url)
            ->body(array_merge(Arr::except($this->onlyValidated(), 'qrcode_slug'), [
                'public_key' => $this->config->option->public_key,
                $this->redirectFieldName => $this->redirect_url ?? $this->config->option->redirect_url,
                'ppk' => $this->config->partner->public_key,
                'state' => 'ignored'
            ]));
    }
}

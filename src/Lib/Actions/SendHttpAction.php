<?php

namespace RWBuild\Guhemba\Lib\Actions;

use RWBuild\Guhemba\Lib\CustomData\SendHttpData;
use Kakaprodo\CustomData\Helpers\CustomActionBuilder;

class SendHttpAction extends CustomActionBuilder
{
    public function handle(SendHttpData $data)
    {
        $response =  $data->send();

        if ($response->failed()) return dd($response->json());

        if (!$data->response_custom_data) return $response->json();

        return $data->response_custom_data::make($response->json());
    }
}

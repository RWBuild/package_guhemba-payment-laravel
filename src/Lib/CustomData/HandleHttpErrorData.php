<?php

namespace RWBuild\Guhemba\Lib\CustomData;

use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Illuminate\Http\Client\Response;
use RWBuild\Guhemba\Lib\CustomData\DataType;


/**
 * @property string message
 * @property number status
 * @property string reason
 * @property string reason_hint
 * @property number reason_code
 * @property Response response
 */
class HandleHttpErrorData extends DataType
{
    protected function expectedProperties(): array
    {
        return [
            'response' => $this->dataType(Response::class),
        ];
    }

    public function boot()
    {
        $responseJson = $this->response->json();
        $this->response_json = $responseJson;

        $this->response_json = $responseJson;

        $this->message = $responseJson['message'] ?? $responseJson['error'] ?? 'undefined';
        $this->status = $this->response->status();

        $this->format401Errors();

        $this->format422StatusErrors();

        $this->formatErrorReason();
    }

    /**
     * format inputs validation errors
     */
    private function format422StatusErrors()
    {
        if ($this->status != HttpResponse::HTTP_UNPROCESSABLE_ENTITY) return;

        $errors =  $this->response_json['errors'];
        $this->message .= ': ';

        foreach ($errors as  $messages) {
            foreach ($messages as $message) {
                $this->message .= "{$message}; ";
            }
        }
    }

    /**
     * Format the reason of the error
     */
    private function formatErrorReason()
    {
        if (!isset($this->response_json['data'])) return;

        $dataReason = $this->response_json['data'];

        if (!isset($dataReason['reason'])) return;

        $this->reason = $dataReason['reason'];
        $this->reason_hint = $dataReason['reason_hint'];
        $this->reason_code = $dataReason['code'];
    }

    private function format401Errors()
    {
        if ($this->status != HttpResponse::HTTP_UNAUTHORIZED) return;

        $this->message = "Unauthenticated, Please check the keys in the config file";
    }
}

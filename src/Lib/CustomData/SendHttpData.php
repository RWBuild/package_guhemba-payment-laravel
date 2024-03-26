<?php

namespace RWBuild\Guhemba\Lib\CustomData;

use ReflectionClass;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Kakaprodo\CustomData\Lib\TypeHub\DataTypeHub;
use RWBuild\Guhemba\Lib\CustomData\PaymentConfigData;

class SendHttpData extends DataType
{
    protected function expectedProperties(): array
    {

        return [
            'verb' => $this->dataType()->inArray(['get', 'post', 'put', 'delete', 'redirect']),
            'endpoint' => $this->dataType()->string(),
            'headers' => $this->dataType()->array([]),
            'body?' => $this->dataType()->array([]),
            'response_custom_data?' =>  $this->dataType()->customValidator(function ($class, DataTypeHub $validator) {
                if (!class_exists($class)) {
                    $validator->message('The response_custom_data must be a valid class');
                    return false;
                }

                return true;
            }),
            'config' => $this->dataType(PaymentConfigData::class)
        ];
    }

    /**
     * Send the the request
     * 
     * @return \Illuminate\Http\Client\Response
     */
    public function send()
    {
        $verb = $this->verb;
        return Http::withHeaders(
            $this->formatHeaders()
        )->$verb($this->makeEndpoint(), $this->body);
    }

    /**
     * Redirect away
     */
    public function redirect()
    {
        $query = http_build_query($this->body);

        return redirect()->away($this->endpoint . '/?' . $query);
    }

    /**
     * check if the http verb is about redirection
     */
    public function isRedirection()
    {
        return $this->verb == 'redirect';
    }

    /**
     * Combine guhemba base url with the provided endpoint
     */
    private function makeEndpoint()
    {
        return Str::finish($this->config->base_url, '/')
            . 'api/third-party/'
            . $this->removeFirst('/', $this->endpoint);
    }

    private function removeFirst($firstCharacter, $content)
    {

        if (!Str::startsWith($content, $firstCharacter)) return $content;

        return Str::replaceFirst($firstCharacter, '', $content);
    }

    private function formatHeaders()
    {
        return array_merge([
            'Accept' => 'application/json',
            'API-KEY' => $this->config->option->api_key,
            'MERCHANT-KEY' => $this->config->option->merchant_key,
            'PUBLIC-KEY' => $this->config->option->public_key,
        ], $this->headers);
    }

    /**
     * Decide how to pass response json to customData class
     */
    public function decideResponseDataFormat(array $responseJson): array
    {
        $responseJson = isset($responseJson['data'])
            ? $responseJson['data']
            : $responseJson;

        $customDataClass = $this->response_custom_data;

        $reflectionClass = new ReflectionClass($customDataClass);

        //the method name that is going format response
        $methodName = 'formatResponse';

        if (!$reflectionClass->hasMethod($methodName)) return $responseJson;

        if ($reflectionClass->getMethod($methodName)->isStatic()) {
            return $customDataClass::$methodName($responseJson);
        }

        return $responseJson;
    }
}

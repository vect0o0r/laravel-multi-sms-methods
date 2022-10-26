<?php

namespace Vector\LaravelMultiSmsMethods\Methods;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use JsonException;

/**
 * Driver class.
 *
 * @author Vector <mo.khaled.yousef@gmail.com>
 */
abstract class BaseMethod
{
    /**
     * enableSendSms.
     *
     * @var bool
     */
    protected bool $enableSendSms;
    /**
     * supportedTypes.
     *
     * @var array
     */
    protected array $supportedTypes;
    /**
     * Driver.
     *
     * @var string
     */
    protected string $driver;
    /**
     * Driver.
     *
     * @var string
     */
    protected string $base_url;
    /**
     * config.
     *
     * @var object
     */
    protected object $config;
    /**
     * config.
     *
     * @var PendingRequest
     */
    protected PendingRequest $client;

    /**
     * Boot Method And Set Method Configurations And HttpClient
     *
     * @return void
     */
    public function __construct()
    {
        $this->setConfigurations();
        $this->buildHttpClient();
    }

    /**
     * Set Method Configurations From Config File
     *
     * @return void
     */
    protected function setConfigurations(): void
    {
        $this->enableSendSms = config("sms-methods.enable_send_sms");
        $this->config = (object)config("sms-methods.methods.$this->driver");
    }

    /**
     * Start Creating Http Client To Send Request
     *
     * @return PendingRequest
     */
    protected function buildHttpClient(): PendingRequest
    {
        return $this->client = Http::baseUrl($this->base_url);
    }

    /**
     * Return The Response Of The Method Action
     *
     * @param int $code
     * @param bool $success
     * @param string $message
     * @param array|null $data
     * @return array
     */
    public function response(int $code, bool $success, string $message, array $data = null): array
    {
        return ["code" => $code, "success" => $success, "message" => $message, "data" => $data];
    }

    /**
     * Convert Soap To Json
     *
     * @param $soap
     * @return mixed
     * @throws JsonException
     */
    public function soapToJson($soap): mixed
    {
        // Load xml data into xml data object
        $xmldata = simplexml_load_string($soap);
        // using json_encode function && Encode this xml data into json
        return json_decode(json_encode($xmldata, JSON_THROW_ON_ERROR), false, 512, JSON_THROW_ON_ERROR);
    }
}

<?php

namespace Vector\LaravelMultiSmsMethods\Methods;

use JsonException;
use Vector\LaravelMultiSmsMethods\Constants\MethodTypes;
use Vector\LaravelMultiSmsMethods\Interfaces\SmsGatewayInterface;

/**
 * SmsBox class.
 *
 * @author Vector <mo.khaled.yousef@gmail.com>
 */
class Smsbox extends BaseMethod implements SmsGatewayInterface
{

    /**
     * Set Method Driver And Base Url
     *
     * @return void
     * @throws JsonException
     */
    public function __construct()
    {
        //Set Method Driver
        $this->driver = 'smsbox';
        //Set Method Base Url
        $this->base_url = "https://smsbox.com";
        //Set Method Supported Type
        $this->supportedTypes = [MethodTypes::SMS->value];
        //Set Config Required Keys
        $this->requiredConfigKeys = ['username', 'password', 'gateway_id', 'sender_id'];
        //Calling Parent Constructor
        parent::__construct();
    }


    /**
     * Send sms message.
     *
     * @param string $phones
     * @param string $message
     * @param string|null $scheduleDate
     * @return array
     * @throws JsonException
     */
    public function send(string $phones, string $message, string|null $scheduleDate = ''): array
    {
        if (!$this->enableSendSms)
            return $this->response(400, false, "Sme Sender Is Disabled");
        $requestBody = $this->buildSmsRequest($phones, $message, $scheduleDate);
        $response = $this->client->get('SMSGateway/Services/Messaging.asmx/Http_SendSMS', $requestBody);
        $jsonResponse = $this->soapToJson($response->body());
        $success = $jsonResponse->Result == 'true' ? true : false;
        return $this->response($response->status(), $success, $jsonResponse->Message, (array)$jsonResponse);
    }

    /**
     * Handle Single sms message.
     *
     * @param string $phone
     * @param string $message
     * @param string|null $scheduleDate
     * @return array
     * @throws JsonException
     */
    public function sendSms(string $phone, string $message, string|null $scheduleDate = ''): array
    {
        return $this->send($phone, $message, $scheduleDate);
    }

    /**
     * Handle Multi sms message.
     *
     * @param array $phonesArray
     * @param string $message
     * @param string|null $scheduleDate
     * @return array
     * @throws JsonException
     */
    public function sendMultiSms(array $phonesArray, string $message, string|null $scheduleDate = ''): array
    {
        $phones = implode(',', $phonesArray);
        return $this->send($phones, $message, $scheduleDate);
    }

    /**
     * Build Sms Request Body
     *
     * @param string $phone
     * @param string $message
     * @param string|null $scheduleDate
     * @return array
     */
    public function buildSmsRequest(string $phone, string $message, string|null $scheduleDate = ''): array
    {
        return [
            'username' => $this->config?->username,
            'password' => $this->config?->password,
            'customerId' => $this->config?->gateway_id,
            'senderText' => $this->config?->sender_id,
            'messageBody' => $message,
            'recipientNumbers' => $phone,
            'defDate' => $scheduleDate,
            'isBlink' => false,
            'isFlash' => false
        ];
    }
}

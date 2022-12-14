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
     * Used To Start Calling Provider Api
     * @param string $phone
     * @param string $message
     * @param string|null $scheduleDate
     * @return array
     * @throws JsonException
     */
    public function send(string $phone, string $message, string|null $scheduleDate = ''): array
    {
        if (!$this->enableSendSms)
            return $this->response(400, false, "Sme Sender Is Disabled");
        $requestBody = $this->buildSmsRequest($phone, $message, $scheduleDate);
        $response = $this->client->get('SMSGateway/Services/Messaging.asmx/Http_SendSMS', $requestBody);
        $jsonResponse = $this->soapToJson($response->body());
        $success = $jsonResponse->Result == 'true' ? true : false;
        return $this->response($response->status(), $success, $jsonResponse->Message, (array)$jsonResponse);
    }

    /**
     * Send Single Sms message.
     *
     * @param string $phone
     * @param string $message
     * @return array
     * @throws JsonException
     */
    public function sendSms(string $phone, string $message): array
    {
        return $this->send($phone, $message);
    }

    /**
     * Send Multi Sms message.
     *
     * @param array $phonesArray
     * @param string $message
     * @return array
     * @throws JsonException
     */
    public function sendMultiSms(array $phonesArray, string $message): array
    {
        $phones = implode(',', $phonesArray);
        return $this->send($phones, $message);
    }

    /**
     * Send Scheduled Sms message.
     *
     * @param string $phone
     * @param string $message
     * @param string $scheduleDate
     * @return array
     * @throws JsonException
     */
    public function sendScheduleSms(string $phone, string $message, string $scheduleDate): array
    {
        return $this->send($phone, $message, $scheduleDate);
    }

    /**
     * Used To Send OTP message.
     *
     * @param string $phone
     * @param int|null $otp
     * @return array
     */
    public function sendOtp(string $phone, int $otp = null): array
    {
        return $this->response(404, false, "This Methods Is Not Supported In {$this->driver} Yet", []);
    }

    /**
     *  Check Sent OTP message.
     *
     * @param string $phone
     * @param int $otp
     * @return array
     */
    public function checkOtp(string $phone, int $otp): array
    {
        return $this->response(404, false, "This Methods Is Not Supported In {$this->driver} Yet", []);
    }

    /**
     * Get Sms Details
     *
     * @param string $smsID
     * @return array
     */
    public function getSmsDetails(string $smsID): array
    {
        return $this->response(404, false, "This Methods Is Not Supported In {$this->driver} Yet", []);
    }

    /**
     * Get Account Available Balance.
     *
     * @return array
     */
    public function getBalance(): array
    {
        return $this->response(404, false, "This Methods Is Not Supported In {$this->driver} Yet", []);
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

<?php

namespace Vector\LaravelMultiSmsMethods\Methods;

use JsonException;
use Vector\LaravelMultiSmsMethods\Constants\MethodTypes;
use Vector\LaravelMultiSmsMethods\Interfaces\SmsGatewayInterface;

/**
 * Twilio class.
 *
 * @author Vector <mo.khaled.yousef@gmail.com>
 */
class Twilio extends BaseMethod implements SmsGatewayInterface
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
        $this->driver = 'twilio';
        //Set Method Base Url
        $this->base_url = "https://api.twilio.com";
        //Set Method Supported Type
        $this->supportedTypes = [MethodTypes::SMS->value];
        //Set Config Required Keys
        $this->requiredConfigKeys = ['account_sid', 'auth_token', 'sms_phone_number'];
        //Calling Parent Constructor
        parent::__construct();
        //Init Http Client With Additional Configs
        $this->client->withBasicAuth($this->config?->account_sid, $this->config?->auth_token);
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
    public function send(string $phone, string $message, string|null $scheduleDate = null): array
    {
        if (!$this->enableSendSms)
            return $this->response(400, false, "Sme Sender Is Disabled");
        $requestBody = $this->buildSmsRequest($phone, $message, $scheduleDate);
        $response = $this->client->asForm()->post("2010-04-01/Accounts/{$this->config?->account_sid}/Messages.json", $requestBody);
        $jsonResponse = (object)$response->json();
        $success = ($jsonResponse->status == 'queued' || $jsonResponse->status == 'scheduled' || $jsonResponse->status == 'delivered');
        $message = ($response->status() == 201 || $response->status() == 200) ? ($jsonResponse->status ?? null) : ($jsonResponse->message ?? null);
        return $this->response($response->status(), $success, $message, (array)$jsonResponse);
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
        if (!isset($this->config?->schedule_messaging_service_sid) || is_null($this->config?->schedule_messaging_service_sid) || trim($this->config?->schedule_messaging_service_sid) === '')
            throw new JsonException("schedule_messaging_service_sid is required (Set It In Config)");
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
     * @throws JsonException
     */
    public function getSmsDetails(string $smsID): array
    {
        $response = $this->client->get("2010-04-01/Accounts/{$this->config?->account_sid}/Messages/{$smsID}",);
        $jsonResponse = $this->soapToJson($response->body());
        $success = (($jsonResponse->Message->Status ?? null) == 'queued' || ($jsonResponse->Message->Status ?? null) == 'scheduled' || ($jsonResponse->Message->Status ?? null) == 'delivered');
        $message = ($response->status() == 201 || $response->status() == 200) ? ($jsonResponse->Message->Status ?? null) : ($jsonResponse->RestException->Message ?? null);
        return $this->response($response->status(), $success, $message, (array)$jsonResponse);
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
    public function buildSmsRequest(string $phone, string $message, string $scheduleDate = null): array
    {
        $scheduleData = [];
        $data = [
            'From' => $this->config?->sms_phone_number,
            'To' => $phone,
            'Body' => $message,
        ];
        if ($scheduleDate) {
            $scheduleData = [
                "SendAt" => $scheduleDate,
                "ScheduleType" => "fixed",
                "StatusCallback" => null,
                "MessagingServiceSid" => $this->config?->schedule_messaging_service_sid,
            ];
        }


        return array_merge($data, $scheduleData);
    }

    /**
     * Send Whatsapp message
     *
     * @param string $phone
     * @param string $message
     * @param string|null $scheduleDate
     * @return array
     */
    public function sendWhatsappMessage(string $phone, string $message, string|null $scheduleDate = ''): array
    {
        if (!$this->enableSendSms)
            return $this->response(400, false, "Sme Sender Is Disabled");
        $requestBody = $this->buildWhatsappRequest($phone, $message);
        $response = $this->client->asForm()->post("2010-04-01/Accounts/{$this->config?->account_sid}/Messages.json", $requestBody);
        $jsonResponse = (object)$response->json();
        $success = ($jsonResponse->status == 'queued' || $jsonResponse->status == 'scheduled' || $jsonResponse->status == 'delivered');
        $message = ($response->status() == 201 || $response->status() == 200) ? ($jsonResponse->status ?? null) : ($jsonResponse->message ?? null);
        return $this->response($response->status(), $success, $message, (array)$jsonResponse);
    }

    /**
     * Build Whatsapp Message Request
     *
     * @param string $phone
     * @param string $message
     * @return array
     */
    public function buildWhatsappRequest(string $phone, string $message): array
    {
        return [
            'From' => "whatsapp:{$this->config?->whatsapp_phone_number}",
            'To' => "whatsapp:$phone",
            'Body' => $message,
        ];
    }
}

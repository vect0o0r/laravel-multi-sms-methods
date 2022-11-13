<?php

namespace Vector\LaravelMultiSmsMethods\Methods;

use Carbon\Carbon;
use JsonException;
use Vector\LaravelMultiSmsMethods\Constants\MethodTypes;
use Vector\LaravelMultiSmsMethods\Interfaces\SmsGatewayInterface;

/**
 * Ooredoo class.
 *
 * @author Vector <mo.khaled.yousef@gmail.com>
 */
class Ooredoo extends BaseMethod implements SmsGatewayInterface
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
        $this->driver = 'ooredoo';
        //Set Method Base Url
        $this->base_url = "https://messaging.ooredoo.qa";
        //Set Method Supported Type
        $this->supportedTypes = [MethodTypes::SMS->value];
        //Set Config Required Keys
        $this->requiredConfigKeys = ['username', 'password', 'sender_id', 'customer_id'];
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
        if (!$this->enableSendSms) {
            return $this->response(400, false, "Sme Sender Is Disabled");
        }
        $response = $this->client->get('bms/soap/Messenger.asmx/HTTP_SendSms', $this->buildSmsRequest($phone, $message, $scheduleDate));
        $jsonResponse = $this->soapToJson($response->body());
        $success = $jsonResponse->Result === "OK";
        return $this->response($response->status(), $success, $jsonResponse->Result, (array)$jsonResponse);
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
     * @throws JsonException
     */
    public function getSmsDetails(string $smsID): array
    {
        $requestBody = ['userName' => $this->config?->username, 'userPassword' => $this->config?->password, 'customerID' => $this->config?->customer_id, "detailed" => 'true', 'transactionID' => $smsID];
        $response = $this->client->get('bms/soap/Messenger.asmx/HTTP_GetSmsStatus', $requestBody);
        $jsonResponse = $this->soapToJson($response->body());
        $success = $jsonResponse->Result === "OK";
        return $this->response($response->status(), $success, $jsonResponse->Result, (array)$jsonResponse);
    }

    /**
     * Get Account Available Balance.
     *
     * @return array
     * @throws JsonException
     */
    public function getBalance(): array
    {
        $response = $this->client->get('bms/soap/Messenger.asmx/HTTP_Authenticate2', ['userName' => $this->config?->username, 'userPassword' => $this->config?->password, 'customerID' => $this->config?->customer_id]);
        $jsonResponse = $this->soapToJson($response->body());
        $success = $jsonResponse->Result === "OK";
        $message = $jsonResponse->Result;
        return $this->response($response->status(), $success, $message, (array)$jsonResponse);
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
            'userName' => $this->config?->username,
            'userPassword' => $this->config?->password,
            'originator' => $this->config?->sender_id,
            'customerID' => $this->config?->customer_id,
            'messageType' => $this->getMessageLanguage($message),
            'recipientPhone' => $phone,
            'smsText' => $message,
            'defDate' => $scheduleDate,
            'blink' => 'false',
            'flash' => 'false',
            'Private' => 'false',
        ];
    }

    /**
     * Get Message Language
     *
     * @param string $message
     * @return string
     */
    public function getMessageLanguage(string $message): string
    {
        return preg_match('/[\x{0590}-\x{05ff}\x{0600}-\x{06ff}]/u', $message) ? 'ArabicWithArabicNumbers' : 'Latin';
    }
}

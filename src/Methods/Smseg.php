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
class Smseg extends BaseMethod implements SmsGatewayInterface
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
        $this->driver = 'smseg';
        //Set Method Base Url
        $this->base_url = "https://smssmartegypt.com";
        //Set Method Supported Type
        $this->supportedTypes = [MethodTypes::SMS->value, MethodTypes::OTP->value];
        //Set Config Required Keys
        $this->requiredConfigKeys = ['username', 'password', 'sender_id'];
        //Calling Parent Constructor
        parent::__construct();
    }


    /**
     * Send sms message.
     *
     * @param string $phone
     * @param string $message
     * @param string|null $scheduleDate
     * @return array
     */
    public function send(string $phone, string $message, string|null $scheduleDate = null): array
    {
        if (!$this->enableSendSms)
            return $this->response(400, false, "Sme Sender Is Disabled");
        $response = $this->client->post($this->buildRequestUrl(), $this->buildSmsRequest($phone, $message, $scheduleDate));
        $jsonResponse = $response->object();
        dd($jsonResponse);
        $responseCode = $jsonResponse->code ?? $jsonResponse?->Code ?? null;
        $success = in_array($responseCode, ['1901', '4901'], true);
        $message = $this->getStatusMessage($responseCode);
        return $this->response($response->status(), $success, $message, (array)$jsonResponse);
    }

    /**
     * Get Sms Status
     *
     * @param string $smsID
     * @return array
     */
    public function getSmsDetails(string $smsID): array
    {
        return $this->response(404, false, "This Methods Is Not Supported In {$this->driver} Yet", []);
    }

    /**
     * Handle Single sms message.
     *
     * @param string $phone
     * @param string $message
     * @return array
     */
    public function sendSms(string $phone, string $message): array
    {
        return $this->send($phone, $message);
    }

    /**
     * Handle Single OTP message.
     *
     * @param string $phone
     * @param int $otp
     * @return array
     * @throws JsonException
     */
    public function sendOtp(string $phone, int $otp): array
    {
        $this->requiredConfigKeys = array_merge($this->requiredConfigKeys, ['template_token']);
        $this->messageType = MethodTypes::OTP->value;
        $this->validateRequiredKeys();
        return $this->send($phone, $otp);
    }

    /**
     * Handle Single sms message.
     *
     * @param string $phone
     * @param string $message
     * @param string $scheduleDate
     * @return array
     */
    public function sendScheduleSms(string $phone, string $message, string $scheduleDate): array
    {
        return $this->send($phone, $message, $scheduleDate);
    }

    /**
     * Handle Multi sms message.
     *
     * @param array $phonesArray
     * @param string $message
     * @return array
     */
    public function sendMultiSms(array $phonesArray, string $message): array
    {
        $phones = implode(',', $phonesArray);
        return $this->send($phones, $message);
    }

    /**
     * Build OTP Request Body
     *
     * @param string $phone
     * @param string $message
     * @param string|null $scheduleDate
     * @return array
     */
    public function buildSmsRequest(string $phone, string $message, string|null $scheduleDate = null): array
    {
        $requestData = [
            'username' => $this->config?->username,
            'password' => $this->config?->password,
        ];
        if ($this->messageType === MethodTypes::SMS->value) {
            $requestData += [
                'sendername' => $this->config?->sender_id,
                'mobiles' => $phone,
                'message' => $message,
            ];
        } elseif ($this->messageType === MethodTypes::OTP->value) {
            $requestData += [
                'mobile' => $phone,
                'lang' => 'en',
            ];
        }
        return $requestData;
    }

    /**
     * Build Request Url
     *
     * @return string
     */
    public function buildRequestUrl(): string
    {
        $url = '';
        if ($this->messageType === MethodTypes::SMS->value) {
            $url = 'sms/api';
        } elseif ($this->messageType === MethodTypes::OTP->value) {
            $url = 'sms/api/otp-send';
        }
        return $url;
    }

    /**
     * Get Message Language
     *
     * @param string $message
     * @return string
     */
    public function getMessageLanguage(string $message): string
    {
        return preg_match('/[\x{0590}-\x{05ff}\x{0600}-\x{06ff}]/u', $message) ? 2 : 1;
    }

    /**
     * Get Message Language
     *
     * @param string $statusCode
     * @return string
     */
    public function getStatusMessage(string $statusCode): string
    {
        return match ($statusCode) {
            '4901', '1901' => 'Success, Message Submitted Successfully',
            '1902' => 'Invalid Request',
            '4903', '1903' => 'Invalid value in username or password field',
            '4904', '1904' => 'Invalid value in "sender" field',
            '4905', '1905' => 'Invalid value in "mobile" field',
            '4906', '1906' => 'Insufficient Credit',
            '4907', '1907' => 'Server under updating',
            '1908' => 'Invalid Date & Time format in “DelayUntil=” parameter',
            '1909' => 'Invalid Message',
            '1910' => 'Invalid Language',
            '1911' => 'Text is too long',
            '4912', '1912' => 'Invalid Environment',
            '4908' => 'Invalid OTP',
            '4909' => 'Invalid Template Token',
            default => 'SomeThing Went Wrong',
        };
    }
}

<?php

namespace Vector\LaravelMultiSmsMethods\Methods;

use JsonException;
use Vector\LaravelMultiSmsMethods\Constants\MethodTypes;
use Vector\LaravelMultiSmsMethods\Interfaces\SmsGatewayInterface;

/**
 * SmsEg class.
 *
 * @author Vector <mo.khaled.yousef@gmail.com>
 */
class Smseg extends BaseMethod implements SmsGatewayInterface
{

    /**
     * internationalSms.
     *
     * @var bool
     */
    protected bool $internationalSms;

    /**
     * Set Method Driver And Base Url
     *
     * @return void
     * @throws JsonException
     */
    public function __construct()
    {
        $this->internationalSms = false;
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
        if ($this->internationalSms) {
            $response = $this->client->asJson()->get($this->buildRequestUrl(), $this->buildSmsRequest($phone, $message, $scheduleDate));
        } else {
            $response = $this->client->asJson()->post($this->buildRequestUrl(), $this->buildSmsRequest($phone, $message, $scheduleDate));
        }
        $jsonResponse = is_array($response->object()) ? $response->object()[0] : $response->object();
        $success = $jsonResponse->type === 'success';
        $message = $success ? $jsonResponse->type : $this->getStatusMessage($jsonResponse?->error->number) ?? $jsonResponse->type;
        return $this->response($response->status(), $success, $message, $response->json());
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
        $this->internationalSms = false;
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
        $this->internationalSms = false;
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
     */
    public function sendScheduleSms(string $phone, string $message, string $scheduleDate): array
    {
        return $this->response(404, false, "This Methods Is Not Supported In {$this->driver} Yet", []);

    }

    /**
     * Used To Send OTP message.
     *
     * @param string $phone
     * @param int|null $otp
     * @return array
     * @throws JsonException
     */
    public function sendOtp(string $phone, int $otp = null): array
    {
        $this->messageType = MethodTypes::OTP->value;
        return $this->send($phone, $otp);
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
        $details = ['username' => $this->config?->username, 'password' => $this->config?->password, 'mobile' => $phone, 'otp' => $otp];
        $response = $this->client->post('sms/api/otp-check', $details);
        $jsonResponse = $response->object();
        $success = $jsonResponse->type === "success";
        $message = $success ? $jsonResponse->msg : $jsonResponse->error->msg;
        return $this->response($response->status(), $success, $message, $response->json());
    }

    /**
     * Send Single International Sms message.
     *
     * @param string $phone
     * @param string $message
     * @return array
     * @throws JsonException
     */
    public function sendSingleInternationalSms(string $phone, string $message): array
    {
        $this->internationalSms = true;
        return $this->send($phone, $message);
    }

    /**
     * Send Multi International Sms Message.
     *
     * @param array $phonesArray
     * @param string $message
     * @return array
     * @throws JsonException
     */
    public function sendMultiInternationalSms(array $phonesArray, string $message): array
    {
        $this->internationalSms = true;
        $phones = implode(',', $phonesArray);
        return $this->send($phones, $message);
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
        $response = $this->client->get('sms/api/getBalance', ['username' => $this->config?->username, 'password' => $this->config?->password,]);
        $jsonResponse = $response->object();
        $success = $jsonResponse->type === "success";
        $message = $jsonResponse->message;
        return $this->response($response->status(), $success, $message, $response->json());
    }

    /**
     * Build Sms Request Body
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
                'sender' => $this->config?->sender_id,
                'mobile' => $phone,
                // 'lang' => 'en',
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
            if ($this->internationalSms) {
                $url = 'sms/api/InterAPI';
            } else {
                $url = 'sms/api/json';
            }
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
            '100' => 'Other errors',
            '101' => 'Please send username and password in request!',
            '201' => 'Not found username or wrong password !',
            '202' => 'This account is blocked !',
            '300' => 'Not enough balance',
            '301' => 'Unapproved Sender ID',
            default => 'SomeThing Went Wrong',
        };
    }
}

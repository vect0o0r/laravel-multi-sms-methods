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
class Victorylink extends BaseMethod implements SmsGatewayInterface
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
        $this->driver = 'victorylink';
        //Set Method Base Url
        $this->base_url = "https://smsvas.vlserv.com";
        //Set Method Supported Type
        $this->supportedTypes = [MethodTypes::SMS->value];
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
     * @throws JsonException
     */
    public function send(string $phone, string $message, string|null $scheduleDate = null): array
    {
        if (!$this->enableSendSms)
            return $this->response(400, false, "Sme Sender Is Disabled");
        $response = $this->client->get('KannelSending/service.asmx/SendSMS', $this->buildSmsRequest($phone, $message, $scheduleDate));
        $arrayResponse = $this->soapToArray($response->body());
        $success = ($arrayResponse[0] === '0') || ($arrayResponse[0] === '-10');
        $message = $this->getStatusMessage($arrayResponse[0]);
        return $this->response($response->status(), $success, $message, (array)$arrayResponse);
    }

    /**
     * Get Account Balance.
     *
     * @return array
     * @throws JsonException
     */
    public function getBalance(): array
    {
        $response = $this->client->get('KannelSending/service.asmx/CheckCredit', ['UserName' => $this->config?->username, 'Password' => $this->config?->password]);
        $arrayResponse = $this->soapToArray($response->body());
        $success = ($arrayResponse[0] === '0');
        $balance = $arrayResponse['0'];
        $message = $success ? "Success" : "SomeThing Went Wrong";
        return $this->response($response->status(), $success, $message, ['balance' => (int)$balance]);
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
     * @throws JsonException
     */
    public function sendSms(string $phone, string $message): array
    {
        return $this->send($phone, $message);
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
        return $this->response(404, false, "This Methods Is Not Supported In {$this->driver} Yet", []);
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
        return $this->response(404, false, "This Methods Is Not Supported In {$this->driver} Yet", []);
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
        return [
            'UserName' => $this->config?->username,
            'Password' => $this->config?->password,
            'SMSSender' => $this->config?->sender_id,
            'SMSLang' => $this->getMessageLanguage($message),
            'SMSReceiver' => $phone,
            'SMSText' => $message,
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
        return preg_match('/[\x{0590}-\x{05ff}\x{0600}-\x{06ff}]/u', $message) ? 'A' : 'E';
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
            '0' => 'The SMS is sent successfully.',
            '-1' => 'User is not subscribed.',
            '-5' => 'out of credit.',
            '-10' => 'Queued Message, no need to send it again.',
            '-11' => 'Invalid language.',
            '-12' => 'SMS is empty.',
            '-13' => 'Invalid fake sender exceeded 12 chars or empty.',
            '-25' => 'Sending rate greater than receiving rate (only for send/receive accounts).',
            '-100' => 'other error.',
            default => 'SomeThing Went Wrong',
        };
    }
}

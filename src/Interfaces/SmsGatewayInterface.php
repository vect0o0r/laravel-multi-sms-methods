<?php

namespace Vector\LaravelMultiSmsMethods\Interfaces;

use Exception;
use JsonException;

/**
 * SmsGatewayInterface interface.
 *
 * @author Vector <mo.khaled.yousef@gmail.com>
 */
interface SmsGatewayInterface
{
    /**
     * Send sms message.
     *
     * @param string $phones
     * @param string $message
     * @param string|null $scheduleDate
     * @return array
     * @throws JsonException
     */
    public function send(string $phones, string $message, string $scheduleDate = null): array;

    /**
     * Handle Single sms message.
     *
     * @param string $phone
     * @param string $message
     * @param string|null $scheduleDate
     * @return array
     * @throws JsonException
     */
    public function sendSms(string $phone, string $message, string $scheduleDate = null): array;

    /**
     * Handle Multi sms message.
     *
     * @param array $phonesArray
     * @param string $message
     * @param string|null $scheduleDate
     * @return array
     * @throws JsonException
     */
    public function sendMultiSms(array $phonesArray, string $message, string $scheduleDate = null): array;

    /**
     * Build Sms Request Body
     *
     * @param string $phone
     * @param string $message
     * @param string|null $scheduleDate
     * @return array
     */
    public function buildSmsRequest(string $phone, string $message, string $scheduleDate = null): array;


}

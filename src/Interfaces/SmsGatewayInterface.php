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
     * Used To Start Calling Provider Api
     * @param string $phone
     * @param string $message
     * @param string|null $scheduleDate
     * @return array
     * @throws JsonException
     */
    public function send(string $phone, string $message, string|null $scheduleDate): array;

    /**
     * Send Single Sms message.
     *
     * @param string $phone
     * @param string $message
     * @return array
     * @throws JsonException
     */
    public function sendSms(string $phone, string $message): array;

    /**
     * Send Multi Sms message.
     *
     * @param array $phonesArray
     * @param string $message
     * @return array
     * @throws JsonException
     */
    public function sendMultiSms(array $phonesArray, string $message): array;

    /**
     * Send Scheduled Sms message.
     *
     * @param string $phone
     * @param string $message
     * @param string $scheduleDate
     * @return array
     * @throws JsonException
     */
    public function sendScheduleSms(string $phone, string $message, string $scheduleDate): array;

    /**
     * Used To Send OTP message.
     *
     * @param string $phone
     * @param int|null $otp
     * @return array
     * @throws JsonException
     */
    public function sendOtp(string $phone, int $otp = null): array;

    /**
     *  Check Sent OTP message.
     *
     * @param string $phone
     * @param int $otp
     * @return array
     */
    public function checkOtp(string $phone, int $otp): array;

    /**
     * Get Sms Details
     *
     * @param string $smsID
     * @return array
     */
    public function getSmsDetails(string $smsID): array;

    /**
     * Get Account Available Balance.
     *
     * @return array
     */
    public function getBalance(): array;

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

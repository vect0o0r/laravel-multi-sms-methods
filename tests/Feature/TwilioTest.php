<?php

namespace Vector\Tests\Feature;

use Orchestra\Testbench\TestCase as Orchestra;
use Vector\LaravelMultiSmsMethods\Facade\Sms;
use Vector\LaravelMultiSmsMethods\Methods\Managers\SmsManager;
use Vector\Tests\TestCase;

class TwilioTest extends TestCase
{
    protected string $driver = "twilio";
    /**
     * Twilio Tests.
     *
     * @author Vector <mo.khaled.yousef@gmail.com>
     */

    /** @test * */
    public function test_user_can_send_single_sms(): void
    {
        $sms = new SmsManager;
        $sms = (object)$sms->driver($this->driver)->sendSms($this->phone, $this->message);
        $this->assertTrue($sms->success);
        $this->assertEquals(201, $sms->code);
    }

    /** @test * */
    public function test_user_can_send_multi_sms(): void
    {
        $sms = new SmsManager;
        $sms = (object)$sms->driver($this->driver)->sendMultiSms([$this->phone], $this->message);
        $this->assertTrue($sms->success);
        $this->assertEquals(201, $sms->code);
    }

    /** @test * */
    public function test_user_can_send_schedule_sms(): void
    {
        $sms = new SmsManager;
        $sms = (object)$sms->driver($this->driver)->sendScheduleSms($this->phone, $this->message, "2022-10-29T20:36:27Z");
        $this->assertTrue($sms->success);
        $this->assertEquals("scheduled", $sms->message);
        $this->assertEquals(201, $sms->code);
    }

    /** @test * */
    public function test_user_can_send_whatsapp_message(): void
    {
        $sms = new SmsManager;
        $sms = (object)$sms->driver($this->driver)->sendWhatsappMessage($this->phone, $this->message);
        $this->assertTrue($sms->success);
        $this->assertEquals(201, $sms->code);
    }

    /** @test * */
    public function test_user_can_get_sms_details(): void
    {
        $messageID = 'SM0675b053981a9377d41e3e34054d75a1';
        $sms = new SmsManager;
        $sms = (object)$sms->driver($this->driver)->getSmsDetails($messageID);
        $this->assertTrue($sms->success);
        $this->assertEquals(200, $sms->code);
    }

}

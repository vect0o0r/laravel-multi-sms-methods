<?php

namespace Vector\Tests\Feature;

use Vector\LaravelMultiSmsMethods\Managers\SmsManager;
use Vector\Tests\TestCase;

class OoredooTest extends TestCase
{
    protected string $driver = "ooredoo";
    /**
     * Ooredoo Tests.
     *
     * @author Vector <mo.khaled.yousef@gmail.com>
     */

    /** @test * */
    public function test_user_can_send_single_sms(): void
    {
        $sms = new SmsManager;
        $sms = (object)$sms->driver($this->driver)->sendSms($this->phone, $this->message);
        $this->assertTrue($sms->success);
        $this->assertEquals(200, $sms->code);
    }

    /** @test * */
    public function test_user_can_send_multi_sms(): void
    {
        $sms = new SmsManager;
        $sms = (object)$sms->driver($this->driver)->sendMultiSms([$this->phone, $this->phone2], $this->message);
        $this->assertTrue($sms->success);
        $this->assertEquals(200, $sms->code);
    }

    /** @test * */
    public function test_user_can_send_schedule_sms(): void
    {
        $sms = new SmsManager;
        $sms = (object)$sms->driver($this->driver)->sendScheduleSms($this->phone, $this->message, '2022-11-27 07:21:03');
        $this->assertTrue($sms->success);
        $this->assertEquals(200, $sms->code);
    }

    /** @test * */
    public function test_user_can_get_sms_details(): void
    {
        $messageID = 'a0913315-f25f-43ce-a532-021a14f69f4b';
        $sms = new SmsManager;
        $sms = (object)$sms->driver($this->driver)->getSmsDetails($messageID);
        $this->assertTrue($sms->success);
        $this->assertEquals(200, $sms->code);
    }

    /** @test * */
    public function test_user_can_send_get_account_balance(): void
    {
        $sms = new SmsManager;
        $sms = (object)$sms->driver($this->driver)->getBalance();
        $this->assertTrue($sms->success);
        $this->assertEquals(200, $sms->code);
    }


}

<?php

namespace Vector\Tests\Feature;

use Vector\LaravelMultiSmsMethods\Managers\SmsManager;
use Vector\Tests\TestCase;

class SmsBoxTest extends TestCase
{
    protected string $driver = "smsbox";
    /**
     * SMS BOX Tests.
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
    public function test_user_can_send_schedule_sms(): void
    {
        $sms = new SmsManager;
        $sms = (object)$sms->driver($this->driver)->sendScheduleSms($this->phone, $this->message, '2022-11-15 20:21:03');
        $this->assertTrue($sms->success);
        $this->assertEquals(200, $sms->code);
    }

    /** @test * */
    public function test_user_can_send_multi_sms(): void
    {
        $sms = new SmsManager;
        $sms = (object)$sms->driver($this->driver)->sendMultiSms([$this->phone,$this->phone], $this->message);
        $this->assertTrue($sms->success);
        $this->assertEquals(200, $sms->code);
    }
}

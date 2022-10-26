<?php

namespace Vector\Tests\Feature;

use Orchestra\Testbench\TestCase as Orchestra;
use Vector\LaravelMultiSmsMethods\Facade\Sms;
use Vector\LaravelMultiSmsMethods\Methods\Managers\SmsManager;
use Vector\Tests\TestCase;

class SmsBoxTest extends TestCase
{
    /**
     * SMS BOX Tests.
     *
     * @author Vector <mo.khaled.yousef@gmail.com>
     */

    /** @test * */
    public function test_user_can_send_single_sms(): void
    {
        $sms = new SmsManager;
        $sms = $sms->driver('smsbox')->sendSms('+96566991971', 'test Sms From Vector 2');
        $this->assertTrue($sms->success);
        $this->assertEquals(200,$sms->code);
    }

    /** @test * */
    public function test_user_can_send_multi_sms(): void
    {
        $sms = new SmsManager;
        $sms = (object)$sms->driver('smsbox')->sendMultiSms(['+96566991971', '+96566991971'], 'test Sms From Vector 2');
        $this->assertTrue($sms->success);
        $this->assertEquals(200,$sms->code);
    }
}

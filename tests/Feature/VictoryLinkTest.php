<?php

namespace Vector\Tests\Feature;

use Vector\LaravelMultiSmsMethods\Managers\SmsManager;
use Vector\Tests\TestCase;

class VictoryLinkTest extends TestCase
{
    protected string $driver = "victorylink";
    /**
     * SMS Misr Tests.
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
    public function test_user_can_send_get_account_balance(): void
    {
        $sms = new SmsManager;
        $sms = (object)$sms->driver($this->driver)->getBalance();
        $this->assertTrue($sms->success);
        $this->assertIsInt(200, $sms->data['balance']);
        $this->assertEquals(200, $sms->code);
    }


}

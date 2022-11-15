<?php

namespace Vector\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;
use Vector\LaravelMultiSmsMethods\Providers\SmsServiceProvider;

/**
 * TestCase class.
 *
 * @author Vector <mo.khaled.yousef@gmail.com>
 */
abstract class TestCase extends Orchestra
{

    protected string $phone = "+2001028955489";
    protected string $phone2 = "+2001028955489";
    protected string $message = "Test Message Form Unit Testing";
    protected string $otp = "123456";

    /**
     * Get package service providers.
     *
     * @param Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            SmsServiceProvider::class,
        ];
    }
}

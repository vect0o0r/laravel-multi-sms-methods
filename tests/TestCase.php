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
    /**
     * Get package service providers.
     *
     * @param  Application  $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            SmsServiceProvider::class,
        ];
    }
}

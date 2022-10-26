<?php

namespace Vector\LaravelMultiSmsMethods\Providers;

use Illuminate\Support\ServiceProvider;
use Vector\LaravelMultiSmsMethods\Methods\Managers\SmsManager;

/**
 * SmsServiceProvider class.
 *
 * @author Vector <mo.khaled.yousef@gmail.com>
 */
class SmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/../../config/sms-methods.php' => config_path('sms-methods.php')], 'laravel-multi-sms-methods');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/sms-methods.php', 'sms-methods');
        $this->app->bind('sms', function () {
            return new SmsManager();
        });
    }
}

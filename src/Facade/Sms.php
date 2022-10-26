<?php

namespace Vector\LaravelMultiSmsMethods\Facade;

use Illuminate\Support\Facades\Facade;

class Sms extends Facade
{
    /**
     * Get the registered name of the component.
     * @author Vector <mo.khaled.yousef@gmail.com>
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'sms';
    }
}

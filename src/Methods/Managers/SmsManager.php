<?php

namespace Vector\LaravelMultiSmsMethods\Methods\Managers;

use Exception;
use RuntimeException;
use Vector\LaravelMultiSmsMethods\Constants\Methods;


/**
 * SMS Manager class.
 *
 * @author Vector <mo.khaled.yousef@gmail.com>
 *
 */
class SmsManager
{
    /**
     * Return Passed Sms Driver Class
     *
     * @param $method
     * @return mixed
     */
    public function driver($method): mixed
    {
        $this->checkMethodAvailability($method);
        $class = $this->getMethodDriver($method);
        return new $class;
    }

    /**
     * Check Method Availability
     *
     * @param $method
     * @return void
     */
    public function checkMethodAvailability($method): void
    {
        if (!in_array($method, Methods::getValues(), true))
            throw new RuntimeException("Driver {$method} Is Not Supported Yet");
    }

    /**
     * Check Method Driver Class
     *
     * @param $method
     * @return string
     */
    public function getMethodDriver($method): string
    {
        $methodsClassName = $this->cleanMethodName($method);
        $className = "Vector\LaravelMultiSmsMethods\Methods\\" . $methodsClassName;
        if (!class_exists($className))
            throw new RuntimeException("Class {$methodsClassName} does not exist");
        return $className;
    }


    /**
     * Clean Method Name and upper case first letter
     *
     * @param $string
     * @return array|string
     */
    public function cleanMethodName($string): array|string
    {
        return str_replace(' ', '', ucwords(preg_replace("/[\s-]+/", " ", $string)));
    }
}

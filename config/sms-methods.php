<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sms Available Methods
    |--------------------------------------------------------------------------
    / @author Vector <mo.khaled.yousef@gmail.com>
    | Here are each of the Available sms Methods
    |
    */

    "enable_send_sms" => env('ENABLE_SEND_SMS', false), //Enable or Disable Sms
    'methods' => [
        /*
           |--------------------------------------------------------------------------
           | Sms Box Connection
           |--------------------------------------------------------------------------
           |
           | Here are credentials for sms Box gateway.
           |
           */
        'smsbox' => [
            'username' => env('SMSBOX_USERNAME'),
            'password' => env('SMSBOX_PASSWORD'),
            'gateway_id' => env('SMSBOX_GATWAY_ID'),
            'sender_id' => env('SMS_SENDER_ID'),
        ],
    ]

];
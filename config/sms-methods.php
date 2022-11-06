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
    "enable_send_sms" => env('ENABLE_SEND_SMS', true), //Enable or Disable Sms
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
            'gateway_id' => env('SMSBOX_GATEWAY_ID'),
            'sender_id' => env('SMSBOX_SENDER_ID'),
        ],
        'twilio' => [
            'account_sid' => env('TWILIO_ACCOUNT_SID'),
            'auth_token' => env('TWILIO_AUTH_TOKEN'),
            'sms_phone_number' => env('TWILIO_SMS_PHONE_NUMBER'),
            'schedule_messaging_service_sid' => env('TWILIO_SCHEDULE_MESSAGING_SERVICE_SID'),
            'whatsapp_phone_number' => env('TWILIO_WHATSAPP_PHONE_NUMBER'),
        ],
    ]

];

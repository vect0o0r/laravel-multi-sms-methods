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
    "enable_send_sms" => env('ENABLE_SEND_SMS', true), //Enable or Disable Sending Sms
    'methods' => [
        /*
       |--------------------------------------------------------------------------
       | Sms Box Connection
       |--------------------------------------------------------------------------
       |
       | Here are credentials for Sms Box gateway.
       |
       */
        'smsbox' => [
            'username' => env('SMSBOX_USERNAME'),
            'password' => env('SMSBOX_PASSWORD'),
            'gateway_id' => env('SMSBOX_GATEWAY_ID'),
            'sender_id' => env('SMSBOX_SENDER_ID'),
        ],
        /*
        |--------------------------------------------------------------------------
        | Sms Misr Connection
        |--------------------------------------------------------------------------
        |
        | Here are credentials for Sms Misr gateway.
        |
        */
        'smsmisr' => [
            'username' => env('SMSMISR_USERNAME'),
            'password' => env('SMSMISR_PASSWORD'),
            'sender_token' => env('SMSMISR_SENDER_TOKEN'),
            'template_token' => env('SMSMISR_TEMPLATE_TOKEN'),
            'sandbox' => env('SMSMISR_SANDBOX'),
        ],
        /*
         |--------------------------------------------------------------------------
         | Sms EG Connection
         |--------------------------------------------------------------------------
         |
         | Here are credentials for Sms EG gateway.
         |
         */
        'smseg' => [
            'username' => env('SMSEG_USERNAME'),
            'password' => env('SMSEG_PASSWORD'),
            'sender_id' => env('SMSEG_SENDER_ID'),
        ],
        /*
       |--------------------------------------------------------------------------
       | Ooredoo Connection
       |--------------------------------------------------------------------------
       |
       | Here are credentials for Ooredoo gateway.
       |
       */
        'ooredoo' => [
            'username' => env('OOREDOO_USERNAME'),
            'password' => env('OOREDOO_PASSWORD'),
            'sender_id' => env('OOREDOO_SENDER_ID'),
            'customer_id' => env('OOREDOO_CUSTOMER_ID'),
        ],
        /*
      |--------------------------------------------------------------------------
      | Victory Link Connection
      |--------------------------------------------------------------------------
      |
      | Here are credentials for Victory Link gateway.
      |
      */
        'victorylink' => [
            'username' => env('VICTORYLINK_USERNAME'),
            'password' => env('VICTORYLINK_PASSWORD'),
            'sender_id' => env('VICTORYLINK_SENDER_ID'),
        ],
        /*
        |--------------------------------------------------------------------------
        | Twilio Connection
        |--------------------------------------------------------------------------
        |
        | Here are credentials for Twilio gateway.
        |
        */
        'twilio' => [
            'account_sid' => env('TWILIO_ACCOUNT_SID'),
            'auth_token' => env('TWILIO_AUTH_TOKEN'),
            'sms_phone_number' => env('TWILIO_SMS_PHONE_NUMBER'),
            'schedule_messaging_service_sid' => env('TWILIO_SCHEDULE_MESSAGING_SERVICE_SID'),
            'whatsapp_phone_number' => env('TWILIO_WHATSAPP_PHONE_NUMBER'),
        ],
    ]

];

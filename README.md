# Laravel Multi SMS Methods

Laravel Package For SMS Methods Multi Methods Usage.

A package dedicated to complete control and work with more than one gateway smoothly and simultaneously

## 🚀 About Me

I'm a Full-stack Developer with more than 8 years of a unique experience, I'm a self-learner and specialize in applying
the best practices to design and implement scalable, concurrent, flexible, and robust software solutions, with a healthy
focus on the expected business outcomes, Always I seek to gain new skills and grow up my knowledge.

## Supported Methods

- [x]  [SMS BOX](https://www.smsbox.com)
- [x]  [TWILIO](https://www.twilio.com)
- [x] [SMS EG](https://www.smseg.com)
- [x] [SMS Misr](https://www.sms.com.eg)
- [x] [Victory Link](http://www.victorylink.com)
- [x] [Ooredoo](https://www.ooredoo.com)
- [ ]  Others On The Way...

## Features

- Send Single SMS
- Send Multi SMS
- Send OTP SMS
- Check Sent OTP
- Send Scheduled Sms
- Send WhatsApp Message
- Get Sent SMS Details
- Get Account Balance

## Installation

You Can Install Via Composer

```bash
composer require vectoor/laravel-multi-sms-methods
```

## Publish

You Should Publish Config File To Set Method Credentials

```bash
php artisan vendor:publish --provider="Vector\LaravelMultiSmsMethods\Providers\SmsServiceProvider"
```

## Config

Example Of Config File

```
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

```

## Facade Usage

Use Sms Facade

```bash
use Vector\LaravelMultiSmsMethods\Facade\Sms;
```

# Usage/Examples

## Supported Gateways

| Gateway        | Key            |
|:---------------|:---------------|
| `SMS BOX`      | `smsbox`       |
| `TWILIO`       | `twilio `      |
| `SMS EG`       | `smseg`        |
| `SMS Misr`     | `smsmisr`      |
| `Victory Link` | `victorylink`  |
| `Ooredoo `     | `ooredoo`      |

## Supported Methods

| Available Methods     | smsbox            | smsmisr           | smseg              | twilio            | ooredoo           | victorylink       |
|:----------------------|:------------------|:------------------|:-------------------|:------------------|:------------------|:------------------|
| `sendSms`             | `supported`       | `supported`       | `supported`        | `supported`       | `supported`       | `supported`       |
| `sendMultiSms`        | `supported`       | `supported`       | `supported`        | `supported`       | `supported`       | **Not Supported** |
| `sendScheduleSms`     | `supported`       | `supported`       | **Not Supported**  | `supported`       | `supported`       | **Not Supported** |
| `sendWhatsappMessage` | **Not Supported** | **Not Supported** | **Not Supported**  | `supported`       | **Not Supported** | **Not Supported** |
| `sendOtp`             | **Not Supported** | `supported`       | `supported`        | **Not Supported** | **Not Supported** | **Not Supported** |
| `checkOtp`            | **Not Supported** | **Not Supported** | `supported`        | **Not Supported** | **Not Supported** | **Not Supported** |
| `getSmsDetails`       | **Not Supported** | **Not Supported** | **Not Supported**  | `supported`       | `supported`       | **Not Supported** |
| `getBalance`          | **Not Supported** | **Not Supported** | `supported`        | **Not Supported** | `supported`       | `supported`       |

## Request

#### To Send Single Sms

##### Example

```bash
  Sms::driver($gateway)->sendSms($mobileNumber,$message);
```

| Variable        | Type      | Mandatory | Description        |
|:----------------|:----------|:----------|:-------------------|
| `$gateway`      | `string`  | `Yes`     | `Geteway Key`      |
| `$mobileNumber` | `string`  | `Yes`     | `Mobile Number`    |
| `$message `     | `string`  | `Yes`     | `Message Content ` |

#### To Send Multi Sms

##### Example

```bash
 Sms::driver($gateway)->sendMultiSms($mobileNumbers,$message);
```

| Variable         | Type       | Mandatory | Description         |
|:-----------------|:-----------|:----------|:--------------------|
| `$gateway`       | `string`   | `Yes`     | `Geteway Key`       |
| `$mobileNumbers` | `Array`    | `Yes`     | `Mobile Numbers`    |
| `$message `      | `string`   | `Yes`     | `Message Content `  |

#### To Send Schedule Sms

##### Example

```bash
  Sms::driver($gateway)->sendScheduleSms($mobileNumber,$message,$scheduleDate);
```

| Variable        | Type     | Mandatory | Description                      |
|:----------------|:---------|:----------|:---------------------------------|
| `$gateway`      | `string` | `Yes`     | `Geteway Key`                    |
| `$mobileNumber` | `string` | `Yes`     | `Mobile Number`                  |
| `$message `     | `string` | `Yes`     | `Message Content `               |
| `$scheduleDate` | `string` | `Yes`     | `dateTime of schedule Message`   |

#### To Send OTP Sms

##### Example

```bash
  Sms::driver($gateway)->sendOtp($mobileNumber, $otp)
```

| Variable        | Type     | Mandatory                       | Description     |
|:----------------|:---------|:--------------------------------|:----------------|
| `$gateway`      | `string` | `Yes`                           | `Geteway Key`   |
| `$mobileNumber` | `string` | `Yes`                           | `Mobile Number` |
| `$otp`          | `string` | `no in some methods like smsEg` | `OTP CODE `     |

#### To Check OTP Sms

##### Example

```bash
  Sms::driver($gateway)->checkOtp($mobileNumber, $otp)
```

| Variable        | Type     | Mandatory | Description     |
|:----------------|:---------|:----------|:----------------|
| `$gateway`      | `string` | `Yes`     | `Geteway Key`   |
| `$mobileNumber` | `string` | `Yes`     | `Mobile Number` |
| `$otp`          | `string` | `Yes`     | `OTP CODE `     |

#### To Send Whatsapp Message

##### Example

```bash
  Sms::driver($gateway)->sendWhatsappMessage($mobileNumber,$message);
```

| Variable        | Type     | Mandatory | Description                    |
|:----------------|:---------|:----------|:-------------------------------|
| `$gateway`      | `string` | `Yes`     | `Geteway Key`                  |
| `$mobileNumber` | `string` | `Yes`     | `Whatsapp Mobile Number`       |
| `$message `     | `string` | `Yes`     | `Message Content `             |

#### To Get Sms Information

##### Example

```bash
  Sms::driver($gateway)->getSmsDetails($messageID);
```

| Variable     | Type     | Mandatory | Description          |
|:-------------|:---------|:----------|:---------------------|
| `$gateway`   | `string` | `Yes`     | `Geteway Key`        |
| `$messageID` | `string` | `Yes`     | `ID Of Sent Message` |

#### To Get Account Available Balance

##### Example

```bash
  Sms::driver($gateway)->getBalance();
```

| Variable     | Type     | Mandatory | Description          |
|:-------------|:---------|:----------|:---------------------|
| `$gateway`   | `string` | `Yes`     | `Geteway Key`        |

## Response

### Example

```bash
  array:4 [
  "code" => 200
  "success" => true
  "message" => "Message Send Successfully"
  "data" => array:5 [
    "Message" => "Message Send Successfully"
    "Result" => "true"
    "NetPoints" => "4995.600"
    "messageId" => "79539c54-XXXX-XXXX-XXXX-2db9244b1969"
    "RejectedNumbers" => []
  ]
]
```

| Variable  | Type        | Description                               |
|:----------|:------------|:------------------------------------------|
| `code`    | `integer`   | `Response Code OF The Sent Api`           |
| `message` | `string`    | `The Response Message From Api`           |
| `success` | `bool`      | `The Response Status (If Success Or Not)` |
| `data`    | `array`     | `The Full Response From Api`              |

## Authors

- [@Vector](https://github.com/vect0o0r)

## 🔗 Links

[![portfolio](https://img.shields.io/badge/my_portfolio-000?style=for-the-badge&logo=ko-fi&logoColor=white)](https://dev-vector.com)
[![linkedin](https://img.shields.io/badge/linkedin-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/mohammed-khaled-yousef/)

## License

The Laravel SMS Gateway package is open-sourced software licensed under
the [MIT license](https://github.com/vect0o0r/laravel-multi-sms-methods/blob/master/LICENSE).

## Support

For support, email mo.khaled.yousef@gmail.com .

Yii2 Twilio
==========
Twilio yii2 wrapper. Enable twilio services in yii2 application.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require dpodium/yii2-twilio "*"
```

or add

```
"dpodium/yii2-twilio": "*"
```

to the require section of your `composer.json` file.

Component Setup
-----
Once the extension is installed, simply modify your application configuration as follows:
```php
return [
    'components' => [
    ...
        'twilio' => [
                   'class' => dpodium\yii2\Twilio\TwilioManager::class,
                   'config' => [
                                   'sid' => API_SID, //from twilio
                                   'token' => API_TOKEN, //from twilio
                               ],
                   //leave blank if not applicable
                   'proxy' => [
                                   'host' => HOST,
                                   'port' => PORT,
                               ],
               ],
        ...
    ],
    ...
];
```

Usage
_____
    Sending message
    Yii::$app->twilio->sendSms(TO, FROM, 'Test Message');

    Make call
    Yii::$app->twilio->call(TO, FROM);

    Lookup phone info
    Yii::$app->twilio->lookup(PHONENO);

    Generating TwiML
    Yii::$app->twilio->generateTwiml();

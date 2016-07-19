<?php

namespace dpodium\yii2\Twilio;

class TwilioManager
{
    public $config = [];
    public $test_mode = true;
    public $proxy = null;

    public function sendSms($to, $from, $text, $client_ref = null) {
        $twillio = Yii::$app->twillio;
        $message = $twillio->getClient()->account->messages->sendMessage(
            '9991231234', // From a valid Twilio number
            '8881231234', // Text this number
            "Hello monkey!"
        );

        print $message->sid;
    }
}
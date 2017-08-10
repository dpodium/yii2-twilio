<?php

namespace dpodium\yii2\Twilio;
use \Twilio\Exceptions\DeserializeException;
use \Twilio\Exceptions\TwilioException;
use \yii\base\InvalidConfigException;
class TwilioManager
{
    public $config = [];
    public $proxy = null;

    private $_client = null;
    private $lookupType = ['carrier', 'caller-name'];
    public function sendSms($to, $from, $text) {
        $this->initClient();
        $message = $this->_client->account->messages->sendMessage(
            $from, // From a valid Twilio number
            $to, // Text this number
            [
                'body' => $text
            ]
        );

        print $message->sid;
    }

    /**
    $countryCode: countryCode ISO2
    $type: Possible values are carrier or caller-name. If not specified, the default is null.
    Carrier information costs $0.005 per phone number looked up.
    Caller Name information costs $0.01 per phone number looked up, and is currently ONLY available in the US.
     */
    public function lookup($phoneNo, $countryCode = null, $type = null) {
        $this->initClient();
        if($type && !in_array($type, $this->lookupType)) {
            throw new Exception('Invalid lookup type.');
        }

        $number = $this->_client->lookups->phoneNumbers($phoneNo)->fetch(['CountryCode'=>$countryCode, 'Type'=>$type]);
        if($number) {
            $response = [
                'countryCode' => $number->countryCode,
                'phoneNumber' => $number->phoneNumber,
                'nationalFormat' => $number->nationalFormat,
                'carrier' => (array)$number->carrier,
                'addOns' => (array)$number->addOns,
                'request'=>$this->_client->lookups->phoneNumbers($phoneNo)->__toString(),
            ];
            return $response;
        } else {
            throw new Exception('API error.');
        }
    }

    public function call($to, $from, $musicUrl)
    {
        $this->initClient();
        $call = $this->_client->account->calls->create(
            $from, // From a valid Twilio number
            $to, // Call this number
            // Read TwiML at this URL when a call connects (hold music)
            //'http://twimlets.com/holdmusic?Bucket=com.twilio.music.ambient'
            $musicUrl
        );
        print $call;
    }

    public function generateTwiml() {
        $response = new \Twilio\Twiml();
        $response->say('Hello');
        $response->play('https://api.twilio.com/cowbell.mp3', array("loop" => 5));
        print $response;
    }


    private function initClient()
    {
        if (!$this->_client) {
            if (!$this->config['sid']) {
                throw new InvalidConfigException('SID is required');
            }
            if (!$this->config['token']) {
                throw new InvalidConfigException('Token is required');
            }
            $this->_client = new \Twilio\Rest\Client($this->config['sid'], $this->config['token']);
        }
    }
}
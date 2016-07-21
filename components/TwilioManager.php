<?php

namespace dpodium\yii2\Twilio;

class TwilioManager
{
    public $config = [];
    public $proxy = null;

    private $_client = null;
    private $_clientCapability = null;
    private $_clientLookup = null;

    public function sendSms($to, $from, $text) {
        $this->initClient('normal');
        $message = $this->_client->account->messages->sendMessage(
            $from, // From a valid Twilio number
            $to, // Text this number
            $text
        );

        print $message->sid;
    }

    public function lookup($phoneNo) {
        $this->initClient('lookup');
        $number = $this->_clientLookup->phone_numbers->get($phoneNo);

        print $number;
    }

    public function call($to, $from, $musicUrl)
    {
        $this->initClient('normal');
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
        $response = new \Services_Twilio_Twiml();
        $response->say('Hello');
        $response->play('https://api.twilio.com/cowbell.mp3', array("loop" => 5));
        print $response;
    }


    private function initClient($type)
    {
        if (!$this->config['sid']) {
            throw new InvalidConfigException('SID is required');
        }
        if (!$this->config['token']) {
            throw new InvalidConfigException('Token is required');
        }
        switch($type) {
            case 'normal':
                if ($this->_client === null) {
                    $client = new \Services_Twilio($this->config['sid'], $this->config['token']);
                    $this->_client = $client;
                }
                break;
            case 'capability':
                if ($this->_clientCapability === null) {
                    $client = new \Services_Twilio_Capability($this->config['sid'], $this->config['token']);
                    $this->_clientCapability = $client;
                }
                break;
            case 'lookup':
                if ($this->_clientLookup === null) {
                    $client = new \Lookups_Services_Twilio($this->config['sid'], $this->config['token']);
                    $this->_clientLookup = $client;
                }
                break;
        }
        return $client;
    }
}
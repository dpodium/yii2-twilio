<?php

namespace dpodium\yii2\Twilio;

class TwilioManager
{
    public $config = [];
    public $test_mode = true;
    public $proxy = null;

    private $_client = null;
    private $_clientCapability = null;

    public function sendSms($to, $from, $text) {
        $this->initClient();
        $message = $this->_client->account->messages->sendMessage(
            $from, // From a valid Twilio number
            $to, // Text this number
            $text
        );

        print $message->sid;
    }

    public function call($to, $from, $musicUrl = null)
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
        $response = new \Services_Twilio_Twiml();
        $response->say('Hello');
        $response->play('https://api.twilio.com/cowbell.mp3', array("loop" => 5));
        print $response;
    }


    private function initClient()
    {
        if (!$this->config['sid']) {
            throw new InvalidConfigException('SID is required');
        }
        if (!$this->config['token']) {
            throw new InvalidConfigException('Token is required');
        }
        if ($this->_client === null) {
            $client = new \Services_Twilio($this->config['sid'], $this->config['token']);
            $this->_client = $client;
        }
        return $this->_client;
    }

    private function initClientCapability()
    {
        if (!$this->config['sid']) {
            throw new InvalidConfigException('SID is required');
        }
        if (!$this->config['token']) {
            throw new InvalidConfigException('Token is required');
        }
        if ($this->_clientCapability === null) {
            $client = new \Services_Twilio_Capability($this->config['sid'], $this->config['token']);
            $this->_clientCapability = $client;
        }
        return $this->_clientCapability;
    }

}
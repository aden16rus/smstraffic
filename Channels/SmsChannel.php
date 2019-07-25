<?php

namespace App\Channels;


use App\Notifications\TrainingStatus;
use GuzzleHttp\Client;

class SmsChannel
{

    protected $httpClient;

    protected $url;

    protected $authLogin;

    protected $authPassword;

    /**
     * SmsChannel constructor.
     * @param $httpClient
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->url = config('auth.sms_url');
        $this->authLogin = config('auth.sms_login');
        $this->authPassword = config('auth.sms_password');
    }


    public function send($notifiable, $notification)
    {
        $messageData = $notification->toSms($notifiable);
        $phone = str_replace([')','(','-',' ','+'], '', $messageData['recipient_phone']);

        $request = $this->constructRequest($phone, $messageData['message']);
        if ($phone) {
            return $response = $this->httpClient->request('GET', $request);
        }
        return false;
    }

    private function constructRequest($phone, $message)
    {
        return $this->url. 'login=' . $this->authLogin . '&password=' . $this->authPassword
            . '&phones=' . $phone
            . '&rus=5&message=' . $message;
    }
}

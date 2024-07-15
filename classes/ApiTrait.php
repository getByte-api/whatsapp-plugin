<?php

namespace GetByte\Whatsapp\Classes;


use GetByte\Whatsapp\Models\Account;
use GuzzleHttp\Client;


trait ApiTrait
{
//    protected static $base_url = 'http://getbyte.local/api/v1/';

    protected static $base_url = 'https://gateway.getbyte.co/api/v1/';

    protected static function http(Account $account) : Client
    {

        $client = new Client([
            'base_uri' => self::$base_url,
            'headers' => [
                'Authorization' => 'Bearer ' . $account->secret_key,
                'Content-Type' => 'application/json'
            ],
        ]);

        return $client;
    }
}

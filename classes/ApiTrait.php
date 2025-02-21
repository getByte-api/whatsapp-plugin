<?php

namespace GetByte\Whatsapp\Classes;

use GetByte\Whatsapp\Models\Account;
use GuzzleHttp\Client;

trait ApiTrait
{
    protected static function getBaseUrl()
    {
        return \Config::get('getbyte.whatsapp::api_url');
    }

    protected static function http(Account $account) : Client
    {
        $client = new Client([
            'base_uri' => self::getBaseUrl(),
            'headers' => [
                'Authorization' => 'Bearer ' . $account->secret_key,
                'Content-Type' => 'application/json'
            ],
        ]);

        return $client;
    }
}

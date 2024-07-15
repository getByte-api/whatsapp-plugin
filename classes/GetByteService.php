<?php

namespace GetByte\Whatsapp\Classes;

use GetByte\Whatsapp\Models\Account;
use GuzzleHttp\Exception\RequestException;

class GetByteService
{
    use ApiTrait;

    public static function getDetail(Account $account)
    {
        try {
            $response = self::http($account)->get('account/detail');
        } catch (RequestException $e) {
            throw new ApiException($e->getResponse());
        }

        return json_decode($response->getBody()->getContents());
    }
}

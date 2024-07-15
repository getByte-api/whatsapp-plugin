<?php

namespace GetByte\Whatsapp\Classes;

use General\General\Classes\Helpers\Phone;
use GetByte\Whatsapp\Models\Account;
use GuzzleHttp\Exception\RequestException;

class WhatsAppWpp
{
    use ApiTrait;

    public static function connect(Account $account): StatusConnectionResponse
    {
        return self::getStatus($account);
    }

    public static function getStatus(Account $account): StatusConnectionResponse
    {
        try {
            $response = self::http($account)
                ->post('whatsapp/start');
        } catch (RequestException $e) {
            throw new ApiException($e->getResponse());
        }

        $response = json_decode($response->getBody()->getContents());

        $status = new StatusConnectionResponse();
        $status->setStatus($response->response->state ?? 'CONNECTED');
        if ($response->response && property_exists($response->response, 'qrcode'))
            $status->setQrCode($response->response?->qrcode);

        return $status;
    }

    public static function sendText(string $user_phone_number, string $message, Account $account)
    {
        try {
            $response = self::http($account)
                ->post('whatsapp/sendText', [
                    'json' => [
                        "number"      => Phone::justnumber($user_phone_number),
                        "text"        => $message,
                        "time_typing" => 0,
                        "options"     => [
                            "delay"           => 0,
                            "createChat"      => true,
                            "detectMentioned" => false,
                            "markIsRead"      => false,
                            "waitForAck"      => false,
                        ],
                    ]
                ]);
        } catch (RequestException $e) {
            throw new ApiException($e->getResponse());
        }

        return json_decode($response->getBody()->getContents());
    }

    public static function sendMedia(string $mediaType, string $user_phone_number, string $mediaUrl, Account $account, string $caption = null)
    {
        try {
            $response = self::http($account)
                ->post('whatsapp/sendFile', [
                    'json' => [
                        "number"  => Phone::justnumber($user_phone_number),
                        "path"    => $mediaUrl,
                        'caption' => $caption ?? '',
                    ]
                ]);

        } catch (RequestException $e) {
            throw new ApiException($e->getResponse()->getBody()->getContents());
        }

        return json_decode($response->getBody()->getContents());
    }
}

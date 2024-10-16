<?php

namespace GetByte\Whatsapp\Classes;

use General\General\Classes\Helpers\Phone;
use GetByte\Whatsapp\Models\Account;
use GuzzleHttp\Exception\RequestException;

class WhatsAppWppGetByte
{
    use ApiTrait;

    public static function connect(Account $account): StatusConnectionResponse
    {
        return self::getStatus($account);
    }

    public static function getStatus(Account $account): StatusConnectionResponse
    {
        try {
            $response = self::http($account)->post('wpp/start-session');
        } catch (RequestException $e) {
            throw new ApiException($e->getResponse());
        }

        $response = json_decode($response->getBody()->getContents());

        $status = new StatusConnectionResponse();
        $status->setStatus($response?->status ?? 'DISCONNECTED');
        if ($response && $response?->qrcode)
            $status->setQrCode($response->qrcode);

        return $status;
    }

    public static function sendText(string $user_phone_number, string $message, Account $account)
    {
        try {
            $response = self::http($account)
                ->post('wpp/send-message', [
                    'json' => [
                        "phone"   => Phone::justnumber($user_phone_number),
                        "message" => $message,
                    ]
                ]);
        } catch (RequestException $e) {
            throw new ApiException($e->getResponse());
        }

        return json_decode($response->getBody()->getContents());
    }

    public static function sendMedia(string $mediaType, string $user_phone_number, string $mediaUrl, Account $account, string $caption = null, $document_filename = null)
    {
        $headers = get_headers($mediaUrl, 1);
        $mime_type = $headers["Content-Type"];

        $media = file_get_contents($mediaUrl);
        $base64 = base64_encode($media);

        $mediaBase64 = "data:" . $mime_type . ";base64," . $base64;


        $payload = [
            "phone"   => Phone::justnumber($user_phone_number),
            'caption' => $caption ?? '',
            "base64"  => $mediaBase64
        ];

        if ($document_filename) {
            $payload['filename'] = $document_filename;
        } else if ($mediaType == 'document') {
            $payload['filename'] = basename($mediaUrl);
        }

        try {
            $response = self::http($account)
                ->post('wpp/' . ($mediaType == 'document' ? 'send-file' : 'send-image'), [
                    'json' => $payload
                ]);
        } catch (RequestException $e) {
            throw new ApiException($e->getResponse());
        }

        return json_decode($response->getBody()->getContents());
    }
}

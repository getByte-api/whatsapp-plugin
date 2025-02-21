<?php

namespace GetByte\Whatsapp\Classes;

use General\General\Classes\Helpers\Phone;
use GetByte\Whatsapp\Models\Account;
use GuzzleHttp\Exception\RequestException;

class WhatsAppEvolutionGetByte
{
    use ApiTrait;

    public static function connect(Account $account): StatusConnectionResponse
    {
        try {
            $response = self::http($account)->get('evo/instance/connectionState');
        } catch (RequestException $e) {
            throw new ApiException($e->getResponse());
        }

        $responseState = json_decode($response->getBody()->getContents());

        $status = new StatusConnectionResponse();

        if ($responseState && ($responseState->instance->state == 'connecting' || $responseState->instance->state == 'close')) {
            try {
                $responseConnection = self::http($account)->get('evo/instance/connect');
                $responseConnection = json_decode($responseConnection->getBody()->getContents());
                $status->setQrCode($responseConnection->base64 ?? null);
                $status->setpairingCode($responseConnection->pairingCode ?? null);
            } catch (RequestException $e) {
                throw new ApiException($e->getResponse());
            }
        } else if ($responseState && $responseState->instance->state == 'disconnected') {
            try {
                $response = self::http($account)->put('evo/instance/restart');
            } catch (RequestException $e) {
                throw new ApiException($e->getResponse());
            }
        }

        $statusCode = $responseState ? $responseState?->instance?->state : 'disconnected';
        $status->setStatus($statusCode);

        return $status;
    }

    public static function getStatus(Account $account): StatusConnectionResponse
    {
        $status = new StatusConnectionResponse();

        try {
            $response = self::http($account)->get('evo/instance/connectionState');
        } catch (RequestException $e) {
            $exception = new ApiException($e->getResponse());
            $status->setStatus($exception->getMessage());
            return $status;
        }

        $responseState = json_decode($response->getBody()->getContents());
        $statusCode = $responseState ? $responseState?->instance?->state : 'disconnected';
        $status->setStatus($statusCode);

        return $status;
    }

    public static function sendText(string $user_phone_number, string $message, Account $account)
    {
        try {
            $response = self::http($account)
                ->post('evo/message/sendText', [
                    'json' => [
                        "number" => Phone::justnumber($user_phone_number),
                        "text"   => $message,
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

        $payload = [
            "number"    => Phone::justnumber($user_phone_number),
            "mediatype" => $mediaType,
            "mimetype"  => $mime_type,
            'caption'   => $caption ?? '',
            "media"     => $mediaUrl,
        ];

        if ($document_filename) {
            $payload['filename'] = $document_filename;
        } else if ($mediaType == 'document') {
            $payload['filename'] = basename($mediaUrl);
        }

        try {
            $response = self::http($account)
                ->post('evo/message/sendMedia', [
                    'json' => $payload
                ]);
        } catch (RequestException $e) {
            throw new ApiException($e->getResponse());
        }

        return json_decode($response->getBody()->getContents());
    }

    public static function logout(Account $account)
    {
        try {
            $response = self::http($account)->delete('evo/instance/logout');
        } catch (RequestException $e) {
            throw new ApiException($e->getResponse());
        }

        return json_decode($response->getBody()->getContents());
    }
}

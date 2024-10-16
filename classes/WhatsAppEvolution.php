<?php

namespace GetByte\Whatsapp\Classes;

use GetByte\Whatsapp\Models\Account;
use GuzzleHttp\Exception\RequestException;

class WhatsAppEvolution
{
    use ApiTrait;

    public static function connect(Account $account): StatusConnectionResponse
    {
        try {
            $response = self::http($account)->get('evolution/instance/connectionState');
        } catch (RequestException $e) {
            throw new ApiException($e->getResponse());
        }

        $responseState = json_decode($response->getBody()->getContents());

        $status = new StatusConnectionResponse();

        if ($responseState && ($responseState->response->instance->state == 'connecting' || $responseState->response->instance->state == 'close')) {
            try {
                $responseConnection = self::http($account)->get('evolution/instance/connect');
                $responseConnection = json_decode($responseConnection->getBody()->getContents());
                $status->setQrCode($responseConnection->response->base64 ?? null);
            } catch (RequestException $e) {
                throw new ApiException($e->getResponse());
            }
        } else if ($responseState && $responseState->response->instance->state == 'disconnected') {
            try {
                $response = self::http($account)->put('evolution/instance/restart');
            } catch (RequestException $e) {
                throw new ApiException($e->getResponse());
            }
        }

        $statusCode = $responseState ? $responseState->response?->instance?->state : 'disconnected';
        $status->setStatus($statusCode);

        return $status;
    }

    public static function getStatus(Account $account): StatusConnectionResponse
    {
        $status = new StatusConnectionResponse();

        try {
            $response = self::http($account)->get('evolution/instance/connectionState');
        } catch (RequestException $e) {
            $exception = new ApiException($e->getResponse());
            $status->setStatus($exception->getMessage());
            return $status;
        }

        $responseState = json_decode($response->getBody()->getContents());
        $statusCode = $responseState ? $responseState->response?->instance?->state : 'disconnected';
        $status->setStatus($statusCode);

        return $status;
    }

    public static function sendText(string $user_phone_number, string $message, Account $account)
    {
        try {
            $response = self::http($account)
                ->post('evolution/message/sendText', [
                    'json' => [
                        "number"      => $user_phone_number,
                        "options"     => [
                            "delay"    => 0,
                            "presence" => "composing"
                        ],
                        "textMessage" => [
                            "text" => $message
                        ]
                    ]
                ]);
        } catch (RequestException $e) {
            throw new ApiException($e->getResponse());
        }

        return json_decode($response->getBody()->getContents());
    }

    public static function sendMedia(string $mediaType, string $user_phone_number, string $mediaUrl, Account $account, string $caption = null, $document_filename = null)
    {
        $media = base64_encode(file_get_contents($mediaUrl));

        $payload = [
            "number"       => $user_phone_number,
            "options"      => [
                "delay"    => 1200,
                "presence" => "composing"
            ],
            "mediaMessage" => [
                'mediatype' => $mediaType,
                'caption'   => $caption ?? '',
                "media"     => $media
            ]
        ];

        if ($document_filename) {
            $payload['mediaMessage']['fileName'] = $document_filename;
        } else if ($mediaType == 'document') {
            $payload['mediaMessage']['fileName'] = basename($mediaUrl);
        }

        try {
            $response = self::http($account)
                ->post('evolution/message/sendMedia', [
                    'json' => $payload
                ]);
        } catch (RequestException $e) {
            throw new ApiException($e->getResponse());
        }

        return json_decode($response->getBody()->getContents());
    }
}

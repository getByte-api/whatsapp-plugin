<?php

namespace GetByte\Whatsapp\Classes;

use General\General\Classes\Helpers\Phone;
use GetByte\Whatsapp\Models\Account;
use GetByte\Whatsapp\Models\MessageLog;

class WhatsAppService
{
    public static function connect(Account $account)
    {
        if ($account->whatsapp_type == 'whatsapp-wpp') {
            return WhatsAppWpp::connect($account);
        } else {
            return WhatsAppEvolution::connect($account);
        }
    }

    public static function getStatus(Account $account)
    {
        if ($account->whatsapp_type == 'whatsapp-wpp') {
            return WhatsAppWpp::getStatus($account);
        } else {
            return WhatsAppEvolution::getStatus($account);
        }
    }

    public static function send(Account $account, string $messageType, string $phoneNumber, string $content, $document_filename = null, $caption = null)
    {
        if ($phoneNumber && Phone::validate($phoneNumber)) {

            try {
                if ($messageType == 'image' || $messageType == 'document') {
                    if ($account->whatsapp_type == 'whatsapp-wpp')
                        WhatsAppWpp::sendMedia($messageType, $phoneNumber, $content, $account, $caption);
                    else
                        WhatsAppEvolution::sendMedia($messageType, $phoneNumber, $content, $account, $caption, $document_filename);
                } else {
                    if ($account->whatsapp_type == 'whatsapp-wpp')
                        WhatsAppWpp::sendText($phoneNumber, $content, $account);
                    else
                        WhatsAppEvolution::sendText($phoneNumber, $content, $account);
                }

                MessageLog::create([
                    'to_phone_number' => $phoneNumber,
                    'account_id'      => $account->id,
                    'message_type'    => $messageType,
                    'content'         => [
                        'content'           => $content,
                        'document_filename' => $document_filename ?? '',
                        'caption'           => $caption ?? '',
                    ],
                ]);

            } catch (ApiException $e) {
                MessageLog::create([
                    'to_phone_number' => $phoneNumber,
                    'account_id'      => $account->id,
                    'message_type'    => $messageType,
                    'error'           => $e->getMessage(),
                    'content'         => [
                        'content'           => $content,
                        'document_filename' => $document_filename ?? '',
                        'caption'           => $caption ?? '',
                    ],
                ]);
            }
        }
    }
}

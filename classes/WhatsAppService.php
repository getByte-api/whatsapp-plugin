<?php

namespace GetByte\Whatsapp\Classes;

use GetByte\Whatsapp\Classes\Helpers\Phone;
use GetByte\Whatsapp\Models\Account;
use GetByte\Whatsapp\Models\MessageLog;

class WhatsAppService
{
    public static function connect(Account $account)
    {
        if ($account->whatsapp_type == 'whatsapp-wpp') {
            return WhatsAppWpp::connect($account);
        } else if ($account->whatsapp_type == 'whatsapp-evolution') {
            return WhatsAppEvolution::connect($account);
        } else if ($account->whatsapp_type == 'wpp-getbyte') {
            return WhatsAppWppGetByte::connect($account);
        }
    }

    public static function getStatus(Account $account): StatusConnectionResponse
    {
        if ($account->whatsapp_type == 'whatsapp-wpp') {
            $statusResponse = WhatsAppWpp::getStatus($account);
        } else if ($account->whatsapp_type == 'whatsapp-evolution') {
            $statusResponse = WhatsAppEvolution::getStatus($account);
        } else if ($account->whatsapp_type == 'wpp-getbyte') {
            $statusResponse = WhatsAppWppGetByte::getStatus($account);
        }

        $status = $statusResponse->getStatus();
        $account->status = $status ?: 'CONNECTED';
        $account->connected_at = ($account->status == 'CONNECTED') ? now() : null;
        $account->save();

        return $statusResponse;
    }

    public static function send(Account $account, string $messageType, string $phoneNumber, string $content, $document_filename = null, $caption = null)
    {
        $phoneNumber = Phone::justnumber($phoneNumber);

        if ($phoneNumber && Phone::validate($phoneNumber)) {

            try {
                if ($messageType == 'image' || $messageType == 'document') {
                    if ($account->whatsapp_type == 'whatsapp-wpp') {
                        WhatsAppWpp::sendMedia($messageType, $phoneNumber, $content, $account, $caption);
                    } else if ($account->whatsapp_type == 'whatsapp-evolution') {
                        WhatsAppEvolution::sendMedia($messageType, $phoneNumber, $content, $account, $caption, $document_filename);
                    } else if ($account->whatsapp_type == 'wpp-getbyte') {
                        WhatsAppWppGetByte::sendMedia($messageType, $phoneNumber, $content, $account, $caption, $document_filename);
                    }
                } else {
                    if ($account->whatsapp_type == 'whatsapp-wpp') {
                        WhatsAppWpp::sendText($phoneNumber, $content, $account);
                    } else if ($account->whatsapp_type == 'whatsapp-evolution') {
                        WhatsAppEvolution::sendText($phoneNumber, $content, $account);
                    } else if ($account->whatsapp_type == 'wpp-getbyte') {
                        WhatsAppWppGetByte::sendText($phoneNumber, $content, $account);
                    }
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

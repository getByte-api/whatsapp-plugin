<?php namespace GetByte\Whatsapp;

use Backend\Facades\Backend;
use GetByte\Whatsapp\Models\MessageLog;
use GetByte\Whatsapp\Models\Settings;
use GetByte\Whatsapp\NotifyRules\SendWhatsappMessage;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;

/**
 * GetByte Whatsapp Plugin Information File
 */
class Plugin extends PluginBase
{
    public function registerNotificationRules()
    {
        return [
            'actions' => [
                SendWhatsappMessage::class,
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Configurações',
                'category'    => 'API getByte',
                'class'       => Settings::class,
                'permissions' => ['getbyte.whatsapp.settings'],
                'order'       => 500,
                'keywords'    => 'whatsapp api getbyte',
            ],
            'logs'  => [
                'label'       => 'Registros de Mensagens de WhatsApp',
                'description' => 'Ver registros de mensagens de WhatsApp enviadas.',
                'category'    => SettingsManager::CATEGORY_LOGS,
                'icon'        => 'icon-envelope-o',
                'url'         => Backend::url('getbyte/whatsapp/messagelogs'),
                'order'       => 900,
                'keywords'    => 'whatsapp api getbyte log',
                'permissions' => ['getbyte.whatsapp.logs']
            ],
            'accounts'  => [
                'label'       => 'Contas de WhatsApp',
                'description' => 'Gerenciar contas de WhatsApp.',
                'category'    => 'API getByte',
                'icon'        => 'icon-whatsapp',
                'url'         => Backend::url('getbyte/whatsapp/accounts'),
                'order'       => 900,
                'keywords'    => 'whatsapp api getbyte account',
                'permissions' => ['getbyte.whatsapp.accounts']
            ],
        ];
    }

    public function registerSchedule($schedule)
    {
        $schedule->call(function () {
            $days = Settings::get('purge_days', 7);
            $date = now()->subDays($days);
            MessageLog::where('sent_at', '<', $date)->delete();
        })->daily();
    }
}

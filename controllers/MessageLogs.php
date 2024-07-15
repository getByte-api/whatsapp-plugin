<?php namespace GetByte\Whatsapp\Controllers;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use Backend\Classes\Controller;
use Flash;
use GetByte\Whatsapp\Models\MessageLog;
use System\Classes\SettingsManager;

class MessageLogs extends Controller
{
    public $implement = [
        ListController::class,
        FormController::class
    ];

    public $listConfig = 'config_list.yaml';

    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'getbyte.whatsapp.logs'
    ];

    public function __construct()
    {
        parent::__construct();

        SettingsManager::setContext('GetByte.Whatsapp', 'messagelog');
    }

    public function index_onEmptyLog()
    {
        MessageLog::truncate();
        Flash::success('Logs de mensagens apagados com sucesso.');
        return $this->listRefresh();
    }
}

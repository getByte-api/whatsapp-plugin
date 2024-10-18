<?php namespace GetByte\Whatsapp\Controllers;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use Backend\Classes\Controller;
use Flash;
use GetByte\Whatsapp\Classes\WhatsAppService;
use GetByte\Whatsapp\Models\MessageLog;
use October\Rain\Exception\ValidationException;
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

    public function index_onRetryMessage()
    {
        $message_log_id = post('message_log_id');

        $messageLog = MessageLog::find($message_log_id);

        if ($messageLog) {

            try {
                if ($messageLog->message_type == 'document' || $messageLog->message_type == 'image') {
                    WhatsAppService::send(
                        $messageLog->account,
                        $messageLog->message_type,
                        $messageLog->to_phone_number,
                        $messageLog->content['content'] ?? null,
                        $messageLog->content['document_filename'] ?? null,
                        $messageLog->content['caption'] ?? null
                    );
                } else {
                    WhatsAppService::send(
                        $messageLog->account,
                        $messageLog->message_type,
                        $messageLog->to_phone_number,
                        $messageLog->content['content'] ?? null
                    );
                }
            } catch (\Exception $exception) {
                throw new ValidationException(['account' => $exception->getMessage()]);
            }

            Flash::success('Mensagem enviada novamente com sucesso.');

            return $this->listRefresh();
        } else {
            Flash::error('Mensagem não encontrada.');
        }
    }
}

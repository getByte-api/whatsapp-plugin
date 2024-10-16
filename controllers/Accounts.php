<?php namespace GetByte\Whatsapp\Controllers;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use General\BackendSkin\Controller\BaseController;
use GetByte\Whatsapp\Classes\GetByteService;
use GetByte\Whatsapp\Classes\WhatsAppService;
use GetByte\Whatsapp\Models\Account;
use October\Rain\Exception\ValidationException;
use System\Classes\SettingsManager;
use Flash;

class Accounts extends BaseController
{
    public $implement = [
        ListController::class,
        FormController::class
    ];

    public $listConfig = 'config_list.yaml';

    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'getbyte.whatsapp.accounts',
        'getbyte.whatsapp.accounts.*'
    ];

    public function __construct()
    {
        parent::__construct();

        SettingsManager::setContext('GetByte.Whatsapp', 'accounts');

        $this->addJs('/plugins/getbyte/whatsapp/assets/js/accounts.js');
    }

    public function index_onLoadSendTest()
    {
        if (!$this->user->hasAccess('getbyte.whatsapp.accounts.send_test')) {
            throw new ValidationException(['account' => 'Você não tem permissão para enviar mensagens de teste']);
        }

        $this->vars['account_id'] = post('account_id');
        return $this->makePartial('popup_send_test');
    }

    public function index_onLoadConnectDevice()
    {
        $this->vars['account_id'] = post('account_id');
        return $this->makePartial('popup_connect_device');
    }

    public function onSendTest()
    {
        if (!$this->user->hasAccess('getbyte.whatsapp.accounts.send_test')) {
            Flash::error('Você não tem permissão para enviar mensagens de teste');
            return;
        }

        $account = Account::find(post('account_id'));
        $message = post('message');
        $phone = post('phone');
        $message_type = post('message_type');
        $file_url = post('file_url');

        if (!$phone) {
            Flash::error('Telefone não informado');
            return;
        }

        if (!$account) {
            Flash::error('Conta não encontrada');
            return;
        }

        if (!$message && $message_type == 'text') {
            Flash::error('Mensagem não informada');
            return;
        }

        if (!$file_url && $message_type == 'image') {
            Flash::error('Url da Imagem não informada');
            return;
        }

        if (!$file_url && $message_type == 'document') {
            Flash::error('Url do Documento não informada');
            return;
        }

        try {
            if ($message_type == 'document' || $message_type == 'image') {
                WhatsAppService::send($account, $message_type, $phone, $file_url, null, $message ?: 'Teste de Mensagem');
            } else {
                WhatsAppService::send($account, $message_type, $phone, $message);
            }
        } catch (\Exception $exception) {
            throw new ValidationException(['account' => $exception->getMessage()]);
        }

        Flash::success('Mensagem enviada com sucesso');
    }

    public function onCheckStatus()
    {
        $account = Account::find(post('account_id'));

        if (!$account) {
            Flash::error('Conta não encontrada');
            return;
        }

        try {
            WhatsAppService::getStatus($account);
        } catch (\Exception $exception) {
            throw new ValidationException(['account' => $exception->getMessage()]);
        }

        Flash::success('Status atualizado');
        return redirect()->refresh();
    }

    public function onConnectDevice()
    {
        $account = Account::find(post('account_id'));

        if (!$account) {
            Flash::error('Conta não encontrada');
            return;
        }

        try {
            $statusResponse = WhatsAppService::connect($account);
        } catch (\Exception $exception) {
            throw new ValidationException(['account' => $exception->getMessage()]);
        }

        $account->status = $statusResponse->getStatus();

        if ($account->status == 'CONNECTED') {
            $account->connected_at = now();
            Flash::success('Conexão estabelecida com sucesso.');
        }

        $account->save();

        return $statusResponse->toArray();
    }

    public function formBeforeCreate($model)
    {
        $model->fill((array)post('Account'));

        try {
            $accountDetail = GetByteService::getDetail($model);
        } catch (\Exception $exception) {
            throw new ValidationException(['secret_key' => $exception->getMessage()]);
        }

        if (property_exists($accountDetail, 'error') && $accountDetail->error) {
            throw new ValidationException(['secret_key' => $accountDetail?->error]);
        }

        $model->whatsapp_type = $accountDetail->api_type;

        try {
            WhatsAppService::getStatus($model);
        } catch (\Exception $exception) {
            throw new ValidationException(['secret_key' => $exception->getMessage()]);
        }
    }
}

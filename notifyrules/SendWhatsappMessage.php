<?php namespace GetByte\Whatsapp\NotifyRules;

use GetByte\Whatsapp\Classes\WhatsAppService;
use GetByte\Whatsapp\Models\Account;
use General\General\Classes\Helpers\Phone;
use General\General\Classes\Helpers\Util;
use RainLab\Notify\Classes\ActionBase;

class SendWhatsappMessage extends ActionBase
{
    public function defineValidationRules()
    {
        return [
            'account_type'      => 'required|in:account,secret_key,account_id',
            'account_id'        => 'required_if:account_type,account_id',
            'secret_key'        => 'required_if:account_type,secret_key',
            'account'           => 'required_if:account_type,account',
            'message_type'      => 'required',
            'user_phone_number' => 'required',
            'text'              => 'required_if:message_type,text',
            'image'             => 'required_if:message_type,image',
        ];
    }

    public function actionDetails()
    {
        return [
            'name'        => 'API getByte - Enviar Mensagem via WhatsApp',
            'description' => 'Envia uma mensagem para um número de WhatsApp via API getByte.',
            'icon'        => 'fa-brands fa-whatsapp'
        ];
    }

    public function defineFormFields()
    {
        return 'fields.yaml';
    }

    public function getText()
    {
        $message_type = $this->host->message_type;
        $message_type = $this->getMessageTypeOptions()[$message_type] ?? $message_type;
        $user_phone_number = $this->host->user_phone_number;

        return 'Enviar um(a) ' . $message_type . ' para: ' . $user_phone_number;
    }

    /**
     * Triggers this action.
     * @param array $params
     * @return void
     */
    public function triggerAction($params)
    {
        if ($this->host->account_type == 'account') {
            $account = Account::where('id', $this->host->account)->firstOrFail();
        } else if ($this->host->account_type == 'secret_key') {
            $secret_key = Util::twigRawParser((string)$this->host->secret_key, $params);
            $account = Account::where('secret_key', $secret_key)->firstOrFail();
        } else if ($this->host->account_type == 'account_id') {
            $account_id = Util::twigRawParser((string)$this->host->account_id, $params);
            $account = Account::where('id', $account_id)->firstOrFail();
        }

        if ($account->status != 'CONNECTED') {
            WhatsAppService::getStatus($account);
            throw new \Exception('The WhatsApp account is not connected - ' . $account->status . ' - ' . $account->name);
        }

        $phoneNumber = Util::twigRawParser((string)$this->host->user_phone_number, $params);
        $phoneNumber = '55' . ltrim($phoneNumber, '55');
        $phoneNumber = Phone::justnumber($phoneNumber);
        $document_filename = Util::twigRawParser((string)$this->host->document_filename, $params);
        $caption = Util::twigRawParser((string)$this->host->caption, $params);
        $messageType = (string)$this->host->message_type;

        if ($messageType == 'text') {
            $content = Util::twigRawParser((string)$this->host->text, $params);
        } else if ($messageType == 'image') {
            $content = Util::twigRawParser((string)$this->host->image, $params);
        } else if ($messageType == 'document') {
            $content = Util::twigRawParser((string)$this->host->document, $params);
        } else {
            $content = Util::twigRawParser($messageType, $params);
        }

        try {
            WhatsAppService::send(
                $account,
                $messageType,
                $phoneNumber,
                $content,
                $document_filename,
                $caption
            );
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getMessageTypeOptions(): array
    {
        return [
            'text'     => 'Texto',
            'image'    => 'Imagem',
            'document' => 'Documento',
        ];
    }

    public function getAccountOptions(): array
    {
        foreach (Account::all() as $account) {
            $options[$account->id] = $account->name . ' - ' . $account->status;
        }

        return $options ?? [];
    }
}

<?php namespace GetByte\Whatsapp\Controllers;

use Backend\Classes\Controller;
use GetByte\Whatsapp\Classes\WhatsAppService;
use GetByte\Whatsapp\Models\Account;
use October\Rain\Exception\ValidationException;

class Connect extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function connectDevice()
    {
        try {
            $account = Account::find(decrypt(post('account_key')));

            if (!$account) {
                return response()->json(['error' => 'Conta não encontrada'], 404);
            }

            try {
                $statusResponse = WhatsAppService::connect($account);
                
                // Atualiza o status da conta
                $status = $statusResponse->getStatus();
                $account->status = $status ?: 'DISCONNECTED';
                if ($status == 'CONNECTED' && $account->status != $status) {
                    $account->connected_at = now();
                }
                $account->save();

                return response()->json($statusResponse->toArray());
            } catch (\Exception $exception) {
                // Em caso de erro, marca como desconectado
                $account->status = 'DISCONNECTED';
                $account->save();
                return response()->json(['error' => $exception->getMessage()], 500);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function logout()
    {
        try {
            $account = Account::find(decrypt(post('account_key')));

            if (!$account) {
                return response()->json(['error' => 'Conta não encontrada'], 404);
            }

            try {
                WhatsAppService::logout($account);
                $account->status = 'DISCONNECTED';
                $account->connected_at = null;
                $account->save();
                return response()->json(['success' => true]);
            } catch (\Exception $exception) {
                if (str_contains($exception->getMessage(), 'not connected')) {
                    $account->status = 'DISCONNECTED';
                    $account->connected_at = null;
                    $account->save();
                    return response()->json(['success' => true]);
                }
                return response()->json(['error' => $exception->getMessage()], 400);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
} 
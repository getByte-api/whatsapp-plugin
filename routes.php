<?php

use GetByte\Whatsapp\Classes\WhatsAppService;
use GetByte\Whatsapp\Models\Account;

Route::get('connect/whatsapp/{Key}', function (\Illuminate\Http\Request $request) {
    try {
        $account_id = decrypt($request->Key);
        $account = Account::where('id', $account_id)->firstOrFail();
    } catch (Exception $e) {
        return 'Conta não encontrada';
    }

    return view('getbyte.whatsapp::connect-whatsapp', compact('account'));
});

Route::post('connect/whatsapp/connect-device', function (\Illuminate\Http\Request $request) {
    $account_id = decrypt($request->input('account_key'));
    $account = Account::find($account_id);

    try {

        if (!$account) {
            throw new \Exception('Conta não encontrada');
        }

        $statusResponse = WhatsAppService::connect($account);

        $account->status = $statusResponse->getStatus();

        if ($account->status == 'CONNECTED') {
            $account->connected_at = now();
        }

        $account->save();

        return response()->json($statusResponse->toArray());
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

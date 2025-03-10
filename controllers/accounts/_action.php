<?php if ($record->status == 'CONNECTED' && $this->user->hasPermission('getbyte.whatsapp.accounts.send_test')): ?>
    <button type="button"
            data-control="popup"
            data-handler="onLoadSendTest"
            data-request-data="account_id: <?= $record->id ?>"
            href="javascript:;"
            data-bs-toggle="tooltip"
            data-bs-placement="bottom"
            data-bs-title="Enviar mensagem de teste"
            class="btn btn-sm btn-warning"><i class="fa-regular fa-paper-plane"></i></button>
<?php endif; ?>
<?php if ($record->status != 'CONNECTED'): ?>
    <button type="button"
            data-request-data="account_id: <?= $record->id ?>"
            data-bs-toggle="tooltip"
            data-bs-placement="bottom"
            data-link="<?= url('connect/whatsapp/' . encrypt($record->id)) ?>"
            data-bs-title="Compartilhar QRCode"
            class="btn btn-sm btn-success copyLinkButton"><i class="fa-solid fa-share-nodes"></i></button>
<?php endif; ?>
<button type="button"
        data-request="onCheckStatus"
        data-request-data="account_id: <?= $record->id ?>"
        href="javascript:;"
        data-bs-toggle="tooltip"
        data-bs-placement="bottom"
        data-bs-title="Verificar status"
        class="btn btn-sm btn-secondary">
    <i class="fa-solid fa-rotate"></i>
</button>

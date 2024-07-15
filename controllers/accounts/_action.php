<?php if ($record->status == 'CONNECTED'): ?>
    <button type="button"
            data-control="popup"
            data-handler="onLoadSendTest"
            data-request-data="account_id: <?= $record->id ?>"
            href="javascript:;"
            data-bs-toggle="tooltip"
            data-bs-placement="bottom"
            data-bs-title="Enviar mensagem de teste"
            class="btn btn-sm btn-success"><i class="fa-brands fa-whatsapp"></i></button>
<?php endif; ?>
<?php if ($record->status != 'CONNECTED'): ?>
    <button type="button"
            data-control="popup"
            data-handler="onLoadConnectDevice"
            data-request-data="account_id: <?= $record->id ?>"
            href="javascript:;"
            data-bs-toggle="tooltip"
            data-bs-placement="bottom"
            data-bs-title="Conectar"
            class="btn btn-sm btn-info"><i class="fa-solid fa-qrcode"></i></button>
    <button type="button"
            data-request-data="account_id: <?= $record->id ?>"
            data-bs-toggle="tooltip"
            data-bs-placement="bottom"
            data-link="<?= url('connect/whatsapp/' . encrypt($record->id)) ?>"
            data-bs-title="Compartilhar QRCode"
            class="btn btn-sm btn-warning copyLinkButton"><i class="fa-solid fa-share-nodes"></i></button>
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

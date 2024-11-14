<div class="modal-header">
    <button type="button" class="close" data-dismiss="popup">&times;</button>
    <h4 class="modal-title">
        Tentando conexão com o WhatsApp...
    </h4>
</div>
<form class="form-elements" role="form" data-request="onSendTest" data-popup-load-indicator>

    <div class="modal-body">

        <div class="row mb-3 text-center loading">
            <div class="col-sm">
                <img src="/plugins/getbyte/whatsapp/assets/img/loading.gif" alt="" class="loading-img w-50">
            </div>
        </div>

        <div class="row mb-3 text-center success d-none">
            <div class="col-sm">
                <img src="/plugins/getbyte/whatsapp/assets/img/check-success.png" alt="" class="w-50">
            </div>
        </div>

        <div class="row mb-3 justify-content-center qrcode-container d-none">
            <div class="col-6" id="qrcode"></div>
        </div>

        <div class="col-12">
            <div class="alert alert-warning" role="alert">Recomendamos limpar as mensagens antigas ou arquivar as
                conversas para evitar problemas de sincronização.
            </div>
            <div class="alert alert-secondary d-none" id="container-counter" role="alert"><i class="bx bxs-timer"></i>
                <span id="seconds-counter">Já se passaram <span class="conter-value"></span> segundos, o processo pode levar até 60 segundos...</span>
            </div>
            <div id="message-qrcode" class="alert alert-primary message" role="alert">Gerando o QR code. Por favor, aguarde. Esse processo pode demorar alguns segundos.</div>
        </div>

        <div class="row mb-3 d-none pairing-container">
            <div class="col-sm d-flex justify-content-center">
                <label for="pairing_code_input" class="form-label" id="pairing_code_label">ou informe o código gerado no aplicativo do WhatsApp</label>
                <input
                    type="text"
                    name="pairing_code"
                    readonly
                    class="form-control mx-auto"
                    placeholder="Informe o código de pariedade gerado pelo WhatsApp"
                    id="pairing_code_input"
                    style="width: 50%; text-align: center;"
                >
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button
            type="button"
            class="btn btn-default"
            data-dismiss="popup">
            <?= e(trans('backend::lang.relation.close')) ?>
        </button>
    </div>
</form>

<script>
    checkStatusConnection(<?= $account_id ?>);
</script>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="popup">&times;</button>
    <h4 class="modal-title">
        Tentando conexão com o WhatsApp...
    </h4>
</div>
<form class="form-elements" role="form" data-request="onConnectDevice" data-popup-load-indicator>

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
            <div class="col-6 text-center" id="qrcode">
                <img src="" alt="QR Code" class="img-fluid qrcode-image" style="max-width: 100%; height: auto; display: inline-block;">
                <div class="waiting-qrcode d-none text-center">
                    <i class='bx bx-loader-alt bx-spin' style="font-size: 80px; color: #3498db;"></i>
                    <p class="mt-3" style="color: #3498db; font-size: 18px;">Aguardando novo QR Code...</p>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="alert alert-warning" role="alert">Recomendamos limpar as mensagens antigas ou arquivar as
                conversas para evitar problemas de sincronização.
            </div>
            <div class="alert alert-secondary d-none" id="container-counter" role="alert">
                <i class="bx bxs-timer"></i>
                <span id="seconds-counter"></span>
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
    $(document).on('hide.oc.popup', function() {
        if (checkStatusInterval) {
            clearInterval(checkStatusInterval);
            checkStatusInterval = null;
        }
        stopCounter();
    });
    
    checkStatusConnection(<?= $account_id ?>);
</script>

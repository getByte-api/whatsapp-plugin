<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Conectar WhatsApp</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="row mb-3 text-center loading">
                <div class="col-sm">
                    <img src="/plugins/getbyte/whatsapp/assets/img/whatsapp-logo.png" alt=""
                         class="loading-img w-30">
                </div>
            </div>

            <div class="row mb-3 text-center success d-none">
                <div class="col-sm">
                    <img src="/plugins/getbyte/whatsapp/assets/img/check-success.png" alt="" class="w-50">
                </div>
            </div>

            <div class="row mb-3 justify-content-center qrcode-container d-none">
                <div class="col-5 mt-5" id="qrcode"></div>
            </div>

            <div class="col-12">
                <div class="alert alert-info container-instruction" role="alert">

                    <h4 class="text-center mb-4 mt-4">Como Conectar o WhatsApp Web</h4>

                    <ul class="text-left">
                        <li>Abra o WhatsApp no celular.</li>
                        <li>
                            Acesse o menu:

                            Toque nos três pontos (Android) ou "Configurações" (iPhone).
                            Selecione "WhatsApp Web" ou "Dispositivos conectados".
                        </li>
                        <li>
                            Toque em "Conectar um dispositivo" e escaneie o QR code que vai aparecer aqui ao iniciar.
                        </li>
                    </ul>
                </div>

                <div class="alert alert-warning warning-before" role="alert">Recomendamos limpar as mensagens antigas ou arquivar as
                    conversas para evitar problemas de sincronização.

                    <strong>Por favor, inicie o processo quando estiver pronto.</strong>
                </div>

                <div class="text-center start-button">
                    <button type="button"
                            class="btn btn-success"
                            id="btn-reload" onclick="checkStatusConnection('<?= encrypt($account->id) ?>')">
                        Iniciar conexão
                    </button>
                </div>

                <div id="message-qrcode" class="alert alert-primary message d-none" role="alert">Gerando o QR code. Por favor, aguarde. Esse processo pode demorar alguns segundos.</div>

                <div class="alert alert-success d-none" id="container-success" role="alert">
                    <i class="bx bxs-check-circle"></i> Conexão estabelecida com sucesso! Você já pode fechar essa janela.
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var checkStatusInterval = null;
    var isRequestPending = false;

    function checkStatusConnection(account_key) {
        checkConnectionDevice(account_key);
        checkStatusInterval = setInterval(function () {
            checkConnectionDevice(account_key);
        }, 10000);
    }

    function checkConnectionDevice(account_key) {
        if (isRequestPending) return;

        isRequestPending = true;

        $('.start-button').addClass('d-none');
        $('.warning-before').addClass('d-none');
        $('#message-qrcode').removeClass('d-none');

        $.ajax({
            url: '<?= url('connect/whatsapp/connect-device') ?>',
            method: 'POST',
            dataType: 'json',
            data: {account_key: account_key},
            complete: function() {
                isRequestPending = false;
            },
            success: function(data) {
                if (data.status == "CONNECTED") {
                    $('#message-qrcode').addClass('d-none');
                    $('.qrcode-container').addClass('d-none');
                    $('.loading').addClass('d-none');
                    $('#container-counter').addClass('d-none');
                    $('.success').removeClass('d-none');
                    $('#container-success').removeClass('d-none');
                    $('.container-instruction').addClass('d-none');
                    clearInterval(checkStatusInterval);
                    clearInterval(counterInterval);
                } else if (data.qrcode) {
                    $('.loading').addClass('d-none');
                    $('.qrcode-container').removeClass('d-none');
                    $('#qrcode').html('<img src="' + data.qrcode + '" alt="QR Code" />');
                    $('#message-qrcode').html('Leia o QR Code exibido na tela com o seu WhatsApp');
                }
            },
            error: function(xhr, status, error) {
                clearInterval(checkStatusInterval);
                isRequestPending = false;
            }
        });
    }
</script>
</body>
</html>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Conectar WhatsApp</title>
    <link rel="icon" type="image/png" href="/plugins/getbyte/whatsapp/assets/img/getbyte-icon.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #fdf5ef;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            max-width: 1000px;
            margin: 0 auto;
            width: 100%;
        }
        .header-logo {
            height: 50px;
            width: auto;
        }
        .header-logo.whatsapp {
            height: 50px;
            width: auto;
            display: flex;
            align-items: center;
        }
        .main-container {
            background: white;
            border-radius: 25px;
            border: 1px solid black;
            padding: 26px 50px;
            max-width: 1000px;
            margin: 20px auto;
            width: 100%;
            position: relative;
        }
        .content-wrapper {
            display: flex;
            align-items: flex-start;
            gap: 40px;
            position: relative;
        }
        .left-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .right-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            min-height: 400px;
        }
        .qrcode-placeholder {
            width: 264px;
            height: 264px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
        .qrcode-placeholder img {
            width: 70%;
            height: auto;
            opacity: 0.2;
        }
        .instructions {
            width: 100%;
            text-align: left;
        }
        .instructions li {
            margin-bottom: 18px;
        }
        .alert {
            border: none;
            border-radius: 8px;
        }
        .alert-warning {
            background-color: #fff5e6;
            color: #664d03;
        }
        .counter-container {
            font-size: 14px;
            color: #667781;
            margin-top: 10px;
            width: 100%;
            max-width: 264px;
            text-align: center;
        }
        .waiting-qrcode {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
        }
        #qrcode {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success-container {
            text-align: center;
            padding: 60px;
            background-color: #e7f8f4;
            border-radius: 12px;
            margin: 20px 0;
        }
        .success-container img {
            width: 120px;
            margin-bottom: 30px;
        }
        .success-container h4 {
            color: #00a884;
            margin-bottom: 15px;
            font-size: 24px;
        }
        .success-container p {
            color: #667781;
            font-size: 16px;
        }
        .encryption-notice {
            text-align: center;
            color: #8696a0;
            font-size: 14px;
            margin-top: 20px;
        }
        .encryption-notice i {
            margin-right: 5px;
        }
        .start-button {
            margin: 40px auto 20px;
            text-align: center;
        }
        .btn-success {
            background-color: #00a884;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 24px;
            min-width: 200px;
        }
        .btn-success:hover {
            background-color: #008f6f;
        }
        .qrcode-container {
            border: 4px solid #000000;
            border-radius: 24px;
            padding: 4px;
            width: 264px;
            height: 264px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .qrcode-container img {
            max-width: 100%;
            height: auto;
            border-radius: 20px;
        }
    </style>
</head>
<body>
<div class="header">
    <img src="/plugins/getbyte/whatsapp/assets/img/logo-whatsapp-bege.png" alt="WhatsApp" class="header-logo whatsapp">
    <img src="/plugins/getbyte/whatsapp/assets/img/getbyte-azul-transparent.png" alt="GetByte" class="header-logo">
</div>

<div class="main-container">
    <div class="row mb-3 text-center success-container d-none" id="success-message">
        <div class="col-sm">
            <img src="/plugins/getbyte/whatsapp/assets/img/check-success.png" alt="" class="success-icon">
            <h4>WhatsApp Conectado com Sucesso!</h4>
            <p>Sua conta está pronta para enviar e receber mensagens.</p>
            <p class="mt-3" style="font-size: 14px;">Você já pode fechar essa janela.</p>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="left-content">
            <div class="instructions container-instruction">
                <h4 class="text-center mb-4">Como Conectar o WhatsApp Web</h4>
                <ol class="ps-3">
                    <li>Abra o WhatsApp no celular</li>
                    <li>Acesse o menu: Toque nos três pontos (Android) ou "Configurações" (iPhone)</li>
                    <li>Selecione "WhatsApp Web" ou "Dispositivos conectados"</li>
                    <li>Toque em "Conectar um dispositivo" e escaneie o QR code</li>
                </ol>
            </div>

            <div class="alert alert-warning warning-before" role="alert">
                <i class='bx bx-info-circle'></i>
                Recomendamos limpar as mensagens antigas ou arquivar as conversas para evitar problemas de sincronização.
                <p class="mt-2 mb-0"><strong>Por favor, inicie o processo quando estiver pronto.</strong></p>
            </div>

            <div class="alert alert-info" style="display: none;" id="message-qrcode" role="alert">
                <i class='bx bx-scan'></i> Leia o QR Code exibido na tela com o seu WhatsApp
            </div>

            <div class="alert alert-danger" style="display: none;" id="message-error" role="alert">
                <i class='bx bx-error-circle'></i> <span id="error-text"></span>
            </div>
        </div>

        <div class="right-content">
            <div class="qrcode-container" style="display: none;">
                <div class="text-center" id="qrcode">
                    <div class="waiting-qrcode">
                        <i class='bx bx-loader-alt bx-spin' style="font-size: 80px; color: #00a884;"></i>
                        <p class="mt-3" style="color: #667781;">Aguardando QR Code...</p>
                    </div>
                </div>
            </div>
            <div class="qrcode-placeholder">
                <img src="/plugins/getbyte/whatsapp/assets/img/whatsapp-icon.png" alt="WhatsApp">
            </div>
            <div class="alert alert-secondary" style="display: none;" id="container-counter">
                <i class="bx bxs-timer"></i>
                <span id="seconds-counter"></span>
            </div>
        </div>
    </div>

    <div class="text-center start-button">
        <button type="button" class="btn btn-success" id="btn-reload" onclick="checkStatusConnection('<?= encrypt($account->id) ?>')">
            <i class='bx bx-qr-scan'></i> Iniciar conexão
        </button>
    </div>
</div>

<div class="encryption-notice">
    <i class='bx bx-lock-alt'></i> Suas mensagens pessoais são protegidas com criptografia de ponta a ponta
</div>

<script>
    var checkStatusInterval = null;
    var isRequestPending = false;
    var lastQrCode = null;
    var qrCodeRegenerationCount = 0;
    var countdownSeconds = 30;
    var counterInterval = null;

    function showError(message) {
        $('#message-error').show();
        $('#error-text').html(message);
        $('.qrcode-container').hide();
        $('.qrcode-placeholder').show();
        $('#message-qrcode').hide();
        $('#container-counter').hide();
        $('.start-button').show();
        stopCounter();
    }

    function hideError() {
        $('#message-error').hide();
    }

    function checkStatusConnection(account_key) {
        $('.start-button').hide();
        $('.warning-before').hide();
        $('.qrcode-placeholder').hide();
        $('.qrcode-container').show();
        hideError();
        $('#message-qrcode').html('<i class="bx bx-loader-alt bx-spin"></i> Verificando status da conexão...');
        $('#message-qrcode').show();
        
        $.ajax({
            url: '/connect/whatsapp/connect-device',
            method: 'POST',
            dataType: 'json',
            data: {account_key: account_key},
            success: function(data) {
                if (data.error) {
                    showError(data.error);
                    return;
                }
                
                if (data.status === 'CONNECTED') {
                    $('#message-qrcode').hide();
                    $('.qrcode-container').hide();
                    $('.container-instruction').hide();
                    $('.content-wrapper').hide();
                    $('#success-message').removeClass('d-none').fadeIn(500);
                    return;
                }
                
                $.ajax({
                    url: '/connect/whatsapp/logout',
                    method: 'POST',
                    dataType: 'json',
                    data: {account_key: account_key},
                    success: function() {
                        checkConnectionDevice(account_key);
                        checkStatusInterval = setInterval(function () {
                            checkConnectionDevice(account_key);
                        }, 5000);
                    },
                    error: function(xhr) {
                        let errorMsg = 'Erro ao desconectar a conta';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMsg = xhr.responseJSON.error;
                        }
                        showError(errorMsg);
                    }
                });
            },
            error: function(xhr) {
                let errorMsg = 'Erro ao verificar status da conta';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                }
                showError(errorMsg);
            }
        });
    }

    function checkConnectionDevice(account_key) {
        if (isRequestPending) return;
        isRequestPending = true;

        $.ajax({
            url: '/connect/whatsapp/connect-device',
            method: 'POST',
            dataType: 'json',
            data: {account_key: account_key},
            success: function(data) {
                if (data.error) {
                    showError(data.error);
                    isRequestPending = false;
                    return;
                }
                
                if (data.status == "CONNECTED") {
                    $('#message-qrcode').hide();
                    $('.qrcode-container').hide();
                    $('.container-instruction').hide();
                    $('.content-wrapper').hide();
                    $('#success-message').removeClass('d-none').fadeIn(500);
                    stopCounter();
                    isRequestPending = false;
                    return;
                }
                
                if (data.qrcode) {
                    hideError();
                    $('.qrcode-container').show();
                    $('.qrcode-placeholder').hide();
                    $('#qrcode').html(`
                        <img src="${data.qrcode}" alt="QR Code" class="qrcode-image" style="display: block;">
                        <div class="waiting-qrcode" style="display: none;">
                            <i class='bx bx-loader-alt bx-spin' style="font-size: 80px; color: #00a884;"></i>
                            <p class="mt-3" style="color: #667781;">Aguardando novo QR Code...</p>
                        </div>
                    `);
                    
                    $('#message-qrcode').show();
                    $('#message-qrcode').html('<i class="bx bx-scan"></i> Leia o QR Code exibido na tela com o seu WhatsApp');
                    qrCodeRegenerationCount++;
                    lastQrCode = data.qrcode;
                    startCounter(account_key);
                }
                isRequestPending = false;
            },
            error: function(xhr) {
                let errorMsg = 'Erro ao verificar conexão';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                }
                showError(errorMsg);
                isRequestPending = false;
            }
        });
    }

    function startCounter(account_key) {
        if (counterInterval) {
            clearInterval(counterInterval);
            counterInterval = null;
        }
        countdownSeconds = 30;
        updateCounterDisplay();
        $('#container-counter').show();
        
        counterInterval = setInterval(function () {
            countdownSeconds--;
            if (countdownSeconds < 0) {
                clearInterval(counterInterval);
                counterInterval = null;
                $('.qrcode-image').hide();
                $('.waiting-qrcode').show();
                
                // Verifica o status antes de fazer logout
                $.ajax({
                    url: '/connect/whatsapp/connect-device',
                    method: 'POST',
                    dataType: 'json',
                    data: {account_key: account_key},
                    success: function(data) {
                        if (data.status === 'CONNECTED') {
                            // Se já estiver conectado, mostra mensagem de sucesso
                            $('#message-qrcode').hide();
                            $('.qrcode-container').hide();
                            $('#container-counter').hide();
                            $('.container-instruction').hide();
                            $('.content-wrapper').hide();
                            $('#success-message').removeClass('d-none').fadeIn(500);
                            stopCounter();
                            return;
                        }
                        
                        // Se não estiver conectado, faz logout e gera novo QR code
                        $.ajax({
                            url: '/connect/whatsapp/logout',
                            method: 'POST',
                            dataType: 'json',
                            data: {account_key: account_key},
                            success: function() {
                                checkConnectionDevice(account_key);
                            }
                        });
                    },
                    error: function() {
                        // Em caso de erro na verificação, tenta o processo normal
                        $.ajax({
                            url: '/connect/whatsapp/logout',
                            method: 'POST',
                            dataType: 'json',
                            data: {account_key: account_key},
                            success: function() {
                                checkConnectionDevice(account_key);
                            }
                        });
                    }
                });
            }
            updateCounterDisplay();
        }, 1000);
    }

    function updateCounterDisplay() {
        let message = '';
        if (countdownSeconds <= 0) {
            message = `Gerando novo QR Code...<br>Já foi regenerado <strong>${qrCodeRegenerationCount}</strong> ${qrCodeRegenerationCount === 1 ? 'vez' : 'vezes'}.`;
        } else {
            message = `QR Code será regenerado em <strong>${countdownSeconds}</strong> segundos.<br>Já foi regenerado <strong>${qrCodeRegenerationCount}</strong> ${qrCodeRegenerationCount === 1 ? 'vez' : 'vezes'}.`;
        }
        $('#seconds-counter').html(message);
    }

    function stopCounter() {
        if (counterInterval) {
            clearInterval(counterInterval);
            counterInterval = null;
        }
        if (checkStatusInterval) {
            clearInterval(checkStatusInterval);
            checkStatusInterval = null;
        }
        countdownSeconds = 30;
        qrCodeRegenerationCount = 0;
        lastQrCode = null;
        $('.qrcode-image').hide();
        $('.waiting-qrcode').show();
    }
</script>
</body>
</html>

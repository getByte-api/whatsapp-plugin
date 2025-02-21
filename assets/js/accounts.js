var checkStatusInterval = null;
var isRequestPending = false;
var lastQrCode = null;
var qrCodeRegenerationCount = 0;
var countdownSeconds = 30;
var counterInterval = null;

function checkStatusConnection(account_id) {
    // Faz logout inicial antes de começar
    $.request('onLogout', {
        data: { account_id: account_id },
        loading: false,
        success: function() {
            // Inicia a verificação do status apenas após o logout inicial
            checkConnectionDevice(account_id);
            // Verifica a cada 5 segundos se houve mudança no servidor
            checkStatusInterval = setInterval(function() {
                checkConnectionDevice(account_id);
            }, 5000);
        }
    });
}

function startCounter(account_id) {
    $('#container-counter').removeClass('d-none');
    if (counterInterval) {
        clearInterval(counterInterval);
        counterInterval = null;
    }
    countdownSeconds = 30;
    $('.qrcode-image').removeClass('d-none');
    $('.waiting-qrcode').addClass('d-none');
    updateCounterDisplay();
    counterInterval = setInterval(function () {
        countdownSeconds--;
        if (countdownSeconds < 0) {
            clearInterval(counterInterval);
            counterInterval = null;
            $('.qrcode-image').addClass('d-none');
            $('.waiting-qrcode').removeClass('d-none');
            
            // Faz logout antes de gerar novo QR code
            $.request('onLogout', {
                data: { account_id: account_id },
                loading: false,
                success: function() {
                    checkConnectionDevice(account_id);
                }
            });
        }
        updateCounterDisplay();
    }, 1000);
}

function updateCounterDisplay() {
    let message = '';
    if (countdownSeconds <= 0) {
        message = `Desconectando e gerando novo QR Code...<br>Já foi regenerado <strong>${qrCodeRegenerationCount}</strong> ${qrCodeRegenerationCount === 1 ? 'vez' : 'vezes'}.`;
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
    $('.qrcode-image').removeClass('d-none');
    $('.waiting-qrcode').addClass('d-none');
}

function checkConnectionDevice(account_id) {
    if (isRequestPending) return;

    isRequestPending = true;

    $.request('onConnectDevice', {
        loading: false,
        flash: true,
        data: {account_id: account_id},
        complete: function (data) {
            isRequestPending = false;
        },
        success: function (data) {
            if (data.status == "CONNECTED") {
                $('.qrcode-container').addClass('d-none');
                $('.loading').addClass('d-none');
                $('#container-counter').addClass('d-none');
                $('.success').removeClass('d-none');
                stopCounter();
                setTimeout(function () {
                    location.reload();
                }, 2000);
            } else if (data.qrcode) {
                $('.loading').addClass('d-none');
                $('.qrcode-container').removeClass('d-none');
                
                // Se for um QR code novo ou se o contador estiver zerado
                if (data.qrcode !== lastQrCode || countdownSeconds <= 0) {
                    if (data.qrcode !== lastQrCode) {
                        qrCodeRegenerationCount++;
                    }
                    lastQrCode = data.qrcode;
                    countdownSeconds = 30;
                    startCounter(account_id);
                }

                $('#qrcode').html(`
                    <div class="text-center">
                        <img src="${data.qrcode}" alt="QR Code" class="img-fluid qrcode-image" style="max-width: 100%; height: auto; display: inline-block;">
                        <div class="waiting-qrcode d-none text-center">
                            <i class='bx bx-loader-alt bx-spin' style="font-size: 80px; color: #3498db;"></i>
                            <p class="mt-3" style="color: #3498db; font-size: 18px;">Aguardando novo QR Code...</p>
                        </div>
                    </div>
                `);
                $('.qrcode-image').removeClass('d-none');
                $('.waiting-qrcode').addClass('d-none');
                $('#message-qrcode').html('Leia o QR Code com o seu WhatsApp');

                if (data.pairingCode) {
                    $('.pairing-container').removeClass('d-none');
                    let code = data.pairingCode;
                    let firstPart = code.substring(0, 4);
                    let secondPart = code.substring(4, 8);
                    $('[name="pairing_code"]').val(firstPart + '-' + secondPart);
                }
            }
        },
        error: function (data) {
            clearInterval(checkStatusInterval);
            isRequestPending = false;
            this.error(data)
        }
    });
}

addEventListener('ajax:setup', function (event) {
    const {options} = event.detail.context;

    options.flash = true;

    options.handleErrorMessage = function (message) {
        if (message) {
            if (typeof message === 'object' && message !== null && 'X_OCTOBER_ERROR_FIELDS' in message) {
                for (const key in message.X_OCTOBER_ERROR_FIELDS) {
                    console.log('fields', message);
                    oc.flashMsg({message: message.X_OCTOBER_ERROR_FIELDS[key], type: 'error'});
                    $('[name="' + key + '"]').focus();
                    break;
                }
            } else {
                oc.flashMsg({message: message, type: 'error'});
            }
        }
    }


    options.handleFlashMessage = function (message, type) {
        oc.flashMsg({message: message, type: type});
    }

    options.handleValidationMessage = function (message, fields) {
        if (message) {
            oc.flashMsg({message: message, type: 'error'});
        } else {
            for (const key in fields) {
                oc.flashMsg({message: fields[key], type: 'error'});
                $('[name="' + key + '"]').focus();
                break;
            }
        }
    }
});

$(document).ready(function () {

    $('.copyLinkButton').click(function () {
        const link = $(this).data('link');
        const tempInput = $('<input>');
        $('body').append(tempInput);
        tempInput.val(link).select();
        document.execCommand('copy');
        tempInput.remove();
        oc.flashMsg({message: 'Link copiado com sucesso!', type: 'info'});
    });
});

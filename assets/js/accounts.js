var checkStatusInterval = null;
var isRequestPending = false;

function checkStatusConnection(account_id) {
    checkConnectionDevice(account_id);
    checkStatusInterval = setInterval(function () {
        checkConnectionDevice(account_id);
    }, 10000);
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
                clearInterval(checkStatusInterval);
                setTimeout(function () {
                    location.reload();
                }, 2000);
            } else if (data.qrcode) {
                startCounter();
                $('.loading').addClass('d-none');
                $('.qrcode-container').removeClass('d-none');
                $('#qrcode').html('<img src="' + data.qrcode + '" alt="QR Code" />');
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

var counterInterval = null;

function startCounter() {
    $('#container-counter').removeClass('d-none');
    clearInterval(counterInterval);
    var seconds = 0;
    counterInterval = setInterval(function () {
        $('#seconds-counter .conter-value').text(seconds);
        seconds++;
    }, 1000);
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

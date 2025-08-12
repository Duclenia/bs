$(document).ready(function () {

    var msg = 'Para receberes notificações por SMS sobre o andamento dos processos por favor, verifique o telemóvel';

    $('#notificacao_tel').html(msg);
    $('#md_notification_tel').modal('show');


    $('.validar_tel').on('click', function () {

        $('#md_notification_tel').modal('hide');

        $('#md_notification_tel').on('hidden.bs.modal', function (e) {
            $("body").addClass("modal-open");
        });
        
        $('#md_verificar_tel').modal('show');
    });

});


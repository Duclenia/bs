$(document).ready(function () {

    var url = $('#get_notification').val();

    $.ajax({

        type: 'get',
        url: url,
        dataType: 'JSON',
        success: function (response) {

            $.each(response, function (key, value) {

                const now = new Date(); // Data de hoje
                const data_inicio = new Date(value.data_inicio);
                const diff = Math.abs(data_inicio.getTime() - now.getTime()); // Subtrai uma data pela outra
                const days = Math.ceil(diff / (1000 * 60 * 60 * 24));

                if (days >= 1 && days <= 5)
                {
                    //alert('Tens ' + days + ' dia(s)' + ' para realizares a tarefa ');

                    var msg = '';
                    
                    if (value.relacionada_a == 'case') {

                        msg = 'Falta(m) ' + days + ' dia(s)' + ' para o início da(o) ' + value.assunto + ' do processo de nº interno ' + addZeroes(value.no_interno, 7)+ 'BSA';
                    } else {

                        msg = 'Tens ' + days + ' dia(s)' + ' para realizares / início a(o) ' + value.assunto;
                    }

                    $('#notificacao').html(msg);

                    $('#md_notification').modal('show');

                }

            });
        },
        error: function () {

        }
    });

    $('.btn_ok').on('click', function () {

        $('#md_notification').modal('hide');

//        $('#md_notification').on('hidden.bs.modal', function (e) {
//            $("body").addClass("modal-open");
//        });

    });

});

function addZeroes(num, len)
{
    var numberWithZeroes = String(num);
    var counter = numberWithZeroes.length;

    while (counter < len) {

        numberWithZeroes = "0" + numberWithZeroes;

        counter++;

    }

    return numberWithZeroes;
}



var getDayAppointment = $('#get-day-appointmentsCliente').val();
var ajaxCalander = $('#ajaxCalanderCliente').val();

$(document).ready(function () {

    //$("#hora").mask("99:99");


    $('#agenda_cliente').fullCalendar({

        views: {
            timeGrid: {
                eventLimit: 6
            }
        },
        timezone: 'local',
        locale: 'pt',
        left: 'Calendar',
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listMonth'
        },
        buttonText: {
            today: 'Hoje',
            month: 'Mês',
            week: 'Semana',
            day: 'Dia',
            list: 'Lista'
        },
        eventClick: function (calEvent, jsEvent, view) {
            var id = calEvent.id;

        },

        dayClick: function (date, jsEvent, view, resourceObj) {
            // Mostrar agendas do dia clicado
            showDayAppointments(date.format('YYYY-MM-DD'));
            /*  $('#modal_agenda #data').val(date.format());
             $('#modal_agenda').modal('show'); */
        },
        dayRender: function (date, cell) {
            // Verificar se o dia tem agendamentos
            checkDayAppointments(date, cell);
        },

        events: function (start, end, timezone, callback) {
            var current = $('#agenda_cliente').fullCalendar('getDate');

            var new_url = ajaxCalander;

            $.ajax({
                url: new_url,
                dataType: 'json',
                type: 'GET',
                data: {
                    start: current.format('M'),
                    end: current.format('YYYY')
                },
                success: function (response) {
                    callback(response);
                    //ajaxindicatorstop();
                }
            });
        }

    });

    $('#btn_agendar').click(function () {

        var form = $('#form_agendar');
        var hora = $('#hora');
        var assunto = $('#assunto');

        if ((hora.val() != '') && (assunto.val() != '')) {

            form.submit();
        }
    });

});

function checkDayAppointments(date, cell) {
    var today = moment().startOf('day');
    var cellDate = moment(date).startOf('day');

    // Pintar de cinza se a data já passou
    if (cellDate.isBefore(today)) {
        cell.css({
            'background-color': '#f5f5f5',
            'color': '#999',
            'opacity': '0.6'
        });
        return; // Não verificar agendamentos para datas passadas
    }

    $.ajax({
        url: '/bs/cliente/get-day-appointmentsCliente',
        type: 'POST',
        data: {
            _token: token,
            selected_date: date.format('YYYY-MM-DD')
        },
        success: function (response) {
            if (response.success && response.total > 0) {
                if (cellDate.isBefore(today)) {
                    cell.css({
                        'background-color': '#f5f5f5',
                        'color': '#999',
                        'opacity': '0.6'
                    });
                    cell.append('<span class="appointment-badge" style="background-color:#3c3c3c" > ' + response.total + '</span>');
                } else {
                    cell.css({
                        'background-color': '#6ee50230',
                        'position': 'relative'
                    });
                    cell.append('<span class="appointment-badge" > ' + response.total + '</span>');
                }
            }
        }
    });
}

function showDayAppointments(selectedDate) {
    $.ajax({
        url: '/bs/cliente/get-day-appointmentsCliente',
        type: 'POST',
        data: {
            _token: token,
            selected_date: selectedDate
        },
        success: function (response) {
            var appointments = response.data;
            var modalContent = '<div class="modal fade" id="dayAppointmentsModal" tabindex="-1" role="dialog">';
            modalContent += '<div class="modal-dialog" role="document">';
            modalContent += '<div class="modal-content">';
            modalContent += '<div class="modal-header">';
            modalContent += '<h4 class="modal-title">Agendamentos de ' + moment(selectedDate).format('DD/MM/YYYY') + '</h4>';
            modalContent += '<button type="button" class="close" data-dismiss="modal">&times;</button>';
            modalContent += '</div>';
            modalContent += '<div class="modal-body">';

            if (appointments && appointments.length > 0) {
                modalContent += '<div class="table-responsive">';
                modalContent += '<table class="table table-striped">';
                modalContent += '<thead><tr><th>Horário</th><th>Tipo</th><th>Status</th></tr></thead>';
                modalContent += '<tbody>';

                appointments.forEach(function (appointment) {

                    modalContent += '<tr>';
                    modalContent += '<td>' + appointment.time + '</td>';
                    modalContent += '<td>' + (appointment.name || 'Agendamento') + '</td>';
                    modalContent += '<td><span class="label label-info">' + appointment.status + '</span></td>';
                    modalContent += '</tr>';
                });

                modalContent += '</tbody></table></div>';
            } else {
                modalContent += '<p class="text-center">Nenhum agendamento encontrado para este dia.</p>';
            }

            modalContent += '</div>';
            modalContent += '<div class="modal-footer">';
            modalContent += '<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>';
            modalContent += '</div>';
            modalContent += '</div></div></div>';

            // Remover modal anterior se existir
            $('#dayAppointmentsModal').remove();

            // Adicionar e mostrar novo modal
            $('body').append(modalContent);
            $('#dayAppointmentsModal').modal('show');
        },
        error: function () {
            alert('Erro ao carregar agendamentos do dia.');
        }
    });
} 
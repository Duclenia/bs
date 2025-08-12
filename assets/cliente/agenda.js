$(document).ready(function () {

    //$("#hora").mask("99:99");


    $('#agenda_cliente').fullCalendar({

        locale: 'pt',
        eventLimit: true,
        editable: true,
        //weekends: false,

        views: {
            timeGrid: {
                eventLimit: 6 // adjust to 6 only for timeGridWeek/timeGridDay
            }
        },
        // put your options and callbacks here
        timezone: 'local',
        left: 'Calendar',
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listMonth'
        },
        eventClick: function (calEvent, jsEvent, view) {
            var id = calEvent.id;

        },

        viewRender: function (i) {
            var ini = moment();
            if (ini >= i.start && ini <= i.end) {
                $(".fc-prev-button")
                        .prop('disabled', true)
                        .addClass('fc-state-disabled');
            } else {
                $(".fc-prev-button")
                        .removeClass('fc-state-disabled')
                        .prop('disabled', false);
            }
        },

        //selectable: true,

        dayClick: function (date, jsEvent, view, resourceObj) {

            $('#modal_agenda #data').val(date.format());
            $('#modal_agenda').modal('show');
        },

        events: function (start, end, timezone, callback) {
            //ajaxindicatorstart('Please wait a moment..Fetching  detail');
            //var current = $('#agenda_cliente').fullCalendar('getDate');
            // alert(current);

            $.ajax({
                url: '/cliente/ajaxCalander',
                dataType: 'json',
                type: 'GET',
//                        data: {
//                            start: current.format('M'),
//                            end: current.format('YYYY')
//                        },
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


"use strict";

var token = $('#token-value').val();
var caserunning = $('#case-running').val();
var appointment = $('#appointment').val();
var ajaxCalander = $('#ajaxCalander').val();
var date_format_datepiker = $('#date_format_datepiker').val();
var dashboard_appointment_list = $('#dashboard_appointment_list').val();
var getDayAppointment=$('#get-day-appointments').val();
var getNextDateModald = $('#getNextDateModal').val();
var getChangeCourtModald = $('#getChangeCourtModal').val();
var getCaseImportantModald = $('#getCaseImportantModal').val();
var getCourtd = $('#getCourt').val();
var downloadCaseBoardd = $('#downloadCaseBoard').val();
var printCaseBoardd = $('#printCaseBoard').val();
var language = $('#language').val();

var t;
var DatatableRemoteAjaxDemo = function () {

    var lsitDataInTable = function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        t = $('#appointment_list').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [[0, "desc"]],
            "oLanguage": {sProcessing: "<div class='loader-container'><div id='loader'></div></div>"},
            "ajax": {
                "url": dashboard_appointment_list,
                "dataType": "json",
                "type": "POST",
                "data": {
                    _token: token,
                    appoint_date: $('#appoint_range').val()

                }
            },
            "columns": [
                {"data": "id"},
                {"data": "name"},
                // { "data": "mobile" },
                {"data": "date"},
                {"data": "time"},
                        // { "data": "options" },
            ],
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                },
                {
                    "targets": [-2], //last column
                    "orderable": false, //set not orderable
                },
            ], language: {
                paginate: {
                    next: '<i class="fa fa-angle-right">',
                    previous: '<i class="fa fa-angle-left">'
                }
            },

        })
    }

    //== Public Functions
    return {
        // public functions
        init: function () {
            lsitDataInTable();

            $('.datecase').datepicker({
                format: date_format_datepiker,
                language: 'pt',
                autoclose: "close",
                orientation: 'bottom left'
            });

            $('#appoint_range').datepicker({
                format: date_format_datepiker,
                language: 'pt',
                autoclose: "close",
                orientation: 'bottom left'
            });

            $('#calendar_dashbors_case').fullCalendar({
                eventLimit: true,
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

                    if (calEvent.refer == "case") {
                        window.location.href = caserunning + "/" + id;
                    } else {
                        window.location.href = appointment + "/" + id + "/edit";
                    }
                },
                dayClick: function(date, jsEvent, view) {
                    // Mostrar agendas do dia clicado
                    showDayAppointmentsAdmin(date.format('YYYY-MM-DD'));
                },
                dayRender: function(date, cell) {
                    // Verificar se o dia tem agendamentos
                    checkDayAppointmentsAdmin(date, cell);
                },
                events: function (start, end, timezone, callback) {
                    //ajaxindicatorstart('Please wait a moment..Fetching  detail');
                    var current = $('#calendar_dashbors_case').fullCalendar('getDate');
                    // alert(current);
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
                    })
                }

            });
            
            
            $('#appoint_range').on('change', function () {
                t.destroy();
                DatatableRemoteAjaxDemo.init()
            });
            // $("#search").click(function () {
            //     t.destroy();
            //     DatatableRemoteAjaxDemo.init()
            // });
            //
            // $("#clear").click(function () {
            //     $('#date_from').val('');
            //     $('#date_to').val('');
            //     t.destroy();
            //     DatatableRemoteAjaxDemo.init()
            //     $("#search").attr("disabled", "disabled");
            // });


        }
    };
}();
jQuery(document).ready(function () {
    DatatableRemoteAjaxDemo.init()
    $('#client_case').on('change', function () {
        $('#case_board_form').submit();
    });

});

function nextDateAdd(case_id) {
    // ajax get modal
    $.ajax({
        url: getNextDateModald + "/" + case_id,
        success: function (data) {
            // ajaxindicatorstop();
            $('#show_modal_next_date').html(data);
            $('#modal-next-date').modal({
                backdrop: false,
                show: true,
            }); // show bootstrap modal
            $('.modal-title').text('Add Next Date'); // Set Title to Bootstrap modal title
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
        }
    });
}

function transfer_case(case_id) {
    // ajaxindicatorstart('loading modal.. please wait..');
    // ajax get modal
    $.ajax({
        url: getChangeCourtModald + "/" + case_id,
        success: function (data) {
            // ajaxindicatorstop();
            $('#show_modal_transfer').html(data);
            $('#modal-change-court').modal({
                backdrop: false,
                show: true,
            }); // show bootstrap modal
            $('.modal-title').text('Case Transfer'); // Set Title to Bootstrap modal title
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
        }
    });
}

function change_case_important(case_id) {
    // ajaxindicatorstart('loading modal.. please wait..');
    // ajax get modal
    $.ajax({
        url: getCaseImportantModald + '/' + case_id,
        success: function (data) {
            // ajaxindicatorstop();
            $('#show_modal').html(data);
            $('#modal-case-priority').modal({
                backdrop: false,
                show: true,
            }); // show bootstrap modal
            $('.modal-title').text('Change Case Important'); // Set Title to Bootstrap modal title
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
        }
    });
}

function getCourt(id) {
    if (id != '') {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        });
        $.ajax({
            url: getCourtd,
            method: "POST",
            data: {id: id},
            success: function (result) {
                if (result.errors) {
                    $('.alert-danger').html('');
                } else {
                    $('#court_name').html(result);
                }
            }
        });
    }
}

function downloadCaseBorad() {
    $date = $('#client_case').val();
    window.location.href = downloadCaseBoardd + "/" + $date;
}

function printCaseBorad() {
    $date = $('#client_case').val();

    window.open(printCaseBoardd + '/' + $date, '_blank');
}

function checkDayAppointmentsAdmin(date, cell) {
    $.ajax({
        url: '/bs/admin/get-day-appointments',
        type: 'POST',
        data: {
            _token: token,
            selected_date: date.format('YYYY-MM-DD')
        },
        success: function(response) {
            if (response.success && response.total > 0) {
                // Pintar o dia com agendamentos
                cell.css({
                    'background-color': '#fcf8e3',
                 
                    'position': 'relative'
                });
                
                // Adicionar badge com número de agendamentos
                cell.append('<span class="appointment-badge" > ' + response.total + '</span>');
            }
        }
    });
}

function showDayAppointmentsAdmin(selectedDate) {
    $.ajax({
        url: '/bs/admin/get-day-appointments',
        type: 'POST',
        data: {
            _token: token,
            selected_date: selectedDate
        },
        success: function(response) {
            
            var appointments = response.data;
            var modalContent = '<div class="modal fade" id="dayAppointmentsModal" tabindex="-1" role="dialog">';
            modalContent += '<div class="modal-dialog" role="document">';
            modalContent += '<div class="modal-content">';
            modalContent += '<div class="modal-header">';
            modalContent += '<h4 class="modal-title">Agendamentos de ' + moment(selectedDate).format('DD/MM/YYYY') + '</h4>';
            modalContent += '<button type="button" class="close" data-dismiss="modal">&times;</button>';
            modalContent += '</div>';
            modalContent += '<div class="modal-body">';
            
            if (appointments.length > 0) {
                modalContent += '<div class="table-responsive">';
                modalContent += '<table class="table table-striped">';
                modalContent += '<thead><tr><th>Cliente</th><th>Hora</th><th>Telefone</th></tr></thead>';
                modalContent += '<tbody>';
                
                appointments.forEach(function(appointment) {
                    modalContent += '<tr>';
                    modalContent += '<td>' + appointment.name + '</td>';
                    modalContent += '<td>' + appointment.time + '</td>';
                    modalContent += '<td>' + appointment.mobile + '</td>';
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
        error: function() {
           
            alert('Erro ao carregar agendamentos do dia.');
        }
    });
}


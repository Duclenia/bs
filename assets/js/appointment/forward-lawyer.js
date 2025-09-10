let flatpickrInstance;

function openForwardModal(consultaId) {
    $('#consultaId').val(consultaId);
    $('#novoAdvogado').val('');
    $('#novaData').val('');
    $('#novoHorario').html('<option value="">Selecionar horário</option>');

    // Carregar lista de advogados
    $.get(window.routes.getAdvogados, function(response) {
        let options = '<option value="">Selecionar Advogado</option>';
        response.forEach(adv => {
            options += `<option value="${adv.id}">${adv.nome} ${adv.sobrenome}</option>`;
        });
        $('#novoAdvogado').html(options);
    });

    $('#forwardModal').modal('show');
}

// Event listener para mudança de advogado
$('#novoAdvogado').on('change', function() {
    const advogadoId = $(this).val();
    $('#novaData').val('');
    $('#novoHorario').html('<option value="">Selecionar horário</option>');

    if (advogadoId) {
        if (flatpickrInstance) {
            flatpickrInstance.destroy();
        }

        $.get(window.routes.blockedDates + '/' + advogadoId, function(response) {
            flatpickrInstance = flatpickr('#novaData', {
                dateFormat: 'Y-m-d',
                minDate: response.min_date,
                disable: [function(date) { return response.blocked_days.includes(date.getDay()); }],
                locale: flatpickr.l10ns.pt,
                onChange: function(selectedDates, dateStr) {
                    if (dateStr && advogadoId) {
                        $.get(window.routes.availableTimes.replace('__ADVOGADO__', advogadoId).replace('__DATA__', dateStr), function(response) {
                            let options = '<option value="">Selecionar horário</option>';
                            if (response.available_times && response.available_times.length > 0) {
                                response.available_times.forEach(time => {
                                    options += `<option value="${time}">${time}</option>`;
                                });
                            } else {
                                options += '<option value="">Nenhum horário disponível</option>';
                            }
                            $('#novoHorario').html(options);
                        });
                    }
                }
            });
        });
    }
});

$('#btnConfirmarEncaminhamento').on('click', function() {
    const formData = {
        agendamento_id: $('#consultaId').val(),
        novo_advogado_id: $('#novoAdvogado').val(),
        nova_data: $('#novaData').val(),
        novo_horario: $('#novoHorario').val(),
        motivo: $('textarea[name="motivo"]').val(),
        _token: $('input[name="token-value"]').val()
    };

    if (!formData.novo_advogado_id || !formData.nova_data || !formData.novo_horario) {
        alert('Por favor, preencha todos os campos obrigatórios.');
        return;
    }

    $.ajax({
        url: window.routes.encaminhar,
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#forwardModal').modal('hide');
                 message.fire({
                    type: 'success',
                    title: 'Sucesso',
                    text: "Agenda encaminhada com sucesso!"
                });
                location.reload();
            } else {
                alert('Erro ao encaminhar consulta: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.log('Erro:', xhr.responseText);
            let errorMsg = 'Erro ao processar solicitação.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            alert(errorMsg);
        }
    });
});

function change_status(id, status, table) {
    if (status === 'TO FORWARD') {
        openForwardModal(id);
        return false;
    }

    $.confirm({
        title: 'Tem a certeza de que pretende alterar o estado?',
        content: 'Esta ação irá alterar o estado do agendamento.',
        icon: 'fa fa-question-circle',
        animation: 'scale',
        closeAnimation: 'scale',
        opacity: 0.5,
        buttons: {
            confirm: {
                text: 'Sim!',
                btnClass: 'btn-orange',
                action: function () {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        }
                    });
                    $.ajax({
                        url: common_change_state,
                        type: "POST",
                        dataType: "JSON",
                        data: { id: id, status: status, table: table },
                        async: false,
                        success: function (data) {
                            if (data.errors) {
                                message.fire({
                                    type: 'error',
                                    title: 'Error',
                                    text: "Problem in delete!!! Please try again."
                                });
                            } else {
                                message.fire({
                                    type: 'success',
                                    title: 'Sucesso',
                                    text: "Estado alterado com sucesso."
                                });
                            }
                            var d = $('#Appointmentdatatable').DataTable();
                            d.destroy();
                            DatatableRemoteAjaxDemo.init();
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            alert('Error adding / update data');
                        }
                    });
                }
            },
            cancel: {
                text: 'Cancelar',
                btnClass: 'btn-default',
                action: function () {
                    var d = $('#Appointmentdatatable').DataTable();
                    d.destroy();
                    DatatableRemoteAjaxDemo.init();
                }
            }
        }
    });
}
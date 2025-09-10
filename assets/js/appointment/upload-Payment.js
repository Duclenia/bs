function openUploadModal(consultaId) {
    $('#uploadConsultaId').val(consultaId);
    $('#comprovativo').val('');
    $('#uploadModal').modal('show');
}

$('#btnUploadComprovativo').on('click', function () {
    const formData = new FormData();
    formData.append('agendamento_id', $('#uploadConsultaId').val());
    formData.append('comprovativo', $('#comprovativo')[0].files[0]);
    formData.append('_token', $('#token-value').val());

    if (!$('#comprovativo')[0].files[0]) {
        alert('Por favor, selecione um arquivo PDF.');
        return;
    }

    $.ajax({
        url: window.routes.uploadComprovativo,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.success) {
                $('#uploadModal').modal('hide');
                message.fire({
                    type: 'success',
                    title: 'Sucesso',
                    text: "Comprovativo enviado com sucesso!"
                });
                location.reload();
            } else {
                alert('Erro: ' + response.message);
            }
        },
        error: function (xhr) {
            alert('Erro ao enviar comprovativo.' + xhr.responseText);
        }
    });
});
function submitAgendamento() {
    const btnSubmit = document.getElementById('btn_submit');
    const btnSubmitExisting = document.getElementById('btn_submit_existing');
    const loader = document.getElementById('show_loader');
    
    // Desabilitar botão e mostrar loading
    if (btnSubmit) {
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fa fa-spinner fa-spin"></i>&nbsp;Processando...';
    }
    if (btnSubmitExisting) {
        btnSubmitExisting.disabled = true;
        btnSubmitExisting.innerHTML = '<i class="fa fa-spinner fa-spin"></i>&nbsp;Processando...';
    }

    // Coletar dados do formulário
    const formData = new FormData();
    const isNewClient = document.getElementById('test5').checked;
    
    if (isNewClient) {
        collectNewClientData(formData);
    } else {
        collectExistingClientData(formData);
    }
    
    // Processar em etapas
    processAgendamentoAsync(formData);
}

function collectNewClientData(formData) {
    // Dados do cliente
    const clientFields = ['f_name', 'l_name', 'instituicao', 'email', 'telefone', 'endereco'];
    clientFields.forEach(field => {
        const element = document.querySelector(`[name="${field}"]`);
        if (element) formData.append(field, element.value);
    });
    
    collectAgendaData(formData);
    collectPaymentData(formData);
    formData.append('type', 'new');
}

function collectExistingClientData(formData) {
    const clientId = document.querySelector('[name="exists_client"]');
    if (clientId) formData.append('exists_client', clientId.value);
    
    collectAgendaData(formData);
    collectPaymentData(formData);
    formData.append('type', 'exists');
}

function collectAgendaData(formData) {
    const agendaFields = ['date', 'time', 'vc_motivo', 'vc_nota', 'it_termo'];
    agendaFields.forEach(field => {
        const element = document.querySelector(`[name="${field}"]`);
        if (element) {
            if (element.type === 'checkbox') {
                formData.append(field, element.checked ? 1 : 0);
            } else {
                formData.append(field, element.value);
            }
        }
    });
    formData.append('type_agenda', 'reuniao');
}

function collectPaymentData(formData) {
    const paymentFields = ['valor_consulta', 'forma_pagamento', 'referencia_pagamento', 'observacoes_pagamento'];
    paymentFields.forEach(field => {
        const element = document.querySelector(`[name="${field}"]`);
        if (element) formData.append(field, element.value);
    });
}

async function processAgendamentoAsync(formData) {
    try {
        // Etapa 1: Criar agendamento básico (rápido)
        const agendaResponse = await fetch('/admin/agenda/store', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (agendaResponse.redirected) {
            window.location.href = agendaResponse.url;
            return;
        }
        
        const agendaResult = await agendaResponse.json();
        
        if (agendaResult.success) {
            // Sucesso - redirecionar imediatamente
            window.location.href = '/admin/reuniao';
        } else {
            throw new Error(agendaResult.message || 'Erro ao criar agendamento');
        }
        
    } catch (error) {
        resetButton();
        alert('Erro: ' + error.message);
    }
}

function resetButton() {
    const btnSubmit = document.getElementById('btn_submit');
    const btnSubmitExisting = document.getElementById('btn_submit_existing');
    
    if (btnSubmit) {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = '<i class="fa fa-save"></i>&nbsp;Finalizar agendamento';
    }
    if (btnSubmitExisting) {
        btnSubmitExisting.disabled = false;
        btnSubmitExisting.innerHTML = '<i class="fa fa-save"></i>&nbsp;Finalizar Agendamento';
    }
}

$.validator.addMethod("uniqueEmail", function(value, element) {
    // Só valida se for novo cliente
    var clientType = $('input[name="type"]:checked').val();
    if (clientType !== 'new') {
        return true; // Não valida para cliente existente
    }
    
    var isValid = true;
    if (value && value.length > 0) {
        $.ajax({
            url: '/bs/admin/check_client_email_exits',
            type: 'POST',
            data: {
                email: value,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            dataType: 'json',
            success: function(response) {
                if (response && typeof response.exists !== 'undefined') {
                    isValid = !response.exists;
                } else {
                    isValid = true;
                }
            },
            error: function(xhr, status, error) {
                isValid = true;
            }
        });
    }
    return isValid;
}, "Este email já está a ser usado.");

// Validation

$('#add_appointment').validate({
    debug: false,
    rules: {
        mobile: {
            required: true
        },
        data: "required",
        email: {
            required: function(element) {
                return $('input[name="type"]:checked').val() === 'new';
            },
            email: true,
            uniqueEmail: true
        },
        hora: "required",
        new_client: "required",
        exists_client: "required",
    },
    messages: {
        mobile: {
            required: "Por favor, insere o nº de telefone."
        },
        data: "Por favor, insere a data.",
        hora: "Por favor, insere a hora.",
        email: {
            required: "Por favor, insere o email.",
            email: "Por favor, insere um email válido.",
            uniqueEmail: "Este email já está a ser usado."
        },
        new_client: "Por favor, insere o nome do cliente.",
        exists_client: "Por favor, seleccione o cliente."
    },
    errorPlacement: function (error, element) {
        error.appendTo(element.parent()).addClass('text-danger');
    },
    submitHandler: function (e) {
        $('#show_loader').removeClass('fa-save');
        $('#show_loader').addClass('fa-spin fa-spinner');
        $("button[name='btn_add_appointment']").attr("disabled", "disabled").button('refresh');
        return true;
    }
})



$('#add_appointment_existent').validate({
    debug: false,
    rules: {
        mobile: {
            required: true
        },
        data: "required",
        email: {
            required: false,
            email: true
        },
        hora: "required",
        exists_client: "required",
    },
    messages: {
        mobile: {
            required: "Por favor, insere o nº de telefone."
        },
        email: {
            email: "Por favor, insere um email válido."
        },
        data: "Por favor, insere a data.",
        hora: "Por favor, insere a hora.",
        exists_client: "Por favor, seleccione o cliente."
    },
    errorPlacement: function (error, element) {
        error.appendTo(element.parent()).addClass('text-danger');
    },
    submitHandler: function (e) {
        $('#show_loader').removeClass('fa-save');
        $('#show_loader').addClass('fa-spin fa-spinner');
        $("button[name='btn_add_appointment']").attr("disabled", "disabled").button('refresh');
        return true;
    }
})



// Validation

$('#add_appointment').validate({
    debug: false,
    rules: {
        mobile: {
            required: true
        },
        data: "required",
        email:" unique:users,email",
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
        email: "Este email já está a ser usado.",
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
        hora: "required",
        exists_client: "required",
    },
    messages: {
        mobile: {
            required: "Por favor, insere o nº de telefone."
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



"use strict";
var check_user_email_exits = $('#check_user_email_exits').val();
var check_nif_exits = $('#check_nif_exits').val();
var check_ndi_exits = $('#check_ndi_exits').val();

var token = $('#token-value').val();
var date_format_datepiker = $('#date_format_datepiker').val();

var FormControlsClient = {

    init: function () {
        var btn = $("form :submit");
        $.validator.addMethod("pwcheck", function (value) {
            return /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/.test(value) // consists of only these
        });

        $("#form_criarConta").validate({
            debug: false,
            rules: {
                tipo_cliente: "required",
                address: "required",
                country: "required",
               
                email: {
                    required: true,
                    email: true,
                    remote: {
                        async: false,
                        url: check_user_email_exits,
                        type: "post",
                        data: {
                            _token: function () {
                                return token;
                            },
                            email: function () {
                                return $("#email").val();
                            }
                        }
                    }
                },
                
                nif: {
                    required: true,
                    remote: {
                        async: false,
                        url: check_nif_exits,
                        type: "post",
                        data: {
                            _token: function () {
                                return token;
                            },
                            nif: function () {
                                return $("#nif").val();
                            }
                        }
                    }
                },
                
                ndi: {
                    
                    remote: {
                        async: false,
                        url: check_ndi_exits,
                        type: "post",
                        data: {
                            _token: function () {
                                return token;
                            },
                            ndi: function () {
                                return $("#ndi").val();
                            }
                        }
                    }
                },

                password: {
                    required: true,
                    pwcheck: true,
                    minlength: 8
                },
                cnm_password: {
                    required: true,
                    equalTo: "#password"
                },

                mobile: {
                    required: true,
                },
                alternate_no: {
                    required: false
                }
            },
            messages: {
                tipo_cliente: "Por favor, seleccione o tipo de cliente.",
                address: "Por favor, insere a morada.",
                country: "Por favor, seleccione o país.",
                
                email: {
                    required: "Por favor, insere o e-mail.",
                    email: "Por favor, insere um e-mail válido.",
                    remote: "O endereço de e-mail já existe."
                },
                
                nif: {
                    required: "Por favor, insere o nº de identificação fiscal.",
                    remote: "Este nº de identificação fiscal já existe."
                },
                
                ndi: {
                    
                    remote: "Este nº de documento de identificação já existe."
                },

                password: {
                    required: "Por favor, insere a palavra-passe.",
                    pwcheck: 'A palavra-passe deve ter no mínimo 8 caracteres, contendo pelo menos 1 letra minúscula, 1 letra maiúscula, 1 número e 1 caractere especial.',
                    minlength: "Por favor, insere uma palavra-passe com pelo menos 8 caracteres."

                },
                cnm_password: {
                    required: "Por favor, confirme a palavra-passe."
                },

                mobile: {
                    required: "Por favor, insere o nº de telefone."
                }
            },
            errorPlacement: function (error, element) {
                error.appendTo(element.parent()).addClass('text-danger');
            },
            submitHandler: function () {
                $('#show_loader').removeClass('fa-save');
                $('#show_loader').addClass('fa-spin fa-spinner');
                $("button[name='btn_add_user']").attr("disabled", "disabled").button('refresh');
                return true;
            }
        })
    }

};
jQuery(document).ready(function () {

    FormControlsClient.init();
    var provincia = $('#provincia');
    var municipio = $('#municipio_id');


    $("#mobile").mask("999 999 999");
    $('#alternate_no').mask("999 999 999");

     $("#mobile00").inputmask({
        mask: ['999999999', '999 999 999'],
        keepStatic: true
    });
       $("#mobile_alternativo").inputmask({
        mask: ['999999999', '999 999 999'],
        keepStatic: true
    });
    
    $('#nif').inputmask({
        mask: ['9999999999', '999999999aa999'],
        keepStatic: true
    });
    
    $('#ndi').inputmask({
        mask: ['aa999999','999999999aa999','9999999a99'],
        keepStatic: true
    });


    //set initial state.
    $("#change_court_chk").on("click", function () {
        if ($(this).is(":checked")) {

            var returnVal = this.value;
            if (returnVal == 'Yes') {
                $('#change_court_div').removeClass('hidden');
            }
        } else {
            $('#change_court_div').addClass('hidden');
        }
    });


    $('#tipo_cliente').on("change", function () {

        if (this.value == '2') {

            //$('.row_particular').show();
            
            
            $('.instituicao').hide();
            $("#instituicao").prop('required', false).val('');

            $('.f_name').show();
            $('#f_name').prop('required', true);

            $('.l_name').show();
            $('#l_name').prop('required', true);

            $('.estado_civil').show();
            
            $('.documento').show();
            $('#documento').prop('required', true);
            
            $('.ndi').show();
            $('#ndi').prop('required', true);


        } else {

            //$('.row_particular').hide();
            
            $('.instituicao').show();
            $("#instituicao").prop('required', true);

            $('.f_name').hide();
            $('#f_name').prop('required', false).val('');

            $('.l_name').hide();
            $('#l_name').prop('required', false).val('');

            $('.estado_civil').hide();
            
            $('.documento').hide();
            $('#documento').prop('required', false);
            
            $('.ndi').hide();
            $('#ndi').prop('required', false);
        }

    });
    
    
    $('#pais').on("change", function () {

        if (this.value == '6') {

            $('.provincia').show();
            $('.municipio').show();
           
        } else {
           
           $('.provincia').hide();
           $('.municipio').hide();
        }

    });

    provincia.select2({
        allowClear: true,
        language: "pt",
        ajax: {
            url: provincia.data('url'),
            data: function (params) {
                return {
                    search: params.term,
                    cod_pais: $(provincia.data('target')).val()
                };
            },
            dataType: 'json',
            processResults: function (data) {
                
             if ($("#pais").val() == '6'){
                return {
                    results: data.map(function (item) {
                        return {
                            id: item.id,
                            text: item.nome,
                            otherfield: item
                        };
                    })
                }
            }else{
                
                return false;
            }

            },
            cache: true,
            delay: 250
        },
        placeholder: 'Seleccionar'
        
    });

    municipio.select2({
        allowClear: true,
        language: "pt",
        ajax: {
            url: municipio.data('url'),
            data: function (params) {
                return {
                    search: params.term,
                    cod_provincia: $(municipio.data('target')).val()
                };
            },
            dataType: 'json',
            processResults: function (data) {
                if ($("#provincia").val() != '')
                {
                    return {
                        results: data.map(function (item) {
                            return {
                                id: item.id,
                                text: item.nome,
                                otherfield: item
                            };
                        })
                    }
                } else {
                    return false;
                }
            },
            cache: true,
            delay: 250
        },
        placeholder: 'Seleccionar'

    });

    $('.provincia').on('select2:select', function (e) {
        var el = $(this);
        var clearInput = el.data('clear').toString();
        $(clearInput).val(null).trigger('change');
    });

});

"use strict";
var checkExistRoute = $('#route-exist-check').val();
var token = $('#token-value').val();
var date_format_datepiker = $('#date_format_datepiker').val();
var language = $('#language').val();

var common_check_exist = $('#common_check_exist').val();

var check_user_email_exits = $('#check_user_email_exits').val();

var FormControlsClient = {

    init: function () {
        var btn = $("form :submit");
        $.validator.addMethod('telcheck', function (value) {
            return /^[9][^0|^6-8]{1}[0-9]{1}\s\d{3}\s\d{3}$/.test(value)
        });

        $.validator.addMethod('nifcheck', function (value) {
            return /\d{9}[a-zA-Z]{2}[0]\d{2}|\d{10}/.test(value);
        });
         $.validator.addMethod('mobilecheck', function (value) {
            return /\d{9}[a-zA-Z]{2}[0]\d{2}|\d{10}/.test(value);
        });

        $.validator.addMethod('ndicheck', function (value) {
            return /\d{9}[a-zA-Z]{2}[0]\d{2}|\d{7}[a-zA-Z]{1}\d{2}|[a-zA-Z]{1,2}\d{6}|\d{4}[a-zA-Z]{1}\d{2}/.test(value);
        });

        $.validator.addMethod("intlTelNumber", function (value, element) {
            return this.optional(element) || $(element).intlTelInput("isValidNumber");
        }, "Por favor, insere um nº de telemóvel válido.");

        $("#add_client").validate({
            debug: false,
            rules: {
                tipo_cliente: "required",
                address: "required",
                country: "required",

                nif: {

                    remote: {
                        async: false,
                        url: common_check_exist,
                        type: "post",
                        data: {
                            _token: function () {
                                return token
                            },
                            form_field: function () {
                                return $("#nif").val();
                            },
                            id: function () {
                                return $("#id").val();
                            },
                            db_field: function () {
                                return 'nif';
                            },
                            table: function () {
                                return 'cliente';
                            },
                            condition_form_field: function () {
                                return $("#nif").val();
                            },
                            condition_db_field: function () {
                                return 'nif';
                            }
                        }
                    }
                },

                ndi: {

                    ndicheck: true,
                    remote: {
                        async: false,
                        url: common_check_exist,
                        type: "post",
                        data: {
                            _token: function () {
                                return token
                            },
                            form_field: function () {
                                return $("#ndi").val();
                            },
                            id: function () {
                                return $("#id").val();
                            },
                            db_field: function () {
                                return 'ndi';
                            },
                            table: function () {
                                return 'documento';
                            },
                            condition_form_field: function () {
                                return $("#ndi").val();
                            },
                            condition_db_field: function () {
                                return 'ndi';
                            }
                        }
                    }
                },

                email: {
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
                mobile: {
                    required: true
                    //intlTelNumber: true
                },
                alternate_no: {
                    required: false
                }
            },
            messages: {
                tipo_cliente: "Por favor, seleccione o tipo de cliente.",
                address: "Por favor, insere a morada.",
                country: "Por favor, seleccione o país.",

                nif: {

                    remote: "Este nº de identificação fiscal já existe."
                },

                ndi: {

                    ndicheck: "Por favor, insere um nº de documento válido",
                    remote: "Este nº de documento de identificação já existe."
                },

                email: {
                    email: "Por favor, insere um e-mail válido.",
                    remote: "O endereço de e-mail já existe."
                },
                mobile: {
                    required: "Por favor, insere o telemóvel."
                    //telcheck: 'Por favor, insere um nº de telemóvel válido.'
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
    
     $("select").on("select2:close", function (e) {  
        $(this).valid(); 
    });

    //$('#lb_nif').html('Nº de Identificação Fiscal');

//    $("#mobile").mask("999 999 999");

    //$('#mobile').intlTelInput();

    var utils = $('#utils').val();


    var input = document.querySelector("#mobile");
    intlTelInput(input, {
        initialCountry: "auto",
        geoIpLookup: function (callback) {
            $.get("https://ipinfo.io", function () {}, "jsonp").always(function (resp) {
                var countryCode = (resp && resp.country) ? resp.country : "";
                callback(countryCode);
            });
        },
        utilsScript: utils
    });

    var input = document.querySelector("#mobile00");
    intlTelInput(input, {
        initialCountry: "auto",
        geoIpLookup: function (callback) {
            $.get("https://ipinfo.io", function () {}, "jsonp").always(function (resp) {
                var countryCode = (resp && resp.country) ? resp.country : "";
                callback(countryCode);
            });
        },
        utilsScript: utils
    });

    // here, the index maps to the error code returned from getValidationError - see readme
    var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];


    var alternate_no = document.querySelector('#alternate_no');
    if (alternate_no) {
        var itiAlternate = intlTelInput(alternate_no, {
            initialCountry: "ao",
            utilsScript: utils
        });
        
        // Forçar a definição do país após inicialização
        setTimeout(function() {
            itiAlternate.setCountry("ao");
        }, 100);
    }

    //$('#alternate_no').mask("999 999 999");

    $('#nif').inputmask({
        mask: ['9999999999', '999999999aa999'],
        keepStatic: true
    });

    
    $('#ndi').inputmask({
        mask: ['aa999999', 'a999999', '9999a99', '999999999aa999', '9999999a99'],
        keepStatic: true
    });

    $('#ddvdoc').datepicker({
        format: date_format_datepiker,
        language: language,
        autoclose: "close",
        todayHighlight: true,
        clearBtn: true
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

    $('.two').css('display', 'none');

    $('input[type=radio][name=type]').on("change", function () {

        if (this.value == 'single') {
            $('.one').show();
            $('.two').hide();

        } else if (this.value == 'multiple') {
            $('.two').show();
            $('.one').hide();
        }

    });

    $('#documento').on('change', function () {

        if (this.value == 1 || this.value == 2)
        {
            $('#lb_nif').html('Nº de Identificação Fiscal <span class="text-danger">*</span>');
            $('#nif').prop('required', true);
        } else {

            $('#lb_nif').html('Nº de Identificação Fiscal');
            $('#nif').prop('required', false);
        }
    });

    // Garantir que o evento seja registrado após o DOM estar pronto
    setTimeout(function() {
        $('#tipo_cliente').off('change').on("change", function () {
            console.log('Tipo cliente changed:', this.value);
        
        if (this.value == '2') {
            // Pessoa singular
            $('.f_name').show();
            $('#f_name').prop('required', true);

            $('.l_name').show();
            $('#l_name').prop('required', true);

            $('.instituicao').hide();
            $('#instituicao').prop('required', false).val('');

            $('.documento').show();
            $('#documento').prop('required', true);

            $('.ndi').show();
            $('#ndi').prop('required', true);

            $('.ddvdoc').show();

            $('.estado_civil').show();

            $('.regime_casamento').hide();

            $('#lb_nif').html('Nº de Identificação Fiscal');
            $('#nif').prop('required', false);

        } else {
            // Pessoa coletiva
            $('.f_name').hide();
            $('#f_name').prop('required', false).val('');

            $('.l_name').hide();
            $('#l_name').prop('required', false).val('');

            $('.instituicao').show();
            $('#instituicao').prop('required', true);

            $('.estado_civil').hide();
            $('.regime_casamento').hide();

            $('.documento').hide();
            $('#documento').prop('required', false).val('');

            $('.ndi').hide();
            $('#ndi').prop('required', false).val('');

            $('.ddvdoc').hide();

            $('#lb_nif').html('Nº de Identificação Fiscal <span class="text-danger">*</span>');
            $('#nif').prop('required', true);
        }
        });
        
        // Trigger inicial se já houver valor selecionado
        if ($('#tipo_cliente').val()) {
            $('#tipo_cliente').trigger('change');
        }
    }, 100);

    $('#estado_civil').on('change', function () {

        if (this.value == 'C') {

            $('.regime_casamento').show();
        } else {
            $('.regime_casamento').hide();
        }
    });


    $('#country').on("change", function () {

        if (this.value == '6') {

            $('.provincia').show();
            $('.municipio').show();

        } else {

            $('.provincia').hide();
            $('.municipio').hide();
        }

    });

    $('.repeater').repeater({
        initEmpty: false,
        defaultValues: {
            'text-input': 'foo'
        },
        show: function () {
            $(this).slideDown();
            var id = $(this).find('[type="text"]').attr('id');
            var label = $(this).find('label');
            label.attr('for', id);
            $(this).addClass('fade-in-info').slideDown();
        },
        hide: function (deleteElement) {
            if (confirm('Pretende eliminar este elemento?')) {
                $(this).slideUp(deleteElement);
            }
        },
        isFirstItemUndeletable: false
    });

});

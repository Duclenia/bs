"use strict";
var checkExistRoute = $('#route-exist-check').val();
var token = $('#token-value').val();
var FormControlsProfile = {

    init: function () {
        var btn = $("form :submit");
        $("#add_user").validate({
            // ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
            rules: {

                f_name: "required",
                l_name: "required",
                mobile: {
                    required: true
                },
                address: "required",
                country: "required",
                state: "required",
                city_id: "required",
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: checkExistRoute,
                        type: "post",
                        data: {
                            _token: function () {
                                return token;
                            },
                            email: function () {
                                return $("#email").val();
                            },
                            id: function () {
                                return $("#id").val();
                            }
                        }
                    }
                },

            },
            messages: {
                
                f_name: "Por favor, insere o primeiro nome.",
                l_name: "Por favor, insere o sobrenome.",
                email: {
                    required: "Por favor, insere o endereço de e-mail.",
                    email: "Por favor, insere um endereço de e-mail válido.",
                    remote: "O endereço de e-mail já existe."
                },
                mobile: {
                    required: "Por favor, insere o nº de telefone."
                },
                address: "Por favor, insere o endereço.",
                country: "Por favor, seleccione o país.",
                state: "Por favor, seleccione o estado / província.",
                city_id: "Por favor, seleccione a cidade.",
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
    FormControlsProfile.init();
    
    var date_format_datepiker = $('#date_format_datepiker').val();

//    $("#mobile").mask("999 999 999");
//    $('#alternate_no').mask("999 999 999");
    
    $('#ddn').datepicker({
        format: date_format_datepiker,
        language: 'pt',
        autoclose: "close",
        endDate: '0d',
        todayHighlight: true,
        clearBtn: true
    });
    
    
    $('#tipo_cliente').on("change", function () {

        if (this.value == 2) {

            //$('.row_particular').css('display', 'block');

            $('.genero').show();
            //$('.estado_civil').show();
            $('.ddn').show();
            $('.nome_pai').show();
            $('.nome_mae').show();

        } else {

            //$('.row_particular').css('display', 'none');

            $('.genero').hide();
            //$('.estado_civil').hide();
            $('.ddn').hide();
            $('.nome_pai').hide();
            $('.nome_mae').hide();
        }

    });

});

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
                zip_code: {
                    required: true,
                    minlength: 6,
                    maxlength: 6,
                    number: true
                },
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
                username: {
                    required: "Por favor, insere o nome de utilizador.",
                    remote: "O nome de utilizador já existe."
                },
                f_name: "Por favor, insere o primeiro nome.",
                l_name: "Por favor, insere o sobrenome.",
                email: {
                    required: "Por favor, insere o e-mail.",
                    email: "Por favor, insere um e-mail válido.",
                    remote: "O endereço de e-mail já existe."
                },
                mobile: {
                    required: "Por favor, insere o telefone."
                },
                address: "Por favor, insere o endereço.",
                zip_code: {
                    required: "Please enter zip code.",
                    minlength: "zip code must be 6 digit.",
                    maxlength: "zip code must be 6 digit.",
                    number: "please enter digit 0-9.",
                },
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

});

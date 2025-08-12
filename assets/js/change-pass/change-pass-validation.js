"use strict";
var checkExistRoute = $('#common_check_exist').val();
var token = $('#token-value').val();
var FormControlsClient = {

    init: function () {
        var btn = $("form :submit");
        $.validator.addMethod("pwcheck", function (value) {
            return /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/.test(value); // consists of only these
        });

        $("#change_password").validate({
            rules: {
                old: "required",
                new: {
                    required: true,
                    pwcheck: true,
                    minlength: 8
                },
                confirm: {
                    required: true,
                    minlength: 8,
                    equalTo: "#new"
                }
            },
            messages: {

                old: "Por favor, insere a palavra-passe actual.",
                new: {
                    required: "Por favor, insere a nova palavra-passe.",
                    pwcheck: 'A palavra-passe deve ter no mínimo 8 caracteres, formada por pelo menos 1 letra minúscula, 1 letra maiúscula, 1 número e 1 caractere especial.',
                    minlength: "A palavra-passe deve ter no mínimo 8 caracteres."
                },
                confirm: {
                    required: "Por favor, confirma a palavra-passe.",
                    minlength: "password must be at least 8 characters long.",
                    equalTo: "Confirm password does not match to password."

                },
            },
            errorPlacement: function (error, element) {
                error.appendTo(element.parent()).addClass('text-danger');
            },
            submitHandler: function () {

                $("button[name='btn_add_change']").attr("disabled", "disabled").button('refresh');
                return true;
            }
        });
    }

};
jQuery(document).ready(function () {
    FormControlsClient.init();

});

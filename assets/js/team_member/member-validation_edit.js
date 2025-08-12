// "use strict";

var check_user_email_exits = $('#check_user_email_exits').val();
var token = $('#token-value').val();
var date_format_datepiker = $('#date_format_datepiker').val();

var FormControlsClient = {

    init: function () {
        var btn = $("form :submit");
        $.validator.addMethod("pwcheck", function (value) {
            return /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/.test(value) // consists of only these
        });
        $("#add_user").validate({
            rules: {
                f_name: "required",
                l_name: "required",
                email: {
                    required: true,
                    email: true,
                    remote: {
                        // async: false,
                        url: check_user_email_exits,
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
                
                password: {
                    required: true,
                    pwcheck: true,
                    minlength: 8
                },
                cnm_password: {
                    required: true,
                    equalTo: "#password",

                }
                
            },
            messages: {
                
                f_name: "Por favor, insere o primeiro nome.",
                l_name: "Por favor, insere o sobrenome.",
                email: {
                    required: "Por favor, insere o endereço de e-mail.",
                    email: "Por favor, insere um endereço de e-mail válido.",
                    remote: "O endereço de e-mail já existe."
                },
                
                password: {
                    required: "Por favor, insere a palavra-passe.",
                    pwcheck: 'Password must be minimum 8 characters.password must contain at least 1 lowercase, 1 Uppercase, 1 numeric and 1 special character.',
                    minlength: "Por favor, insere uma palavra-passe com 8 caracteres ou mais."

                },
                cnm_password: {
                    required: "Por favor, confirme a palavra-passe.",

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
        });
    }

};
jQuery(document).ready(function () {
    
    FormControlsClient.init();
    
    $('#ddn').datepicker({
        format: date_format_datepiker,
        language: 'pt',
        autoclose: "close",
        endDate: '0d',
        todayHighlight: true,
        clearBtn: true
    });

    $(".chk").hide();
    $('#chk_pass').on('click', function (ev) {
        $(this).is(':checked') ? $(".chk").show() : $(".chk").hide();
    });

    $("#role").select2({
        allowClear: true,
        placeholder: 'Seleccionar função',
        // multiple:true
    });

    $uploadCrop = $('#upload-demo').croppie({
        enableExif: true,
        viewport: {
            width: 200,
            height: 200,
            type: 'circle'
        },
        boundary: {
            width: 300,
            height: 300
        }
    });

    $("#upload-demo").hide();

    var fileTypes = ['jpg', 'jpeg', 'png'];

    $('#upload').on('change', function () {

        var reader = new FileReader();
        if (this.files[0].size > 5242880) { // 2 mb for bytes.

            message.fire({
                type: 'error',
                title: 'Erro',
                text: 'O tamanho do arquivo não deve ser superior a 5 MB'
            });
            return false;
        }

        reader.onload = function (e) {
            result = e.target.result;
            arrTarget = result.split(';');
            tipo = arrTarget[0];

            if (tipo == 'data:image/jpeg' || tipo == 'data:image/png') {
                $("#upload-demo").show();
                $("#upload_img").show();
                $('#upload-demo-i').hide();
                $('#crop_image').hide();
                $('#demo_profile').hide();
                $('#remove_crop').hide();
                //$('#cancel_img').show();
                $uploadCrop.croppie('bind', {
                    url: e.target.result

                }).then(function () {
                    console.log('jQuery bind complete');
                });
            } else {
                message.fire({
                    type: 'error',
                    title: 'Erro',
                    text: 'Aceite apenas imagens .jpg .png',
                });

            }
        }
        reader.readAsDataURL(this.files[0]);
    });

    $('#cancel_img').on('click', function () {

        $("#upload-demo").hide();
        $("#upload_img").hide();
        $('#upload-demo-i').show();
        $('#crop_image').show();
        $('#demo_profile').show();
        $('#remove_crop').show();
    });
    $('#upload-result').on('click', function (ev) {
        $uploadCrop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function (resp) {

            $('#imagebase64').val(resp);

        });
    });

});

$(document).ready(function () {
    $("#role").select2({
        allowClear: true,
        placeholder: 'Seleccionar função'

    });

});



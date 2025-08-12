"use strict";
var token = $('#token-value').val();
var check_bairro_exist = $('#common_check_exist').val();
var FormControlsClient = {

    init: function () {
        var btn = $("form :submit");
        $("#tagForm").validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
            rules: {
                
                bairro: {
                    required: true,
                    remote: {
                        async: false,
                        url: check_bairro_exist,
                        type: "post",
                        data: {
                            _token: function () {
                                return token;
                            },
                            bairro: function () {
                                return $("#bairro").val();
                            }
                        }
                    }
                }
            },
            messages: {
                
                bairro: {
                    required: "Por favor, insere o nome do bairro",
                    remote: "Este bairro já existe."
                }
            },
            errorPlacement: function (error, element) {
                error.appendTo(element.parent()).addClass('text-danger');
            },
            submitHandler: function (e) {

                $("#cl").removeClass('ik ik-check-circle').addClass('fa fa-spinner fa-spin');
                var formData = new FormData($("#tagForm")[0]);
                var url = $("#tagForm").attr('action');

                $.ajax({
                    url: url,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function (data) {
                        $("#addtag").modal('hide');
                        $("#tagDataTable").dataTable().api().ajax.reload();
                        message.fire({
                            type: 'success',
                            title: 'Sucesso',
                            text: data.message
                        });
                    },
                    error: function (xhr, status, error) {
                        /* Act on the event */
                        if (xhr.status === 422) {

                            var errors = xhr.responseJSON.errors;
                            errorsHtml = '<div class="alert alert-danger"><ul>';
                            $.each(errors, function (key, value) {
                                console.log(value[0]);
                                errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                            });
                            errorsHtml += '</ul></di>';
                            $('#form-errors').html(errorsHtml);

                        }
                        $("#cl").removeClass('fa fa-spinner fa-spin').addClass('ik ik-check-circle');
                        message.fire({
                            type: 'error',
                            title: 'Erro',
                            text: 'something went wrong please try again !',
                        })
                    },
                });
            }
        })
    }

};
jQuery(document).ready(function () {
    FormControlsClient.init();
    
    $("#municipio").select2({
        allowClear: true,
        language: "pt",
        placeholder: 'Seleccionar',
        multiple: true
    });
});

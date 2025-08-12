"use strict";
var token = $('#token-value').val();
var common_check_exist = $('#common_check_exist').val();
var FormControlsClient = {

    init: function () {
        var btn = $("form :submit");
        $("#tagForm").validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
            rules: {

                judge_name: 'required',
                areaprocessual: 'required',
                tribunal: 'required',
                seccao: 'required'

            },
            messages: {
                judge_name: "Por favor, insere o nome do juiz",
                areaprocessual: 'Por favor, seleccione a área processual.',
                tribunal: 'Por favor, seleccione o tribunal.',
                seccao: 'Por favor, seleccione a secção.'

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
                            title: 'Error',
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

    var tribunal = $('#tribunal');
    var seccao = $('#seccao');

    tribunal.select2({
        allowClear: true,
        language: "pt",
        ajax: {
            url: tribunal.data('url'),
            data: function (params) {
                return {
                    search: params.term,
                    id: $('#areaprocessual').val()
                };
            },
            dataType: 'json',
            processResults: function (tribunais) {
                if ($("#areaprocessual").val() != '')
                {
                    return {
                        results: tribunais.map(function (item) {
                            return {
                                id: item.id,
                                text: item.nome,
                                otherfield: item
                            };
                        }),
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


    seccao.select2({
        allowClear: true,
        language: "pt",
        ajax: {
            url: seccao.data('url'),
            data: function (params) {
                return {
                    search: params.term,
                    areaprocessual: $('#areaprocessual').val(),
                    tribunal: $('#tribunal').val()
                };
            },
            dataType: 'json',
            processResults: function (seccao) {
                if ($("#areaprocessual").val() != '' && $("#tribunal").val() != '')
                {
                    return {
                        results: seccao.map(function (item) {
                            return {
                                id: item.id,
                                text: item.nome,
                                otherfield: item
                            };
                        }),
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

    $('#areaprocessual , #tribunal').on('select2:select', function (e) {
        var el = $(this);
        var clearInput = el.data('clear').toString();
        $(clearInput).val(null).trigger('change');
    });

});

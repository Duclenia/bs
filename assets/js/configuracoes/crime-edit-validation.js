"use strict";
var token = $('#token-value').val();
var check_tipo_crime_exist = $('#common_check_exist').val();
var FormControlsClient = {

    init: function () {
        var btn = $("form :submit");
        $("#tagForm").validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
            rules: {

                crime: {
                    required: true,
                    remote: {
                        async: false,
                        url: check_tipo_crime_exist,
                        type: "post",
                        data: {
                            _token: function () {
                                return token
                            },
                            tipo_crime: function () {
                                return $("#crime").val();
                            },
                            id: function () {
                                return $("#id").val();
                            }
                        }
                    }
                }
            },
            messages: {
                
                crime: {
                    required: "Por favor, insere o tipo de crime",
                    remote: "Este tipo de crime já existe."
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
                        });
                    },
                });
            }
        })
    }

};
jQuery(document).ready(function () {
    FormControlsClient.init();
    
    var crimEnquad = $('#crime_enquad');
    var crimSubEnquad = $('#crime_sub_enquad');
    
    crimEnquad.select2({
        allowClear: true,
        language: "pt",
        ajax: {
            url: crimEnquad.data('url'),
            data: function (params) {
                return {
                    search: params.term,
                    id: $(crimEnquad.data('target')).val()
                };
            },
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: data.map(function (item) {
                        return {
                            id: item.id,
                            text: item.designacao,
                            otherfield: item
                        };
                    }),
                }
            },
            cache: true,
            delay: 250
        },
        placeholder: 'Seleccionar'
    });
    
    
    crimSubEnquad.select2({
        allowClear: true,
        language: "pt",
        ajax: {
            url: crimSubEnquad.data('url'),
            data: function (params) {
                return {
                    search: params.term,
                    id: $(crimSubEnquad.data('target')).val()
                };
            },
            dataType: 'json',
            processResults: function (data) {
                if ($("#crime_enquad").val() != '')
                {
                    return {
                        results: data.map(function (item) {
                            return {
                                id: item.id,
                                text: item.designacao,
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
    
    $('#crime_enquad').on('select2:select', function (e) {
        var el = $(this);
        var clearInput = el.data('clear').toString();
        $(clearInput).val(null).trigger('change');
    });

});

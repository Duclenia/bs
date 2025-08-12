"use strict";
var token = $('#token-value').val();
var common_check_exist = $('#common_check_exist').val();
var FormControlsClient = {

    init: function () {
        var btn = $("form :submit");
        $("#mail_setup").validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
            rules: {
                favicon: {
                    accept: "image/jpg,image/jpeg,image/png",
                    filesize: 5242880,
                },
                logo: {
                    accept: "image/jpg,image/jpeg,image/png",
                    filesize: 5242880,
                }
            },
            messages: {
                favicon: {
                    accept: "accept only jpg,jpeg,png image",
                },
                logo: {
                    accept: "accept only jpg,jpeg,png image",
                }


            },
            errorPlacement: function (error, element) {
                error.appendTo(element.parent()).addClass('text-danger');

                if ($(element).is('input[type=file]')) {

                    if (element.attr("name") == "favicon") {
                        error.insertAfter(".valfavicon");
                    }

                    if (element.attr("name") == "logo") {
                        error.insertAfter(".vallogo");
                    }
                }
            },
            submitHandler: function (e) {
                $('#show_loader').removeClass('fa-save');
                $('#show_loader').addClass('fa-spin fa-spinner');
                $("button[name='btn_add_smtp']").attr("disabled", "disabled").button('refresh');
                return true;
            }
        })
    }

};
jQuery(document).ready(function () {
    FormControlsClient.init();
    $("#timezone").select2();
    
    var provincia = $('#provincia');
    var municipio = $('#municipio_id');
    var bairro = $('#bairro_id');

    $.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param)
    }, 'File size must be less than 5Mb');

    $("#favicon").checkImageSize({
        minWidth: 16,
        minHeight: 16,
        maxWidth: 16,
        maxHeight: 16,
        showError: true,
        ignoreError: false

    });


    $("#logo").checkImageSize({
        minWidth: 230,
        minHeight: 46,
        maxWidth: 230,
        maxHeight: 46,
        showError: true,
        ignoreError: false

    });
    
    provincia.select2({

        allowClear: true,
        language: "pt",
        ajax: {
            url: provincia.data('url'),
            data: function (params) {
                return {
                    search: params.term,
                    id: 6
                };
            },
            dataType: 'json',
            processResults: function (data) {

                return {
                    results: data.map(function (item) {
                        return {
                            id: item.id,
                            text: item.nome,
                            otherfield: item
                        };
                    })
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
                    id: $('#provincia').val()
                };
            },
            dataType: 'json',
            processResults: function (data) {
                if ($('#provincia').val() != '')
                {
                    return {
                        results: data.map(function (item) {
                            return {
                                id: item.id,
                                text: item.nome,
                                otherfield: item,
                            };
                        })
                    };
                } else {
                    return false;
                }
            },
            cache: true,
            delay: 250
        },
        placeholder: 'Seleccionar'
                // minimumInputLength: 1,
    });


    bairro.select2({

        allowClear: true,
        language: "pt",
        ajax: {
            url: bairro.data('url'),
            data: function (params) {
                return {
                    search: params.term,
                    cod_municipio: $('#municipio_id').val()
                };
            },
            dataType: 'json',
            processResults: function (data) {
                if ($('#municipio_id').val() != '')
                {
                    return {
                        results: data.map(function (item) {
                            return {
                                id: item.id,
                                text: item.nome,
                                otherfield: item,
                            };
                        })
                    };
                } else {
                    return false;
                }
            },
            cache: true,
            delay: 250
        },
        placeholder: 'Seleccionar'
    });

    $('#provincia').on('select2:select', function (e) {
        var el = $(this);
        var clearInput = el.data('clear').toString();
        $(clearInput).val(null).trigger('change');
    });

});

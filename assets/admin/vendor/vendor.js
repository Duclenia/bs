"use strict";
var token = $('#token-value').val();
var common_check_exist = $('#common_check_exist').val();
var FormControlsClient = {
    
    init: function () {
        
        $('#add_vendor').validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
            rules: {

                tipo_fornecedor: "required",

                nif: {
                    required: true,
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
                                return 'fornecedor';
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
                
                mobile: "required"
            },
            messages: {

                tipo_fornecedor: "Por favor, seleccione o tipo de fornecedor.",

                nif: {
                    required: "Por favor, insere o nº de identificação fiscal",
                    remote: "Este nº de identificação fiscal já existe."
                },
                
                mobile: "Por favor, insere o nº de telefone"
            },

            errorPlacement: function (error, element) {
                error.appendTo(element.parent()).addClass('text-danger');
            },
            submitHandler: function (e) {
                $('#show_loader').removeClass('fa-save');
                $('#show_loader').addClass('fa-spin fa-spinner');
                $("button[name='btn_add_client']").attr("disabled", "disabled").button('refresh');
                return true;
            }
        })
    }
};

// Get state & city

function getState(id) {

    if (id == "") {
        $('#provincia').html('');
    } else {
        $('#provincia').html('');
        $('#municipio_id').html('');
        $('#provincia').prepend($('<option></option>').html('Loading...'));
    }

    if (id != '') {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        });
        $.ajax({
            url: "{{ url('getStateByCountry')}}",
            method: "POST",
            data: {
                id: id
            },
            success: function (result) {
                if (result.errors)
                {
                    $('.alert-danger').html('');
                } else
                {
                    $('#states').html(result);
                }
            }
        });
    }
}


function getCity(id) {


    if (id == "") {
        $('#cities').html('');
    } else {
        $('#cities').html('');
        $('#cities').prepend($('<option></option>').html('Loading...'));
    }
    if (id != '') {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        });
        $.ajax({
            url: "{{ url('getCitiesByState')}}",
            method: "POST",
            data: {
                id: id
            },
            success: function (result) {
                if (result.errors)
                {
                    $('.alert-danger').html('');
                } else
                {
                    $('#cities').html(result);
                }
            }
        });
    }
}

$(document).ready(function () {
    
    FormControlsClient.init();

    $("#mobile").mask("999 999 999");
    $('#alternate_no').mask("999 999 999");

    var provincia = $('#provincia');
    var municipio = $('#municipio_id');
    var bairro = $('#bairro_id');

    $('#nif').inputmask({
        mask: ['9999999999', '999999999aa999'],
        keepStatic: true
    });

    $('#tipo_fornecedor').on("change", function () {

        if (this.value == 'F') {

            $('.company_name').show();
            $('#company_name').prop('required', true);

            $('.f_name').hide();
            $("#f_name").prop('required', false).val('');

            $('.l_name').hide();
            $('#l_name').prop('required', false).val('');


        } else if (this.value == 'P') {

            $('.f_name').show();
            $("#f_name").prop('required', true);

            $('.l_name').show();
            $('#l_name').prop('required', true);

            $('.company_name').hide();
            $('#company_name').prop('required', false).val('');
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
                            otherfield: item,
                        };
                    }),
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
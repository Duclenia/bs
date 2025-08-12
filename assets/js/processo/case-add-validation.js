"use strict";
var select2Case = $('#select2Case').val();
var date_format_datepiker = $('#date_format_datepiker').val();
var getCaseSubTypes = $('#getCaseSubType').val();
var getCourts = $('#getCourt').val();
var token = $('#token-value').val();
var common_check_exist = $('#common_check_exist').val();

var FormControlsClient = {

    init: function () {
        var btn = $("form :submit");
        $("#add_case").validate({
            rules: {
                no_processo: {

                    remote: {
                        async: false,
                        url: common_check_exist,
                        type: "post",
                        data: {
                            _token: function () {
                                return token
                            },
                            form_field: function () {
                                return $("#no_processo").val();
                            },
                            id: function () {
                                return $("#id").val();
                            },
                            db_field: function () {
                                return 'no_processo';
                            },
                            table: function () {
                                return 'processo';
                            },
                            condition_form_field: function () {
                                return $("#no_processo").val();
                            },
                            condition_db_field: function () {
                                return 'no_processo';
                            }
                        }
                    }

                },
                areaprocessual: 'required',
                tipo_processo: 'required',
                orgao: 'required',
                estado: 'required',
                client_name: "required"
            },
            messages: {

                no_processo: {

                    remote: "Este nº de processo já existe."
                },
                areaprocessual: 'Por favor, seleccione a natureza do processo.',
                tipo_processo: 'Por favor, seleccione a forma do processo ou o tipo de acção.',
                orgao: 'Por favor, seleccione o órgão',
                estado: 'Por favor, seleccione o estado do processo',
                client_name: "Por favor, seleccione o cliente."
            },
            errorPlacement: function (error, element) {
                error.appendTo(element.parent()).addClass('text-danger');
            },

            submitHandler: function () {
                $('#show_loader').removeClass('fa-save');
                $('#show_loader').addClass('fa-spin fa-spinner');
                $("button[name='btn_add_case']").attr("disabled", "disabled").button('refresh');
                return true;
            }
        })
    }

};
jQuery(document).ready(function () {
    FormControlsClient.init();

    $('#areaProcessual').on('change', function () {

        if (this.value < 4) {

            $('.tipo_processo').html('Forma do processo <span class="text-danger">*</span>');
        } else {

            $('.tipo_processo').html('Tipo de ac&ccedil;&atilde;o <span class="text-danger">*</span>');
        }

        if (this.value == 3) {

            $('.tipo_crime').show();
            $('#tipo_crime').prop('required', true);
        } else {

            $('.tipo_crime').hide();
            $('#tipo_crime').prop('required', false);
        }
    });

//    $('#areaProcessual, #orgao').on('change', function () {
//
//        var areaprocessual = $('#areaProcessual');
//        var orgao = $('#orgao');
//
////        if (areaprocessual.val() == '3') {
////
////            if (orgao.val() == 'Judicial') {
////
////                $('.row_estado_processo').show();
////            } else {
////
////                $('.row_estado_processo').hide();
////            }
////        }else{
////            
////            $('.row_estado_processo').hide();
////        }
//    });

//    $('#estadoprocesso').on('change', function () {
//
//        var areaprocessual = $('#areaProcessual');
//
//        if (this.value == '2' && areaprocessual.val() == '3') {
//
//            $('.instrutor').show();
//        } else {
//            $('.instrutor').hide();
//        }
//    });

    $('#orgao').on("change", function () {
        
        var areaprocessual = $('#areaProcessual');
        
        if (this.value == 'Judiciário') {

            //$('.row_particular').css('display', 'block');
            
//            if (areaprocessual.val() != '3') {
//                 $('.row_judiciario').css('display', 'block');
//             }else{
//                 $('.row_judiciario').css('display', 'none');
//             }

            $('.orgaojudiciario').show();
            $('#orgaojudiciario').prop('required', true);

            $('.tribunal').hide();
            $('.seccao').hide();
            $('.juiz').hide();
            
            $('.instrutor').show();
            $('.procurador').show();
            
            $('.escrivao').hide();
            $('.mandatario_judicial').hide();
            
            $('.orgaoextrajudicial').hide();
            $('#orgaoextrajudicial').prop('required', false);

        } else if (this.value == 'Judicial') {

            //$('.row_particular').css('display', 'none');
            
            $('.row_judiciario').css('display', 'none');

            $('.tribunal').show();

            $('.seccao').show();
            $('.juiz').show();
            
            $('.escrivao').show();
            $('.mandatario_judicial').hide();
            
            $('.instrutor').hide();
            $('.procurador').hide();

            $('.orgaojudiciario').hide();
            $('#orgaojudiciario').prop('required', false);
            
            $('.orgaoextrajudicial').hide();
            $('#orgaoextrajudicial').prop('required', false);
            
        }else if(this.value == 'Extrajudicial'){
            
            $('.row_judiciario').css('display', 'none');
            
            $('.orgaoextrajudicial').show();
            $('#orgaoextrajudicial').prop('required', true);
            
            $('.instrutor').hide();
            $('.procurador').hide();
            
            $('.mandatario_judicial').show();
            
            $('.orgaojudiciario').hide();
            $('#orgaojudiciario').prop('required', false);

            $('.tribunal').hide();
            $('.seccao').hide();
            $('.juiz').hide();
            $('.escrivao').hide();
        }
    });

    $('input[type=radio][name=position]').on('change', function () {
        if (this.value == 'Respondent') {
            $('.position_name').html('Petitioner Name');
            $('.position_advo').html('Petitioner Advocate');
        } else if (this.value == 'Petitioner') {
            $('.position_name').html('Respondent Name');
            $('.position_advo').html('Respondent Advocate');
        }
    });

    $("#assigned_to").select2({
        allowClear: true,
        language: "pt",
        placeholder: 'Seleccionar advogado(s)',
        multiple: true
    });

    $("#case_type").select2({
        allowClear: true,
        language: "pt",
        placeholder: 'Seleccionar tipo de processo'
    });

    $("#case_status").select2({
        allowClear: true,
        language: "pt",
        placeholder: 'Seleccionar estado do processo'
    });


    $("#court_name").select2({
        allowClear: true,
        language: "pt",
        placeholder: 'Seleccionar tribunal'
    });

    $("#client_name").select2({
        allowClear: true,
        language: "pt",
        placeholder: 'Seleccionar cliente'
    });


    $('.repeater').repeater({
        // (Optional)
        // start with an empty list of repeaters. Set your first (and only)
        // "data-repeater-item" with style="display:none;" and pass the
        // following configuration flag
        initEmpty: false,
        // (Optional)
        // "defaultValues" sets the values of added items.  The keys of
        // defaultValues refer to the value of the input's name attribute.
        // If a default value is not specified for an input, then it will
        // have its value cleared.
        defaultValues: {
            'text-input': 'foo'
        },
        // (Optional)
        // "show" is called just after an item is added.  The item is hidden
        // at this point.  If a show callback is not given the item will
        // have $(this).show() called on it.
        show: function () {
            $(this).slideDown();
//            var test = $('input[name=position]:checked').val();
//
//            if (test == 'Respondent') {
//
//                $('.position_name').html('Petitioner Name');
//                $('.position_advo').html('Petitioner Advocate');
//            } else if (test == 'Petitioner') {
//
//                $('.position_name').html('Respondent Name');
//                $('.position_advo').html('Respondent Advocate');
//            }

        },
        // (Optional)
        // "hide" is called when a user clicks on a data-repeater-delete
        // element.  The item is still visible.  "hide" is passed a function
        // as its first argument which will properly remove the item.
        // "hide" allows for a confirmation step, to send a delete request
        // to the server, etc.  If a hide callback is not given the item
        // will be deleted.
        hide: function (deleteElement) {
            if (confirm('Pretende eliminar este elemento?')) {
                $(this).slideUp(deleteElement);
            }
        },
        // (Optional)
        // You can use this if you need to manually re-index the list
        // for example if you are using a drag and drop library to reorder
        // list items.
        ready: function (setIndexes) {
            //$dragAndDrop.on('drop', setIndexes);
        },
        // (Optional)
        // Removes the delete button from the first list item,
        // defaults to false.
        isFirstItemUndeletable: true
    });


});

function isFloatsNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode != 46 && charCode > 31
        && (charCode < 48 || charCode > 57))
        return false;

    return true;
}

function getCaseSubType(id) {

    if (id == "") {
        $('#case_sub_type').html('');
    } else {
        $('#case_sub_type').html('');
        $('#case_sub_type').prepend($('<option></option>').html('Carregando...'));
    }
    if (id != '') {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        });
        $.ajax({
            url: getCaseSubTypes,
            method: "POST",
            data: {id: id},
            success: function (result) {
                if (result.errors) {
                    $('.alert-danger').html('');
                } else {
                    $('#case_sub_type').html(result);
                }
            }
        });
    }
}

function getCourt(id) {

    if (id == "") {
        $('#court_name').html('');
    } else {
        $('#court_name').html('');
        $('#court_name').prepend($('<option></option>').html('Carregando...'));
    }

    if (id != '') {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        });
        $.ajax({
            url: getCourts,
            method: "POST",
            data: {id: id},
            success: function (result) {
                if (result.errors) {
                    $('.alert-danger').html('');
                } else {
                    $('#court_name').html(result);
                }
            }
        });
    }
}

$(document).ready(function () {

    var tipoprocesso = $('#tipo_processo');
    var tribunal = $('#tribunal');
    var intervdesignacao = $('#qualidade');
    var seccao = $('#seccao');
    var juiz = $('#juiz');
    var areaprocessual = $('#areaProcessual');
    var tipoCrime = $('#tipo_crime');
    var orgaojudiciario = $('#orgaojudiciario');
    var estadoprocesso = $('#estadoprocesso');


    areaprocessual.select2({
        allowClear: true,
        language: "pt",
        ajax: {
            url: areaprocessual.data('url'),
            data: function (params) {
                return {
                    search: params.term,
                    id: $(areaprocessual.data('target')).val()
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

    tipoprocesso.select2({
        allowClear: true,
        language: "pt",
        ajax: {
            url: tipoprocesso.data('url'),
            data: function (params) {
                return {
                    search: params.term,
                    id: $('#areaProcessual').val()
                };
            },
            dataType: 'json',
            processResults: function (tiposProcessos) {
                if ($("#areaProcessual").val() != '')
                {
                    return {
                        results: tiposProcessos.map(function (item) {
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


    estadoprocesso.select2({
        allowClear: true,
        language: "pt",
        ajax: {
            url: estadoprocesso.data('url'),
            data: function (params) {
                return {
                    search: params.term,
                    id: $('#areaProcessual').val()
                };
            },
            dataType: 'json',
            processResults: function (estadosprocesso) {
                if ($("#areaProcessual").val() != '')
                {
                    return {
                        results: estadosprocesso.map(function (item) {
                            return {
                                id: item.id,
                                text: item.estado,
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


    orgaojudiciario.select2({
        allowClear: true,
        language: "pt",
        ajax: {
            url: orgaojudiciario.data('url'),
            data: function (params) {
                return {
                    search: params.term,
                    id: $(orgaojudiciario.data('target')).val()
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

    tribunal.select2({
        allowClear: true,
        language: "pt",
        ajax: {
            url: tribunal.data('url'),
            data: function (params) {
                return {
                    search: params.term,
                    id: $('#areaProcessual').val()
                };
            },
            dataType: 'json',
            processResults: function (tribunais) {
                if ($("#areaProcessual").val() != '')
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


    intervdesignacao.select2({
        allowClear: true,
        language: "pt",
        ajax: {
            url: intervdesignacao.data('url'),
            data: function (params) {
                return {
                    search: params.term,
                    id: $('#areaProcessual').val()
                };
            },
            dataType: 'json',
            processResults: function (intervDesignacao) {
                if ($("#areaProcessual").val() != '')
                {
                    return {
                        results: intervDesignacao.map(function (item) {
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


    seccao.select2({
        allowClear: true,
        language: "pt",
        ajax: {
            url: seccao.data('url'),
            data: function (params) {
                return {
                    search: params.term,
                    areaprocessual: $('#areaProcessual').val(),
                    tribunal: $('#tribunal').val()
                };
            },
            dataType: 'json',
            processResults: function (seccao) {
                if ($("#areaProcessual").val() != '' && $("#tribunal").val() != '')
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


    juiz.select2({
        allowClear: true,
        language: "pt",
        ajax: {
            url: juiz.data('url'),
            data: function (params) {
                return {
                    search: params.term,
                    areaprocessual: $('#areaProcessual').val(),
                    tribunal: $('#tribunal').val(),
                    seccao: $('#seccao').val()
                };
            },
            dataType: 'json',
            processResults: function (juiz) {
                if ($("#areaProcessual").val() != '' && $("#tribunal").val() != '' && $('#seccao').val() != '')
                {
                    return {
                        results: juiz.map(function (item) {
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

    $('#areaProcessual, #tribunal, #seccao').on('select2:select', function (e) {
        var el = $(this);
        var clearInput = el.data('clear').toString();
        $(clearInput).val(null).trigger('change');
    });

});

"use strict";
var select2Case = $('#select2Case').val();
var date_format_datepiker = $('#date_format_datepiker').val();
var FormControlsClient = {

    init: function () {
        var btn = $("form :submit");
        $('#add_client').validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
            rules: {
                task_subject: "required",
                start_date: "required",
                hora_inicio: "required",
                project_status_id: "required",
                priority: "required",
                'assigned_to[]': {
                    required: true
                }

            },
            messages: {
                task_subject: "Por favor, insere o assunto.",
                start_date: "Por favor, insere a data de realização.",
                hora_inicio: "Por favor, insere a hora de início.",
                project_status_id: "Por favor, seleccione o estado.",
                priority: "Por favor, seleccione a prioridade.",
                'assigned_to[]': {
                    required: "Por favor, seleccione o responsável.",
                }

            },
            errorPlacement: function (error, element) {
                error.appendTo(element.parent()).addClass('text-danger');
            },
            submitHandler: function (e) {
                $('#show_loader').removeClass('fa-save');
                $('#show_loader').addClass('fa-spin fa-spinner');
                $("button[name='btn_add_user']").attr("disabled", "disabled").button('refresh');
                return true;
            }
        })
    }

};
jQuery(document).ready(function () {
    FormControlsClient.init();
    
    
    $('#hora_inicio').datetimepicker({
        format: 'HH:mm'
        
    });
    
    
    $('#hora_termino').datetimepicker({
        format: 'HH:mm'
    });

    $('#related_id').select2({
        ajax: {
            url: select2Case,
            data: function (params) {
                return {
                    search: params.term,
                    //page: params.page || 1
                };
            },
            dataType: 'json',
            processResults: function (data) {
                console.log(data);
                //data.page = data.page || 1;
                return {
                    results: data.items.map(function (item) {
                        
                       var clientName = (item.tipo == 2) ? (item.nome + ' '+ item.sobrenome) : item.instituicao;
                        return {
                            id: item.id,
                            text: clientName,
                            otherfield: item
                        };
                    }),
                    pagination: {
                        more: data.pagination
                    }
                }
            },
            //cache: true,
            delay: 50
        },
        placeholder: 'Seleccionar',
        language: 'pt',
        // minimumInputLength: 1,
        templateResult: getfName,
    });

    $("#project_status_id").select2({
        allowClear: true,
        language: 'pt',
        placeholder: 'Seleccionar estado'
    });

    $("#priority").select2({
        allowClear: true,
        language: 'pt',
        placeholder: 'Seleccionar prioridade'
    });

    $("#assigned_to").select2({
        allowClear: true,
        language: 'pt',
        placeholder: 'Seleccionar responsáveis',
        multiple: true
    });

    $("#related").select2({
        allowClear: true,
        language: 'pt',
        placeholder: 'Nothing selected',
    });

    $('.dateFrom').datepicker({
        format: date_format_datepiker,
        language: 'pt',
        autoclose: true,
        startDate: '0d',
        todayHighlight: true
    });
    
    $('#related').on('change', function () {
        var optionSelected = $(this).find("option:selected");
        var label_name = optionSelected.val();

        if (label_name == "case") {
            $('.task_selection').removeClass('hide');
        } else {
            $('.task_selection').addClass('hide');
        }
    });


});

function getfName(data) {
    if (!data.id) {
        return data.text;
    }
    data = data.otherfield;
    
    var clientName = (data.tipo == 2) ? data.nome + ' ' + data.sobrenome : data.instituicao;
    
    var $html = $("<p style='margin-bottom: 0;'><b>" + clientName + "</b> <br> " + data.no_interno + "</p>");
    return $html;
}

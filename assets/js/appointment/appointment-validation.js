"use strict";
var checkExistRoute = $('#common_check_exist').val();
var token = $('#token-value').val();
var date_format_datepiker = $('#date_format_datepiker').val();
var getMobilenos = $('#getMobileno').val();


var FormControlsClient = {

    init: function () {
        var btn = $("form :submit");

    }

};
jQuery(document).ready(function () {
    FormControlsClient.init();

    //$("#mobile").mask("999 999 999");

    // $('.exists').addClass("hidden");

    $('#date').datepicker({
        format: date_format_datepiker,
        language: 'pt',
        autoclose: "close",
        startDate: '0d',
        todayHighlight: true,
    });

    $('#time').datetimepicker({
        format: 'hh:mm A'
    });

    $('#date1').datepicker({
        format: date_format_datepiker,
        language: 'pt',
        autoclose: "close",
        startDate: '0d',
        todayHighlight: true,
    });

    $('#time1').datetimepicker({
        format: 'hh:mm A'
    });

    $('#exists_client').select2({
        placeholder: 'Seleccionar cliente'
    });
    $('#select_advogado').select2({
        placeholder: 'Seleccionar Advogado',
    });
    $('#select_advogado_exist').select2({
        placeholder: 'Seleccionar Advogado',
    });

    $('input[name="type"]').on('change', function () {
        if (this.value === 'exists') {
            // Mostrar cliente existente
            $('.exists').removeClass('hidden').show();
            $('.new').addClass('hidden').hide();

            $('.exists :input').prop('disabled', false);
            $('.new :input').prop('disabled', true);

            $("#exists_client").val('').select2({
                placeholder: 'Seleccionar cliente'
            });
            $('#select_advogado_exist').val('').select2({
                placeholder: 'Seleccionar Advogado',
                language: "pt",
            });
            $("#exists_client").val('').select2({
                placeholder: 'Seleccionar cliente'
            });
            $('#steps-indicator').hide();
            $('#existing-client-form, .exists-buttons').show();
            $('#new-client-step2').hide();

        } else if (this.value === 'new') {
            // Mostrar novo cliente
            $('.exists').addClass('hidden').hide();
            $('.new').removeClass('hidden').show();

            $('.exists :input').prop('disabled', true);
            $('.new :input').prop('disabled', false);

            $('#mobile').val('');
            $('#steps-indicator').show();
            $('#existing-client-form, .exists-buttons').hide();
            $('#new-client-step2').hide();
        }
    });



});



function getMobileno(id) {

    if (id != '') {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        });
        $.ajax({
            url: getMobilenos,
            method: "POST",
            data: { id: id },
            success: function (data) {
                if (data.utilizador) {

                    $('#email').val(data.utilizador.email).prop('readonly', true);
                }
                else {
                    $('#email').val('').prop('readonly', false);
                }
                $('#mobile').val(data.telefone).prop('readonly', true);

            }
        });
    }

}


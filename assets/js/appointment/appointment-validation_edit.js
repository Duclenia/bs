"use strict";
var checkExistRoute = $('#common_check_exist').val();
var token = $('#token-value').val();
var date_format_datepiker = $('#date_format_datepiker').val();
var getMobilenos = $('#getMobileno').val();
var type_chk = $('#type_chk').val();


var FormControlsClient = {

    init: function () {
        var btn = $("form :submit");
    }

};
jQuery(document).ready(function () {
    FormControlsClient.init();

    $('#date').datepicker({
        format: date_format_datepiker,
        language: 'pt',
        autoclose: "close",
        startDate: '0d',
        todayHighlight: true
    });

    $('#time').datetimepicker({
        format: 'hh:mm A'
    });

    $("#mobile").mask("999 999 999");

    $('#exists_client').select2({
        placeholder: 'Seleccionar cliente',
        language: "pt",
    });

     $('#select_advogado').select2({
        placeholder: 'Seleccionar Advogado',
    });

    $('input[type=radio][name=type]').on('change', function () {

        if (this.value == 'exists') {

            $('.exists').removeClass("hidden");
            $('.new').addClass("hidden");

            $("#exists_client").val('').select2({
                placeholder: 'Seleccionar cliente',
                language: "pt",
            });
            $('#select_advogado').val('').select2({
                placeholder: 'Seleccionar Advogado',
                language: "pt",
            });

        } else if (this.value == 'new') {
            // alert("new");
            $('.exists').addClass("hidden");
            $('.new').removeClass("hidden");
            $('#mobile').val('').prop('disabled', false);

        }
    });


});


if (type_chk == 'exists') {
    $('.exists').removeClass("hidden");
    $('.new').addClass("hidden");
}

if (type_chk == 'new') {
    $('.exists').addClass("hidden");
    $('.new').removeClass("hidden");
}
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

                $('#mobile').val(data.telefone).prop('readonly', true);
            }
        });
    }
}


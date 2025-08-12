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

});


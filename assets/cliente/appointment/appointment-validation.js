"use strict";
var checkExistRoute = $('#common_check_exist').val();
var token = $('#token-value').val();
var date_format_datepiker = $('#date_format_datepiker').val();


var FormControlsClient = {

    init: function () {
        var btn = $("form :submit");

    }

};
jQuery(document).ready(function () {
    FormControlsClient.init();
    
    //$("#mobile").mask("999 999 999");

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

      $('#date1').datepicker({
        format: date_format_datepiker,
        language: 'pt',
        autoclose: "close",
        startDate: '0d',
        todayHighlight: true
    });
    

    $('#time1').datetimepicker({
        format: 'hh:mm A'
    });

});


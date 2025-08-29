"use strict";
var t;
var DatatableRemoteAjaxDemo = function () {


    var lsitDataInTable = function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        t = $('#horarioDataTable').DataTable({
            "processing": true,
            "serverSide": true,
            "stateSave": true,
            "lengthMenu": [10, 25, 50],
            "responsive": true,
            "oLanguage": { sProcessing: "<div class='loader-container'><div id='loader'></div></div>" },
            "width": 200,
            // "iDisplayLength": 2,
            "ajax": {
                "url": $('#horarioDataTable').attr('data-url'),
                "dataType": "json",
                "type": "POST",
                "data": function (d) {
                    return $.extend({}, d, {});
                }
            },
            "order": [
                [0, "asc"]
            ],
            "columns": [
                { "data": "id" },
                { "data": "day_of_week" },
                { "data": "day_off" },
                { "data": "time" },
                { "data": "interval_minutes" },
                { "data": "breaks" },
                { "data": "action" }

            ]
        });

    }

    //== Public Functions
    return {
        // public functions
        init: function () {
            lsitDataInTable();


        }
    };
}();
jQuery(document).ready(function () {
    DatatableRemoteAjaxDemo.init()
});



// Validation

$('#add_appointment').validate({   
        debug: false,
        //ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
            rules: {
                     data:"required",
                     hora:"required"
                },
        messages: {
           
                     data: "Por favor, insere a data.",
                     hora: "Por favor, insere a hora.",
        },
        errorPlacement: function (error, element) {            
            error.appendTo(element.parent()).addClass('text-danger');
        },
        submitHandler: function (e) {
          $('#show_loader').removeClass('fa-save');
               $('#show_loader').addClass('fa-spin fa-spinner');
               $("button[name='btn_add_appointment']").attr("disabled", "disabled").button('refresh');
            return true;
        }
    });



  
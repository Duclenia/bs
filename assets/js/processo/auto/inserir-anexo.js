var wrapper = $(".input_fields_wrap"); //Fields wrapper
var add_buttonR = $(".add_field_button"); //Add button ID
var x = 1; //initlal text box count

$(document).on('click', ".add_field_button", function (e) { //on add input button click

    e.preventDefault();

    x++; //text box increment
    $(wrapper).append(
            '<div style="margin-bottom:20px;">' +
            '<div class="form-group">' +
            '<label class="control-label col-md-4 col-sm-4 col-xs-4" for="descricao">Descri&ccedil;&atilde;o <span class="text-danger">*</span></label>' +
            '<div class="col-md-4 col-sm-4 col-xs-4">' +
            '<input type="text" class="form-control" name="descricao[]" id="descricao' + x + '" data-placeholder="Por favor, insere a descrição" required>' +
            '</div>' +
            ' </div>' +
            '<div class="form-group">' +
            '<label class="control-label col-md-4 col-sm-4 col-xs-4" for="auto">Anexar documento <span class="text-danger">*</span></label>' +
            '<div class="col-md-4 col-sm-4 col-xs-4">' +
            '<input type="file" class="form-control" name="autos[]" id="auto' + x + '" required accept="application/pdf">' +
            '<span id="msgErroAuto' + x + '" class="erro"></span>' +
            '</div>' +
            '<div class="input-group-btn">' +
            '<a href="#" id="removeanexo" title="Remover anexo" data-placement="right" data-toggle="tooltip" class="btn btn-danger remove_field"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>' + '</div>' +
            '</div><hr>' +
            ' </div>' +
            '</div>'); //add input box

});

$(wrapper).on("click", ".remove_field", function (e) { //user click on remove text
    e.preventDefault();
    $(this).parent('div').parent().parent().remove();
    x--;
});


var FormControlsClient = {

    init: function () {
        var btn = $("form :submit");
        
        $.validator.addMethod("pwcheck", function (value) {
            return /(\.pdf)$/i.test(value) // consists of only these
        });
        
        $('#add_docs').validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
            rules: {
                
                'descricao[]': {
                    required: true
                },
                
                'autos[]': {
                    required: true,
                    pwcheck: true
                },
                

            },
            messages: {
                
                'descricao[]': {
                    required: "Por favor, insere a descrição.",
                },
                
                'autos[]': {
                    required: "Por favor, anexa o documento",
                    pwcheck: 'Por favor selecione apenas ficheiros com a extensão .pdf'
                },

            },
            errorPlacement: function (error, element) {
                error.appendTo(element.parent()).addClass('text-danger');
            },
            submitHandler: function (e) {
//                $('#show_loader').removeClass('fa-save');
//                $('#show_loader').addClass('fa-spin fa-spinner');
//                $("button[name='btn_add_user']").attr("disabled", "disabled").button('refresh');
                return true;
            }
        })
    }

};

jQuery(document).ready(function () {
    FormControlsClient.init();
});


//$("#auto1").on('change', function (evt) {
//    var fileInput = document.getElementById('auto1');
//    var filePath = fileInput.value;
//    var allowedExtensions = /(\.pdf)$/i;
//    var filesize = this.files[0].size / 1024 / 1024;
//    if (!allowedExtensions.exec(filePath)) {
//        alert('Por favor selecione apenas ficheiros com a extensão .pdf');
//        fileInput.value = '';
//        return false;
//    }
//    if (filesize > 35) {
//        alert("O tamanho do ficheiro selecionado deve ser menor do que 25MB")
//        fileInput.value = '';
//        return false;
//    }
//
//})



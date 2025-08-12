<div id="modal_agenda" class="modal color fade"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-title text-center" id="myModalLabel"> <i class="fa fa-calendar-plus-o"></i> Agendar</h4>
            </div>

            <div class="modal-body">

                <form  id="form_agendar" action="{{url('cliente/agendar')}}"  method="post" class="form-horizontal">

                    {{ csrf_field() }}
                    
                    
                    <div class="form-group">
                        <label for="assunto" class="control-label col-xs-4">Assunto <font color="#FF0000">*</font></label>
                        <div class="col-xs-5">
                            <input type="text"  name="assunto" id="assunto"  class="form-control" required maxlength="50">

                        </div>

                    </div>
                    
                    <div class="form-group">
                        <label for="data" class="control-label col-xs-4">{{__('Date')}} <font color="#FF0000">*</font></label>
                        <div class="col-xs-5">
                            <input type="text"  name="data" id="data"  class="form-control" readonly="" required>

                        </div>

                    </div>

                    <div class="form-group">
                        <label for="hora" class="control-label col-xs-4">Hora <font color="#FF0000">*</font></label>
                        <div class="col-xs-5">
                            <input type="time"  name="hora" id="hora"  class="form-control" required>

                            <span id="msgErroOldPassword" class="erro"></span>

                        </div>

                    </div>
                    
                    <div class="form-group">
                        <label for="nota" class="control-label col-xs-4">Nota</label>
                        <div class="col-xs-5">
                            <textarea name="nota"  id="nota"  class="form-control"></textarea>

                        </div>

                    </div>

                </form>
            </div>

            <div class="modal-footer">
                
                <button type="submit" class="btn btn-primary" id="btn_agendar">Agendar</button>

                <button type="submit" id="btn_close_map" class="btn btn-default" data-dismiss="modal">{{__('Cancel')}}</button>

            </div>
        </div>
    </div> <!-- /.modal -->
</div>



<!-- modal mensagem de alteração de palavra-passe -->
<div class="modal fade t-modal color" id="msg-palavra-passe" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title text-center" id="myModalLabel">Sucesso!</h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>

            <div class="modal-body">

                {{ 'Palavra-passe alterada' }}

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>

            </div>
        </div>
    </div> <!-- /.modal -->
</div>
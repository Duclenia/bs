<div class="modal fade" id="addtag" role="dialog" aria-labelledby="addcategory" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <form action="{{ route('plano.store') }}" method="POST" id="tagForm" name="tagForm">
            @csrf()
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel2">Adicionar Plano</h4>
                </div>

                <div class="modal-body">
                    <div id="form-errors"></div>
                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="plano">Plano <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="plano" name="plano" required autocomplete="off">
                        </div>
                    </div>
                    
                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="valor_mensal">Valor mensal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="valor_mensal" name="valor_mensal" required autocomplete="off">
                        </div>
                    </div>
                    
                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="total_processo">Total de processos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="total_processo" name="total_processo" required autocomplete="off">
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="total_utilizador">Total de utilizadores <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="total_utilizador" name="total_utilizador" required autocomplete="off">
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                            class="ik ik-x"></i>{{__('Close')}}
                    </button>
                    <button type="submit" class="btn btn-success shadow"><i class=" fa fa-save ik ik-check-circle"
                                                                            id="cl">
                        </i> {{__('Save')}}
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
<input type="hidden" name="token-value"
       id="token-value"
       value="{{csrf_token()}}">

<input type="hidden" name="common_check_exist"
       id="common_check_exist"
       value="{{ route('plano_check_exist') }}">

<script src="{{asset('assets/js/plano/create.js')}}"></script>



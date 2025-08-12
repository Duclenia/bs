<div class="modal fade" id="addtag" role="dialog" aria-labelledby="addcategory" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <form action="{{ route('interv-designacao.store') }}" method="POST" id="tagForm" name="tagForm">
            @csrf()
            <div class="modal-content">


                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel2">Adicionar designa&ccedil;&atilde;o</h4>
                </div>


                <div class="modal-body">
                    <div id="form-errors"></div>
                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="designacao">Designa&ccedil;&atilde;o <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="designacao" name="designacao" required autocomplete="off">
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="areaprocessual">&Aacute;rea processual <span class="text-danger">*</span></label>
                            <select name="areaprocessual[]" multiple class="form-control" id="areaprocessual" required>
                                @foreach($areasprocessuais as $areaprocessual)
                                <option value="{{$areaprocessual->id}}">{{$areaprocessual->designacao}}</option>
                                @endforeach
                            </select>
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
       value="{{ url('common_check_exist') }}">

<script src="{{asset('assets/js/configuracoes/designacao-validation.js')}}"></script>

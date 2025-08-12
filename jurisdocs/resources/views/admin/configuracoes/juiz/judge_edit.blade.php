<div class="modal fade" id="addtag" role="dialog" aria-labelledby="addcategory" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <form action="{{route('juiz.update',$juiz->id)}}" method="POST" id="tagForm" name="tagForm">
            @csrf()
            <input type="hidden" id="id" name="id" value="{{$juiz->id ?? ''}}">

            @method('patch')
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel2">Editar Juiz</h4>
                </div>

                <div class="modal-body">
                    <div id="form-errors"></div>
                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="case_subtype">Juiz <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="judge_name" name="judge_name" required autocomplete="off"
                                   value="{{ $juiz->nome ?? '' }}">
                        </div>
                        
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="areaprocessual">&Aacute;rea processual <span class="text-danger">*</span></label>
                            <select name="areaprocessual" class="form-control" id="areaprocessual" required
                                    data-clear="#tribunal,#seccao"
                                    >
                                <option value="">Seleccionar</option>
                                @foreach($areasprocessuais as $areaprocessual)
                                <option value="{{$areaprocessual->id}}" @if($areaprocessual->id == $taps[0]->areaprocessual_id) selected @endif>{{$areaprocessual->designacao}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="tribunal">Tribunal <span class="text-danger">*</span></label>
                            <select name="tribunal" class="form-control" id="tribunal" required
                                    
                                    data-url="{{route('get.tribunal')}}"
                                    data-clear="#seccao"
                                    
                                    >
                                
                                <option value="{{$taps[0]->tribunal_id}}">{{$taps[0]->tribunal}}</option>
                                
                            </select>
                        </div>
                        
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="seccao">Sec&ccedil&atilde;o <span class="text-danger">*</span></label>
                            <select name="seccao" class="form-control" id="seccao" required data-url ="{{route('get.seccao')}}">
                                
                                <option value="{{$taps[0]->seccao_id}}">{{$taps[0]->seccao}}</option>
                                
                            </select>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                            class="ik ik-x"></i>{{__('Close')}}
                    </button>
                    <button type="submit" class="btn btn-success shadow"><i class="fa fa-save   ik ik-check-circle"
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

<script src="{{asset('assets/js/configuracoes/judge-validation.js')}}"></script>
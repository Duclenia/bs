<div class="modal fade" id="addtag" role="dialog" aria-labelledby="addcategory" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <form action="{{route('crime.update',$crime->id)}}" method="POST" id="tagForm" name="tagForm">
            @csrf()
            <input type="hidden" id="id" name="id" value="{{$crime->id ?? ''}}">

            @method('patch')
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel2">Editar tipo de crime</h4>
                </div>


                <div class="modal-body">
                    <div id="form-errors"></div>
                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="crime">Tipo de crime <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="crime" name="crime" required autocomplete="off"
                                   value="{{ $crime->designacao ?? '' }}">
                        </div>
                        
                    </div>
                    
                    
                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="artigo">Artigo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="artigo" name="artigo" autocomplete="off" required
                                   value="{{$crime->artigo ?? '' }}"
                                   >
                        </div>
                    </div>
                    
                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="crime_enquad">Enquadramento do crime <span class="text-danger">*</span></label>
                            <select class="form-control" id="crime_enquad" name="crime_enquad" required
                                    
                                    data-url="{{ route('get.crimEnquad') }}"
                                    data-clear="#crime_sub_enquad"
                                    
                                    >
                            
                                <option value="">Seleccionar</option>
                                
                                @if ($crime->crimEnquad)
                                 <option value="{{ $crime->crimEnquad->id }}"
                                    selected>{{ $crime->crimEnquad->designacao }}</option>
                               @endif
                                
                            </select>
                        </div>
                    </div>
            
                   <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="crime_sub_enquad">Sub-enquadramento do crime <span class="text-danger">*</span></label>
                            <select class="form-control" id="crime_sub_enquad" name="crime_sub_enquad" required
                                    
                                    data-url="{{ route('get.crimSubEnquad') }}"
                                    data-target="#crime_enquad"
                                    
                                    >
                            
                                <option value="">Seleccionar</option>
                                
                                @if ($crime->crimSubEnquad)
                                 <option value="{{ $crime->crimSubEnquad->id }}"
                                    selected>{{ $crime->crimSubEnquad->designacao }}</option>
                               @endif
                            
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
       value="{{ route('check_exist_tipo_crime') }}">

<script src="{{asset('assets/js/configuracoes/crime-edit-validation.js')}}"></script>
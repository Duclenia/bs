<div class="modal fade" id="addtag" role="dialog" aria-labelledby="addcategory" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <form action="{{ route('crime-sub-enquad.store') }}" method="POST" id="tagForm" name="tagForm">
            @csrf()
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel2">Adicionar Sub-enquadramento do crime</h4>
                </div>

                <div class="modal-body">

                    <div id="form-errors"></div>
                    
                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="crime_enquad">Enquandramento do crime <span class="text-danger">*</span></label>
                            <select class="form-control" id="crime_enquad" name="crime_enquad" required>
                                
                                <option value="">Seleccionar</option>
                                @foreach($crimesEnquad as $crimeEnquad)
                                 <option value="{{$crimeEnquad->id}}">{{$crimeEnquad->designacao}}</option>
                                @endforeach
                                
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="crime_sub_enquad">Sub-enquandramento do crime <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="crime_sub_enquad" name="crime_sub_enquad" autocomplete="off" required>
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

<script src="{{asset('assets/js/configuracoes/crime-sub-enquad-validation.js')}}"></script>





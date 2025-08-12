@extends('admin.layout.app')
@section('title',__('Appointment Edit'))

@section('content')

    <div class="page-title">
        <div class="title_left">
            <h3>Editar Agendamento</h3>
        </div>

        <div class="title_right">
            <div class="form-group pull-right top_search">
                <a href="{{ route('agenda.index') }}" class="btn btn-primary">{{__('Back')}}</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('component.error')
            <div class="x_panel">
                <div class="x_content">
                    <form id="add_appointment" name="add_appointment" role="form" method="POST"
                          action="{{route('agenda.update',$appointment->id)}}">
                        <input name="_method" type="hidden" value="PATCH">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="x_content">

                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif


                                <div class="row">
                                    <div class="form-group col-md-6">

                                        <input type="radio" id="test5" value="new" name="type"
                                               @if($appointment->type=="new") checked @endif>

                                        <b> Novo cliente </b>

                                    </div>

                                    <div class="form-group col-md-6">

                                        <input type="radio" id="test4" value="exists" name="type"
                                               @if($appointment->type=="exists") checked @endif>

                                        <b> Cliente existente </b>


                                    </div>
                                </div>
                                <br>
                                <div class="row exists">
                                    <div class="col-md-12">

                                        <div class="form-group">
                                            @if(count($client_list)>0)
                                                <label class="discount_text">Seleccionar cliente
                                                    <er class="rest">*</er>
                                                </label>
                                                <select class="form-control selct2-width-100" name="exists_client"
                                                        id="exists_client"
                                                        onchange="getMobileno(this.value);">
                                                    <option value="">Seleccionar cliente</option>
                                                    @foreach($client_list as $list)
                                                        <option value="{{ $list->id}}"
                                                                @if(!empty($appointment->cliente_id) && $appointment->cliente_id==$list->id)
                                                                selected @endif>{{ str_pad($list->id, 5, '0', STR_PAD_LEFT). ' - '.  $list->full_name}}</option>
                                                    @endforeach
                                                </select>
                                            @endif

                                        </div>
                                    </div>
                                </div>


                                <div class="row new">
                                    <div class="col-md-12 form-group">
                                        <label for="newclint_name">Nome do cliente <span
                                                    class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="new_client"
                                               name="new_client" autocomplete="off"
                                               value="{{old('new_client', $appointment->nome ?? null)}}">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    
                                    <div class="col-md-4 form-group">
                                        <label for="assunto">Assunto <span class="text-danger">*</span></label>
                                        <input type="text" name="assunto" class="form-control" id="assunto"
                                               autocomplete="off" maxlength="50" value="{{old('assunto', $appointment->assunto ?? null)}}" required>
                                    </div>
                                    
                                    <div class="col-md-4 form-group">
                                        <label for="mobile">Telefone <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="mobile" name="mobile"
                                               autocomplete="off" value="{{old('mobile', $appointment->telefone ?? null)}}">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="date">Data <span class="text-danger">*</span></label>

                                        <input type="text" class="form-control" id="date" name="date"
                                               value="{{ date($date_format_laravel, strtotime($appointment->data)) }}">
                                    </div>

                                </div>
                                <div class="row">
                                    
                                    <div class="col-md-4 form-group">
                                        <label for="time">Hora <span class="text-danger">*</span></label>

                                        <input type="text" class="form-control" id="time" name="time"
                                               value="{{old('time', $appointment->hora ?? null) }}">

                                    </div>
                                    
                                    <div class="col-md-8 form-group">
                                        <label for="note">Nota</label>
                                        <textarea type="text" class="form-control" id="note"
                                                  name="note">{{old('note', $appointment->observacao ?? null)}}</textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    <br>
                                    <a href="{{ route('agenda.index') }}" class="btn btn-danger">{{__('Cancel')}}</a>

                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save" id="show_loader"></i>&nbsp;{{__('Save')}}
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="date_format_datepiker"
           id="date_format_datepiker"
           value="{{$date_format_datepiker}}">

    <input type="hidden" name="getMobileno"
           id="getMobileno"
           value="{{ route('getMobileno') }}">

    <input type="hidden" name="type_chk"
           id="type_chk"
           value="{{$appointment->type}}">

@endsection

@push('js')
    <script src="{{asset('assets/admin/appointment/appointment.js') }}"></script>
    <script src="{{asset('assets/js/appointment/appointment-validation_edit.js')}}"></script>
    <script src="{{asset('assets/js/masked-input/masked-input.min.js')}}"></script>

@endpush

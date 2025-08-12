@extends('admin.layout.app')
@section('title','Agendar')
@section('content')
<div class="page-title">
    <div class="title_left">
        <h3>Criar Agenda</h3>
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
                      action="{{route('agenda.store')}}" enctype="multipart/form-data" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="x_content">

                            <div class="row">
                                <div class="form-group col-md-6">

                                    <input type="radio" id="test5" value="new" name="type" checked>

                                    <b> Novo Cliente
                                    </b>

                                </div>

                                <div class="form-group col-md-6">

                                    <input type="radio" id="test4" value="exists" name="type">

                                    <b> Cliente existente
                                    </b>

                                </div>
                            </div>
                            <br>

                            <div class="row exists">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        @if(!empty($client_list) && count($client_list)>0)
                                        <label class="discount_text">Seleccionar cliente
                                            <er class="rest">*</er>
                                        </label>
                                        <select class="form-control selct2-width-100" name="exists_client"
                                                id="exists_client"
                                                onchange="getMobileno(this.value);">
                                            <option value="">Seleccionar cliente</option>
                                            @foreach($client_list as $list)
                                             <option value="{{ $list->id}}">{{ str_pad($list->id, 5, '0', STR_PAD_LEFT). ' - '.  $list->full_name}}</option>
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
                                            <input type="text" class="form-control" id="new_client" name="new_client" value="{{old('new_client')}}" autocomplete="off">
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-4 form-group">
                                    <label for="assunto">Assunto <span class="text-danger">*</span></label>
                                    <input type="text" name="assunto" class="form-control" value="{{old('assunto')}}" id="assunto" autocomplete="off" maxlength="50">
                                </div>

                                <div class="col-md-4 form-group">
                                    <label for="mobile">Telefone <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="mobile" name="mobile" value="{{old('mobile')}}" autocomplete="off">
                                </div>

                                <div class="col-md-4 form-group">
                                    <label for="date">{{__('Date')}} <span class="text-danger">*</span></label>

                                    <input type="text" class="form-control" id="date" name="date" value="{{old('date')}}">

                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-4 form-group">
                                    <label for="time">{{__('Time')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="time" name="time" value="{{old('time')}}" required>
                                </div>

                                <div class="col-md-8 form-group">
                                    <label for="note">{{__('Note')}}</label>
                                    <textarea type="text" class="form-control" id="note" name="note">{{old('note')}}</textarea>
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
@endsection

@push('js')
<script src="{{asset('assets/admin/appointment/appointment.js') }}"></script>
<script src="{{asset('assets/js/masked-input/masked-input.min.js')}}"></script>
<script src="{{asset('assets/js/appointment/appointment-validation.js')}}"></script>
@endpush

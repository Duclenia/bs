@extends('admin.layout.app')
@section('title',__('Add Task'))
@section('content')

<div class="page-title">
    <div class="title_left">
        <h3>{{__('Add Task')}} </h3>
    </div>
    
    <div class="title_right">
        <div class="form-group pull-right top_search">
            <a href="{{route('tarefas.index')}}" class="btn btn-primary">{{__('Back')}}</a>

        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        @include('component.error')
        <div class="x_panel">
            <form id="add_client" name="add_client" role="form" method="POST" autocomplete="nope"
                  action="{{route('tarefas.store')}}">
                {{ csrf_field() }}
                <div class="x_content">

                    <div class="row">

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="fullname">Assunto <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="task_subject" name="task_subject" value="{{old('task_subject')}}" autocomplete="off">
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="start_date">Data de realiza&ccedil;&atilde;o <span class="text-danger">*</span></label>
                            <input type="text" class="form-control dateFrom" id="start_date" name="start_date" value="{{old('start_date')}}">
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="hora_inicio">Hora de in&iacute;cio <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="hora_inicio" name="hora_inicio" value="{{old('hora_inicio')}}" required>
                        </div>

                    </div>

                    <div class="row">
                        
                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="hora_fim">Hora de t&eacute;rmino</label>
                            <input type="text" name="hora_termino" class="form-control" value="{{old('hora_termino')}}" id="hora_termino">
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="project_status_id">Estado <span class="text-danger">*</span></label>
                            <select class="form-control" id="project_status_id" name="project_status_id">
                                <option value="">Seleccionar estado</option>
                                @foreach(getTaskStatusList()  as $key=>$val)
                                <option value="{{$key}}">{{$val}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="priority">{{__('Priority')}} <span class="text-danger">*</span></label>
                            <select name="priority" class="form-control" id="priority">
                                <option value="" selected disabled>Seleccionar prioridade</option>
                                @foreach(getTaskPriorityList() as $key=>$val)
                                <option value="{{$key}}">{{$val}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row">
                        
                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="fullname">Respons&aacute;vel <span class="text-danger">*</span></label>

                            <select multiple class="form-control" id="assigned_to" name="assigned_to[]">
                                <option value="">Seleccionar</option>
                                @foreach($users as $key=>$val)
                                <option value="{{$val->id}}">{{mb_strtoupper($val->nome.' '.$val->sobrenome)}}</option>
                                @endforeach
                            </select>

                        </div>
                        
                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="fullname">Relacionada a</label>
                            <select class="form-control selct2-width-100" id="related" name="related">
                                <option value="">Seleccionar</option>
                                <option value="case">Processo</option>
                                <option value="other">Outra</option>
                            </select>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group task_selection hide">
                            <label for="related_id">Processo</label>
                            <select name="related_id" class="form-control selct2-width-100" id="related_id">
                                <option value="">Seleccionar</option>
                            </select>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="task_description">{{__('Description')}}</label>
                            <textarea class="form-control" id="task_description" name="task_description">{{old('task_description')}}</textarea>

                        </div>
                    </div>

                    <div class="form-group pull-right">
                        <div class="col-md-12 col-sm-6 col-xs-12">
                            <a class="btn btn-danger" href="{{ route('tarefas.index') }}">{{__('Cancel')}}</a>
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

<input type="hidden" name="select2Case"
       id="select2Case"
       value="{{route('select2Case') }}">

<input type="hidden" name="date_format_datepiker"
       id="date_format_datepiker"
       value="{{$date_format_datepiker}}">
@endsection

@push('js')

<script src="{{asset('assets/js/tarefa/task-validation.js')}}"></script>
@endpush

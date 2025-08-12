@extends('admin.layout.app')
@section('title',__('Tasks'))
@section('content')

<div class="">
    @component('component.heading' , [
    'page_title' => __('Tasks'),
    'action' => route('tarefas.create') ,
    'text' => __('Add Task'),
    'permission' => auth()->user()->can('task_add')
    ])
    @endcomponent

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">

                <div class="x_content">

                    <table id="clientDataTable" class="table" data-url="{{ route('task.list') }}">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>{{__('Task')}}</th>
                                <th>Relacionada a</th>
                                <th>Data de realiza&ccedil;&atilde;o</th>
                                <th>Hora de in&iacute;cio</th>
                                <th>Hora de t&eacute;rmino</th>
                                <th>Respons&aacute;veis</th>
                                <th>Estado</th>
                                <th>{{__('Priority')}}</th>
                                <th data-orderable="false" class="text-center">{{__('Action')}}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{asset('assets/js/tarefa/task-datatable.js')}}"></script>
@endpush

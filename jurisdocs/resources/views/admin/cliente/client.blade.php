@extends('admin.layout.app')
@section('title',__('Clients'))
@section('content')
    <div class="">
       @component('component.heading' , [
       'page_title' => __('Clients'),
       'action' => route('clients.create') ,
       'text' => __('Add Client'),
       'permission' => auth()->user()->can('client_add')
        ])
        @endcomponent

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                    <div class="x_content">
                        <table id="clientDataTable" class="table" data-url="{{ route('clients.list') }}">
                            <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>{{__('Name')}}</th>
                                <th>N&ordm; de registo</th>
                                <th width="11%">{{__('Mobile')}}</th>
                                <th width="5%" data-orderable="false">Processos</th>
                                <th width="5%" data-orderable="false">Estado</th>
                                <th width="10%" data-orderable="false" class="text-center">{{__('Action')}}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

   <div id="load-modal"></div>
@endsection

@push('js')
    <script src="{{asset('assets/js/cliente/client-datatable.js')}}"></script>
@endpush

@extends('admin.layout.app')
@section('title',__('Services'))
@section('content')
    <div class="">

        @component('component.modal_heading',
             [
             'page_title' => __('Service'),
             'action'     => route("servico.create"),
             'model_title'=>'Novo serviço',
             'modal_id'=>'#addtag',
             'permission' => auth()->user()->can('service_add')
             ] )
            Service
        @endcomponent


        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                    <div class="x_content">

                        <table id="serviceDataTable" class="table" data-url="{{ route('service.list') }}">
                            <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th width="5%" data-orderable="false">Estado</th>
                                <th width="2%" data-orderable="false">{{__('Action')}}</th>
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

    <script src="{{asset('assets/js/servico/service-datatable.js')}}"></script>

@endpush

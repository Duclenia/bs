@extends('admin.layout.app')
@section('title','Estado do processo')
@section('content')
    <div class="">

        @component('component.modal_heading',
             [
             'page_title' => 'Estado do processo',
             'action'=>route("case-status.create"),
             'model_title'=>'Create Case Status',
             'modal_id'=>'#addtag',
             'permission' => auth()->user()->can('case_status_add')
             ] )
            Status
        @endcomponent


        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                    <div class="x_content">

                        <table id="tagDataTable" class="table" data-url="{{ route('case.status.list') }}"
                              >
                            <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Estado do processo</th>
                                <th width="5%" data-orderable="false">Status</th>
                                <th width="2%" data-orderable="false" class="text-center">Ac&ccedil;&atilde;o</th>
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
    <script src="{{asset('assets/js/configuracoes/case-statue-datatable.js')}}"></script>
@endpush

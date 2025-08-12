@extends('admin.layout.app')
@section('title',__('Role'))
@section('content')
    <div class="">

        @component('component.modal_heading',
             [
             'page_title' => __('Role'),
             'action'=>route("funcao.create"),
             'model_title'=>'Registar função',
             'modal_id'=>'#addtag',
              'permission' => '1'
             ] )
             Fun&ccedil;&atilde;o
        @endcomponent


        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                    <div class="x_content">

                        <table id="roleDataTable" class="table" data-url="{{ route('role.list') }}" >
                            <thead>
                            <tr>
                                <th width="5%">{{__('No')}}</th>
                                <th>{{__('Role')}}</th>
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
    <script src="{{asset('assets/js/funcao/role-datatable.js')}}"></script>
@endpush

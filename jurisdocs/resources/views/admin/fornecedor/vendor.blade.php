@extends('admin.layout.app')
@section('title','Fornecedor')
@section('content')
    <div class="">
 @component('component.heading' , [

  'page_title' => 'Fornecedor',
  'action' => route('fornecedor.create') ,
  'text' => 'Novo Fornecedor',
   'permission' => auth()->user()->can('vendor_add')
   ])
        @endcomponent


        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                    <div class="x_content">

                        <table id="Vendordatatable" class="table"
                               data-url="{{ route('vendor.list') }}">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th width="40%">Nome</th>
                                <th width="40%">Telefone</th>
                                <th>NIF</th>
                                <th data-orderable="false">Estado</th>
                                <th data-orderable="false">Ac&ccedil;&atilde;o</th>
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
    <script src="{{asset('assets/js/vendor/vendor-datatable.js')}}"></script>
@endpush

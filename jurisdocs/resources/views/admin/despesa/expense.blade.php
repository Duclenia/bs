@extends('admin.layout.app')
@section('title','Despesa')
@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>Despesa</h3>
        </div>

        <div class="title_right">
            <div class="form-group pull-right top_search">

                @can('expense_add')
                    <a href="{{ url('admin/expense-create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Adicionar
                        Despesa</a>
                @endcan

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">

                <div class="x_content">

                    <table id="ExpenseDatatable" class="table" >
                        <thead>
                        <tr>

                            <th width="3%;">No</th>
                            <th width="15%">Factura No</th>
                            <th width="30%">Fornecedor</th>
                            <th width="10%">Total</th>
                            <th width="10%">Paid</th>
                            <th width="15%">Due</th>
                            <th width="5%">Estado</th>
                            <th width="5%;">Ac&ccedil;&atilde;o</th>

                        </tr>
                        </thead>


                    </table>
                </div>
            </div>
        </div>

    </div>
    <div id="load-modal"></div>

    <input type="hidden" name="token-value"
           id="token-value"
           value="{{csrf_token()}}">

    <input type="hidden" name="expense-list"
           id="expense-list"
           value="{{ url('admin/expense-list') }}">

@endsection

@push('js')
    <script src="{{asset('assets/js/despesa/expense-datatable.js')}}"></script>
@endpush


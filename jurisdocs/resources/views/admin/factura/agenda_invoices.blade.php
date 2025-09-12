@extends('admin.layout.app')
@section('title', __('Invoice'))
@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>{{ __('Invoice') }}</h3>
        </div>

        <div class="title_right">
            <div class="form-group pull-right top_search">
                @if ($agenda_info)
                    <p class="text-muted">Reunião de
                        {{ date('d/m/Y H:i', strtotime($agenda_info->data . ' ' . $agenda_info->hora)) }}</p>
                @endif

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">

                <div class="x_content">
                    <table id="client_list" class="table">
                        <thead>
                            <tr>
                                <th width="3%;">No</th>
                                <th width="15%">N&ordm; da factura</th>
                                <th width="30%">{{ __('Client') }}</th>
                                <th width="10%">Total</th>
                                <th width="10%">Pago</th>
                                <th width="15%">Data limite de pagamento</th>
                                <th width="5%">Estado</th>
                                <th width="5%;">{{ __('Action') }}</th>
                            </tr>
                        </thead>

                    </table>

                </div>
            </div>
        </div>

    </div>
    <div id="load-modal"></div>

    <!-- /page content end  -->
    <div class="modal fade" id="modal-common" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content" id="show_modal">

            </div>
        </div>
    </div>

    <input type="hidden" name="token-value" id="token-value" value="{{ csrf_token() }}">
    <input type="hidden" name="invoice-list" id="invoice-list" value="{{ url('admin/invoice-list') }}">
    <input type="hidden" value="{{ $agenda_id }}" id="agenda_id" name="agenda_id">

@endsection

@push('js')
    <script src="{{ asset('assets/js/factura/invoice-datatable.js') }}"></script>
@endpush

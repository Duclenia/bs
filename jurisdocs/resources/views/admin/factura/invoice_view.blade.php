@extends('admin.layout.app')
@section('title',__('Invoice View'))
@push('stylesheets')

@endpush

@section('content')
    <!-- /page content start -->
    <div class="x_panel">
        <div id="content">
            <form id="add_invoice" name="add_invoice" role="form" method="POST" action="{{url('admin/add_invoice')}}"
                  autocomplete="off">
                {{ csrf_field() }}
                <div class="col-md-12">
                    <div class="row">
                        <!-- Section Right Part Start -->
                        <!-- Col-md-6 Start -->
                        <div class="col-md-12">
                            <div class="right-part-bg-all">
                                <div class="ctzn-usrs">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="clearfix">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <a target="_blank"
                                               href="{{url('admin/create-Invoice-view-detail/'.encrypt($invoice->id).'/print')}}"
                                               title="Imprimir factura"><i class="fa fa-print fa-2x pull-right"
                                                                        aria-hidden="true"></i></a>
                                            <div class="clearfix">
                                            </div>
                                        </div>
                                    </div>
                                    <h1 class="text-center">{{__('Invoice')}} </h1>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="invoice-title">
                                                <h3 class="pull-right">Factura No: {{ $invoice_no ?? ''}}</h3>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <address>
                                                        <div class="margin-top-30">

                                                            <div class="discount_text">

                                                                <strong>Factura de:</strong>
                                                                {{ucfirst($advocate_client->primeiro_nome)." ".$advocate_client->nome_meio." ".$advocate_client->sobrenome }}

                                                                <br>
                                                                <strong>{{__('Address')}}: </strong>{{ $advocate_client->endereco.', '.$city}}

                                                                <br>
                                                                <strong>Telefone: </strong> {{$advocate_client->telefone}}
                                                    </address>
                                                </div>
                                                <div class="col-xs-6 text-right">
                                                    <address>
                                                        <strong>Data da factura:</strong> {{ $inv_date ?? ''}}<br>

                                                        <strong>Data de vencimento da factura:</strong> {{ $due_date ?? ''}}<br>

                                                    </address>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">

                                                </div>
                                                <div class="col-xs-6 text-right">

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-default">

                                                <div class="panel-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-condensed">
                                                            <thead>
                                                            <tr>
                                                                <td class="text-center"><strong>No</strong></td>
                                                                <td class="text-left"><strong>{{__('Service')}}</strong></td>
                                                                <td class="text-left"><strong>{{__('Description')}}</strong></td>
                                                                <td class="text-center" width="10%">
                                                                    <strong>{{__('Quantity')}}</strong></td>
                                                                <td class="text-center" width="10%">
                                                                    <strong>{{__('Rate')}}</strong></td>
                                                                <td class="text-center" width="10%">
                                                                    <strong>Valor</strong></td>
                                                            </tr>
                                                            </thead>
                                                            <tbody>

                                                            @php $i=1; @endphp
                                                            @if(!empty($iteam) && count($iteam)>0)
                                                                @foreach($iteam as $key=>$value)
                                                                    <tr>
                                                                        <td class="text-center">{{$i}}</td>
                                                                        <td class="text-left">{{$value['service_name']}}</td>
                                                                        <td class="text-left">{{ $value['custom_items_name'] }}</td>

                                                                        <td class="text-center">{{ $value['custom_items_qty'] }}</td>
                                                                        <td class="text-center">{{ $value['item_rate'] }}</td>
                                                                        <td class="text-center">{{$value['custom_items_amount']}}</td>
                                                                    </tr>
                                                                    @php $i++; @endphp
                                                                @endforeach

                                                            @endif

                                                            </tbody>
                                                        </table>

                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        @php if($invoice->remarks!=''){ @endphp
                                        <div class="col-sm-7 col-md-7">
                                            <div class="contct-info">
                                                <div class="form-group">
                                                    <label class="discount_text"> {{__('Note')}}
                                                    </label>
                                                    <p>{{$invoice->remarks ?? ''}}</p>
                                                </div>
                                            </div>
                                        </div>
                                        @php }  @endphp
                                        <div class="pull-right col-md-4 invoice-margin-right-32">

                                            <table class="table row-border dataTable no-footer" id="tab_logic_total">

                                                <tr>
                                                    <td width="75%" align="right"><b
                                                                class="font-size-expense-17">{{__('SubTotal')}}</b></td>
                                                    <td width="25%" align="right"><b
                                                                class="font-size-expense-17">{{$subTotal}}</b></td>
                                                </tr>

                                                <tr>
                                                    <td width="75%" align="right"><b
                                                                class="font-size-expense-17">{{__('Tax')}}</b>
                                                    </td>
                                                    <td width="25%" align="right"><b
                                                                class="font-size-expense-17">{{$tax_amount}}</b></td>
                                                </tr>


                                                <tr>
                                                    <td align="right"><b class="font-size-expense-17">{{__('Total')}}</b></td>
                                                    <td align="right"><b
                                                                class="font-size-expense-17">{{ $total_amount }}</b>
                                                    </td>
                                                </tr>

                                            </table>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <!-- /page content end  -->
@endsection

@push('scripts')

@endpush

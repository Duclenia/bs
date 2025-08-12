@extends('admin.layout.app')
@section('title','Detalhes do fornecedor')
@section('content')
<div>

    <div class="page-title">
        <div class="title_left">
            <h3>Nome do fornecedor : <span>{{$name}}</span></h3>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">

                            <li role="presentation" class="{{ request()->is('admin/fornecedor/*') ? 'active' : '' }}"><a href="{{route('fornecedor.show',$client->id)}}">Detalhes do fornecedor</a>
                            </li>

                            @can('expense_list')
                            <li role="presentation" class="{{ request()->is('admin/expense-account-list/*') ? 'active' : '' }}"><a href="{{url('admin/expense-account-list/'.$client->id)}}">Conta</a>
                            </li>
                            @endcan
                        </ul><br><br>
                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <p><b>Nome</b></p>
                                        </div>
                                        <div class="col-md-7 part">
                                            <p>: @if($client->nome !=null)  {{ $client->nome.' '. $client->sobrenome }} @else {{ $client->company_name}}@endif </p>
                                        </div>
                                    </div>
                                    <li class="divider"></li>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <p><b>Telefone</b></p>
                                        </div>
                                        <div class="col-md-7 part">
                                            <p>:  {{ $client->telefone ?? 'N/A' }} </p>
                                        </div>
                                    </div>

                                    <li class="divider"></li>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <p><b>N&ordm; de Identifica&ccedil;&atilde;o Fiscal</b></p>
                                        </div>
                                        <div class="col-md-7 part">
                                            <p>:  {{ $client->nif ?? 'N/A' }} </p>
                                        </div>
                                    </div>


                                    <li class="divider"></li>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <p><b>Nome da empresa</b></p>
                                        </div>
                                        <div class="col-md-7 part">
                                            <p>: {{ $client->company_name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <li class="divider"></li>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <p><b>E-mail</b></p>
                                        </div>
                                        <div class="col-md-7 part">
                                            <p>: {{ $client->email ?? 'N/A' }} </p>
                                        </div>
                                    </div>
                                    <li class="divider"></li>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <p><b>Telefone alternativo.</b></p>
                                        </div>
                                        <div class="col-md-7 part">
                                            <p>:{{ $client->alternate_no ?? 'N/A' }} </p>
                                        </div>
                                    </div>

                                    <li class="divider"></li>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <p><b>Endere&ccedil;o</b></p>
                                        </div>
                                        <div class="col-md-7 part">
                                            <p>: {{ $client->address ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <li class="divider"></li>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.fornecedor.payment_made');
@include('admin.fornecedor.payment_made_history');
@endsection

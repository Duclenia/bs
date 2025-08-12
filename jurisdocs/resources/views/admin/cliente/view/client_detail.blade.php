@extends('admin.layout.app')
@section('title','Detalhes do cliente')
@section('content')
<div class="page-title">
    <div class="title_left">
        <h4>Nome do cliente : {{mb_strtoupper($name)}} </h4>
    </div>

    <div class="title_right">
        <div class="form-group pull-right top_search">
            <a href="{{route('clients.index')}}" class="btn btn-primary">Voltar</a>

        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="{{ request()->is('admin/clients/*') ? 'active' : '' }}"><a
                            href="{{ route('clients.show', [encrypt($client->id)]) }}">Detalhes do cliente</a>
                    </li>

                    @can('case_list')
                    <li class="{{ request()->is('admin/client/case-list/*') ? 'active' : '' }}"
                        role="presentation"><a href="{{route('clients.case-list',[encrypt($client->id)])}}">Processos</a>
                    </li>
                    @endcan


                    @can('invoice_list')
                    <li class="{{ request()->is('admin/client/account-list/*') ? 'active' : '' }}"
                        role="presentation"><a
                            href="{{route('clients.account-list',[$client->id])}}">Conta</a>
                    </li>
                    @endcan
                </ul>

            </div>

            <div class="x_content">

                <div class="dashboard-widget-content">
                    <div class="col-md-6 hidden-small">
                        <table class="countries_list">
                            <tbody>
                                <tr>
                                    <td>N&ordm; de registo</td>
                                    <td class="fs15 fw700 text-right">{!! str_pad($client->id, 5, '0', STR_PAD_LEFT) !!}</td>
                                </tr>
                                <tr>
                                    <td>Nome</td>
                                    <td class="fs15 fw700 text-right">{{ mb_strtoupper($client->full_name)}}</td>
                                </tr>
                                
                                <tr>
                                    <td>N&ordm; de Identifica&ccedil;&atilde;o Fiscal</td>
                                    <td class="fs15 fw700 text-right">{{ strtoupper($client->nif) ?? ''}}</td>
                                </tr>
                                
                                <tr>
                                    <td>Telefone</td>
                                    <td class="fs15 fw700 text-right">{{ $client->telefone ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td>Nome de refer&ecirc;ncia</td>
                                    <td class="fs15 fw700 text-right">{{ $client->nome_referencia ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td>Pa&iacute;s de Nascimento</td>
                                    <td class="fs15 fw700 text-right">{{ $client->pais->nome ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td>Estado / Prov&iacute;ncia</td>
                                    <td class="fs15 fw700 text-right">{{ $client->provincia->nome ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td>Cidade</td>
                                    <td class="fs15 fw700 text-right">{{ $client->municipio->nome ?? '' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6 hidden-small">

                        <table class="countries_list">
                            <tbody>

                                <tr>
                                    <td>E-mail</td>
                                    <td class="fs15 fw700 text-right s">{{ $client->email ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td>Telefone Alternativo.</td>
                                    <td class="fs15 fw700 text-right">{{ $client->alternate_no ?? '' }} </td>
                                </tr>

                                <tr>
                                    <td>Morada :</td>
                                    <td class="fs15 fw700 text-right">{{ $client->endereco ?? '' }}</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-md-12 col-sm-12 col-xs-12">
        @if(count($single)>0 && !empty($single))
        <div class="x_panel">

            <div class="x_content">
                <div class="dashboard-widget-content">
                    @php
                    $i=1;
                    @endphp
                    @if(isset($single) && !empty($single))
                    @foreach($single as $s)
                    <div class="col-md-6 hidden-small">
                        <h4 class="line_30">Advogado(a)</h4>

                        <table class="countries_list">
                            <tbody>

                                <tr>
                                    <td>{{$i.' ) '.$s->party_firstname.' '.$s->party_middlename.' '.$s->party_lastname }}</td>

                                </tr>
                                <tr>
                                    <td>Telefone :- {{ $s->party_mobile}}</td>

                                </tr>
                                <tr>
                                    <td>Morada :-{{ $s->party_address}}</td>

                                </tr>
                                @if($client->client_type=="multiple")
                                <tr>
                                    <td>Advogado:-{{ $s->party_advocate}}</td>

                                </tr>

                                @endif

                            </tbody>
                        </table>
                    </div>
                    @php
                    $i++;
                    @endphp
                    @endforeach

                    @endif

                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

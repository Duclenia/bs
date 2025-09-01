@extends('admin.layout.app')
@section('title', 'Detalhes da Reunião')

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Detalhes da Reunião</h3>
            </div>
            <div class="title_right">
                <div class="form-group pull-right top_search">
                    <a href="{{ route('reuniao.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">
                        <div class="row">
                            <div class="col-md-6 hidden-small">
                                <h4>Informações da Reunião</h4>
                                <table class="countries_list">
                                    <tbody>
                                        <tr>
                                            <th width="30%">Entidade:</th>
                                            <td>{{ $appointment->vc_entidade }}</td>
                                        </tr>
                                        <tr>
                                            <th>Motivo:</th>
                                            <td>{{ $appointment->vc_motivo }}</td>
                                        </tr>
                                        <tr>
                                            <th>Plataforma:</th>
                                            <td>{{ $appointment->vc_plataforma }}</td>
                                        </tr>
                                        <tr>
                                            <th>Link para o meeting :</th>
                                            <td>

                                                @if ($appointment->join_url)
                                                    <a href="{{ $appointment->join_url }}"
                                                        target="_blank">{{ Str::limit($appointment->join_url, 45, '...') }}</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Nota:</th>
                                            <td>{{ $appointment->vc_nota ?? '-' }}</td>
                                        </tr>
                                        <br>
                                        <tr>
                                            <th>Termo Aceito:</th>
                                            <td>{{ $appointment->it_termo ? 'Sim' : 'Não' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6 hidden-small">
                                <h4>Informações da Agenda</h4>
                                <table class="countries_list">
                                    <tbody>
                                        <tr>
                                            <th width="30%">Data:</th>
                                            <td>{{ date('d/m/Y', strtotime($appointment->data)) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Hora:</th>
                                            <td>{{ date('H:i', strtotime($appointment->hora)) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Telefone:</th>
                                            <td>{{ $appointment->telefone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                @if ($appointment->activo == 'OPEN')
                                                    <span class="badge badge-success">Aberto</span>
                                                @elseif($appointment->activo == 'CANCEL BY CLIENT')
                                                    <span class="badge badge-warning">Cancelado pelo cliente</span>
                                                @elseif($appointment->activo == 'CANCEL BY ADVOCATE')
                                                    <span class="badge badge-danger">Cancelado pelo advogado</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Observação:</th>
                                            <td>{{ $appointment->observacao ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br><br>
                        @if ($appointment->cliente_nome || $appointment->cliente_instituicao)
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Informações do Cliente</h4>
                                    <table class="countries_list">
                                        <tbody>
                                            <tr>
                                                <th width="15%">Nome:</th>
                                                <td>{{ $appointment->cliente_nome }} {{ $appointment->cliente_sobrenome }}
                                                </td>
                                            </tr>
                                            @if ($appointment->cliente_instituicao)
                                                <tr>
                                                    <th>Instituição:</th>
                                                    <td>{{ $appointment->cliente_instituicao }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

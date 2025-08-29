@extends('admin.layout.app')
@section('title',__('Dashboard'))
@section('content')

<link href="{{ asset('assets/admin/vendors/fullcalendar/dist/fullcalendar.min.css') }} " rel="stylesheet">


@can('dashboard_list')

<form method="POST" action="{{url('admin/dashboard')}}" id="case_board_form">
    {{ csrf_field() }}
    <!-- top tiles -->
    <div class="page-title">
        <div class="title_left">
            <h3>{{__('Dashboard')}}</h3>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
         <a href="{{ route('clients.index') }}">
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-users"></i>
                    </div>
                    <div class="count">{{ $client ?? '' }}</div>
                    <h3>{{__('Clients')}}</h3>
                    <p>{{__('Total clients')}}</p>
                </div>
            </div>
        </a>

        <a href="{{ route('processo.index') }}">
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-gavel"></i>
                    </div>
                    <div class="count">{{ $case_total ?? '' }}</div>
                    <h3>Processos</h3>
                    <p>Total de Processos</p>
                </div>
            </div>
        </a>
        <a href="{{ route('tarefas.index') }}">
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-tasks"></i>
                    </div>
                    <div class="count">{{ $total_tarefas ?? '' }}</div>
                    <h3>{{__('Tasks')}}</h3>
                    <p>{{$total_tarefas_hoje ?? ''}} {{__('Today')}}</p>
                    <p>{{$total_tarefas_futura ?? ''}} futuras</p>
                </div>
            </div>
        </a>
        <a href="{{ route('agenda.index') }}">
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-calendar-plus-o"></i>
                    </div>
                    <div class="count">{{$total_agenda}}</div>
                    <h3>Agenda</h3>
                    <p>{{$total_agenda_hoje ?? ''}} {{__('Today')}}</p>
                    <p>{{$total_agenda_futura ?? ''}} futuras</p>
                </div>
            </div>
        </a>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Conselho de Casos</h2>
                    &nbsp;&nbsp;
                    @if($totalCaseCount>0)
                    <a href="javascript:void(0);" onClick="downloadCaseBorad()" title="Download case board"><i
                            class="fa fa-download fa-2x"></i></a>
                    &nbsp;
                    <a href="javascript:void(0);" onClick="printCaseBorad()" title="Print case board"
                       target="_blank"><i class="fa fa-print fa-2x"></i></a>@endif

                    <div class="col-md-3 col-sm-12 col-xs-12 pull-right">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" name="client_case" id="client_case"
                                   class="form-control  datecase" readonly=""
                                   value="">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if($totalCaseCount>0)
                    @if(count($case_dashbord)>0 && !empty($case_dashbord))
                    @foreach($case_dashbord as $court)
                    <h4 class="title text-primary"> {!! $court['judge_name'] !!}</h4>
                    <table id="case_list" class="table row-border" style="width:100%">
                        <thead>
                            <tr>
                                <th width="3%">No</th>
                                <th width="20%">N&ordm; do Processo</th>
                                <th width="35%">Case</th>
                                <th width="15%">Next Date</th>
                                <th width="10%">Estado</th>
                                <th width="17%" style="text-align: center;">Ac&ccedil;&atilde;o</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($court['cases']) && count($court['cases']))
                            @foreach($court['cases'] as $key=>$value)
                            @php
                            $class = ( $value->priority=='High')?'fa fa-star':(( $value->priority=='Medium')?'fa fa-star-half-o':'fa fa-star-o');
                            @endphp
                            @if($value->client_position=='Petitioner')
                            @php
                            $first = $value->first_name.' '.$value->middle_name.' '.$value->last_name;
                            $second = $value->party_name;
                            @endphp
                            @else
                            @php
                            $first = $value->party_name;
                            $second = $value->first_name.' '.$value->middle_name.' '.$value->last_name;
                            @endphp
                            @endif

                            <tr>
                                <td>{{$key+1}}</td>
                                <td><span
                                        class="text-primary">{{ $value->registration_number }}</span><br/><small>{{ ($value->caseSubType!='')?$value->caseSubType:$value->caseType }}</small>
                                </td>
                                <td>
                                    {!! $first !!} <br/><b>VS</b><br/> {!! $second !!}
                                </td>
                                <td>@if($value->hearing_date!='')

                                    @else
                                    <span
                                        class="blink_me text-danger">{{__('Date Not Updated')}}</span>
                                    @endif
                                </td>
                                <td>{{ $value->case_status_name }}</td>
                                <td>
                                    <ul class="padding-bottom-custom" style="list-style: none;">
                                        @can('case_edit')

                                        <li style="text-align:left"><a class=""
                                                                       href="javascript:void(0);"
                                                                       onclick="nextDateAdd('{{$value->case_id}}');"><i
                                                    class="fa fa-calendar-plus-o"></i>
                                                &nbsp;&nbsp;{{__('Next Date')}}</a></li>
                                        <li style="text-align:left"><a class=""
                                                                       href="javascript:void(0);"
                                                                       onClick="transfer_case('{{$value->case_id}}');"><i
                                                    class="fa fa-gavel"></i> &nbsp;&nbsp;Case
                                                Transfer</a></li>
                                        @endcan

                                    </ul>
                                </td>
                            </tr>

                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    @endforeach
                    @endif
                    @elseif($case_total>0 && count($case_dashbord)==0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="customers-space">
                                <p class="customers-tittle text-center">{{__('Hoje não tem nenhum conselho')}}</p>
                            </div>
                        </div>
                    </div>

                    @else
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="customers-space">
                                    <h4 class="customers-heading">Manage your case</h4>
                                    <p class="customers-tittle">Mantenha os detalhes completos dos processos, como hist&oacute;rico do processo,
                                        transfer&ecirc;ncia do processo, data da pr&oacute;xima audi&ecirc;ncia, etc.</p>
                                    <div class="cst-btn">
                                        <div class="top-btns" style="text-align: left;">
                                            <a class="btn btn-info"
                                               href="{{url('admin/processo/create')}}"> Novo Processo </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="customers-img">

                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Agendamento</h2>
                    <div class="col-md-3 col-sm-12 col-xs-12 pull-right">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" name="appoint_range" id="appoint_range" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if(count($appoint_calander)>0)

                    <table id="appointment_list" class="table row-border" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nome do cliente</th>
                                <th>Data</th>
                                <th>Hora</th>
                            </tr>
                        </thead>
                    </table>
                    @elseif($appointmentCount>0 && count($appoint_calander)==0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="customers-space">
                                <p class="customers-tittle text-center">Hoje n&atilde;o h&aacute; agendamento.</p>
                            </div>
                        </div>
                    </div>

                    @else
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="customers-space">
                                    <h4 class="customers-heading">{{__('Manage your Appointment')}}</h4>
                                    <p class="customers-tittle">Schedule your appointment with Advocates
                                        Diary and we will remind and notify as and when your appointment is
                                        due.</p>
                                    <div class="cst-btn">
                                        <div class="top-btns" style="text-align: left;">
                                            <a class="btn btn-info"
                                               href="{{url('admin/agenda/create')}}"> Adicionar
                                                Agendamento </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="customers-img">

                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>

    </div>

    <br>


    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Calend&aacute;rio</h2>
                    <div class="col-md-3 col-sm-12 col-xs-12 pull-right">
                        <div class="input-group">

                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div id="calendar_dashbors_case"></div>

                </div>
            </div>
        </div>

    </div>


    <div id="load-modal"></div>
    <!-- /top tiles -->
</form>



<div class="modal fade" id="modal-case-priority" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="show_modal">

        </div>
    </div>
</div>

<div class="modal fade" id="modal-change-court" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="show_modal_transfer">

        </div>
    </div>
</div>
<div class="modal fade" id="modal-next-date" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="show_modal_next_date">

        </div>
    </div>
</div>

<input type="hidden" name="token-value"
       id="token-value"
       value="{{csrf_token()}}">

<input type="hidden" name="case-running"
       id="case-running"
       value="{{url('admin/processo')}}">

<input type="hidden" name="appointment"
       id="appointment"
       value="{{url('admin/appointment')}}">

<input type="hidden" name="ajaxCalander"
       id="ajaxCalander"
       value="{{ url('admin/ajaxCalander') }}">

<input type="hidden" name="date_format_datepiker"
       id="date_format_datepiker"
       value="{{$date_format_datepiker}}">
<input type="hidden" name="dashboard_appointment_list"
       id="dashboard_appointment_list" value="{{ url('admin/dashboard-appointment-list') }}">
<input type="hidden" name="get-day-appointments"
       id="get-day-appointments" value="{{ url('admin/get-day-appointments') }}">

<input type="hidden" name="getNextDateModal"
       id="getNextDateModal"
       value="{{url('admin/getNextDateModal')}}">

<input type="hidden" name="getChangeCourtModal"
       id="getChangeCourtModal"
       value="{{url('admin/getChangeCourtModal')}}">

<input type="hidden" name="getCaseImportantModal"
       id="getCaseImportantModal"
       value="{{url('admin/getCaseImportantModal')}}">
<input type="hidden" name="getCourt"
       id="getCourt"
       value="{{url('getCourt')}}">
<input type="hidden" name="downloadCaseBoard"
       id="downloadCaseBoard"
       value="{{url('admin/downloadCaseBoard')}}">
<input type="hidden" name="printCaseBoard"
       id="printCaseBoard"
       value="{{url('admin/printCaseBoard')}}">


@endcan

@if(auth()->user()->user_type == 'Cliente')
<input type="hidden" name="ajaxCalanderCliente"
       id="ajaxCalanderCliente"
       value="{{ url('cliente/ajaxCalander') }}">

       <input type="hidden" name="get-day-appointmentsCliente"
       id="get-day-appointmentsCliente" value="{{ url('cliente/get-day-appointmentsCliente') }}">


<div class="page-title">
    <div class="title_left">
        <h3>{{__('Dashboard')}}</h3>
    </div>
</div>

<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Agenda</h2>
                <div class="col-md-3 col-sm-12 col-xs-12 pull-right">
                    <div class="input-group">

                    </div>
                </div>

                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div id="agenda_cliente"></div>

            </div>
        </div>
    </div>

</div>

@include('cliente.agenda')
@endif

<input type="hidden" id='get_notification' value="{{route('task.notification')}}">

<div class="modal fade" id="md_notification" data-backdrop="static" role="dialog" aria-labelledby="addcategory" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2">Notifica&ccedil;&atilde;o</h4>
            </div>

            <p id="notificacao" style="padding-left: 6px; padding-top: 5px; font-size: 11pt"></p>

            <div class="modal-footer">

                <button class="btn btn-primary shadow btn_ok"><i class="ik ik-check-circle"
                                                                 id="cl">
                    </i> OK
                </button>
            </div>

        </div>
    </div>
</div>
@include('cliente.notificacao')
@include('cliente.validar_telemovel')

<input type="hidden" id="language" value="{{app()->getLocale()}}">

@endsection

@push('js')
<script src='https://fullcalendar.io/js/fullcalendar-3.1.0/lib/moment.min.js'></script>
<script src="{{ asset('assets/admin/vendors/fullcalendar/dist/fullcalendar.min.js') }}"></script>
<script src="{{ asset('assets/admin/vendors/fullcalendar/dist/lang/pt.js') }}"></script>
<script src="{{asset('assets/js/dashbord/dashbord-datatable.js')}}"></script>
<script src="{{asset('assets/js/masked-input/masked-input.min.js')}}"></script>
<script src="{{asset('assets/cliente/agenda.js')}}"></script>

@if(auth()->user()->user_type == 'User' || auth()->user()->user_type == 'Admin')
<script src="{{asset('assets/js/notification.js')}}"></script>
@endif

@if(auth()->user()->user_type == 'Cliente')
 @if(!is_null(auth()->user()->cliente->codigo_verificacao))
 <script src="{{asset('assets/cliente/validar_telemovel.js')}}"></script>
 <script src="{{asset('assets/cliente/verificar_telemovel.js')}}"></script>
 @endif
@endif

@endpush

<div class="x_title">
    <h2> Processo</h2>
    <ul class="nav navbar-right panel_toolbox">
        <li>

            <a class="card-header-color"  href="{{url('admin/case-running-download/'.$case->processo_id.'/download')}}"
               title="Download case file"><i class="fa fa-download fa-2x "></i></a>
        </li>
        <li>
            <a class="card-header-color"  href="{{url('admin/case-running-download/'.$case->processo_id.'/print')}}"
               title="Print case file" target="_blank"><i class="fa fa-print fa-2x"></i></a>
        </li>

    </ul>
    <div class="clearfix"></div>
</div>

<br>
<div class="" role="tabpanel" data-example-id="togglable-tabs">
    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
        <li role="presentation" class="@if(Request::segment(2)=='case-running')active @ else @endif"><a
                href="{{route('processo.show', encrypt($case->processo_id))}}">Detalhes</a>
        </li>
        <li role="presentation" class="@if(Request::segment(4)=='histroy')active @ else @endif"><a
                href="{{url( 'admin/case-history/'.encrypt($case->processo_id))}}">Hist&oacute;rico</a>

        </li>
        <li role="presentation" class="@if(Request::segment(4)=='transfer')active @ else @endif"><a
                href="{{url('admin/case-transfer/'.$case->processo_id)}}">Transfer</a>
        </li>
        @can('case_edit')
            <li role="presentation" class="pull-right udt-nd"><a href="javascript:void(0);"
                                                                 onClick="nextDateAdd({{$case->processo_id}});"><i
                        class="fa fa-calendar"></i> Update Next Date</a>
            </li>
        @endcan
            <li role="presentation" class="pull-right udt-nd"><a href="javascript:void(0);"><i
                        class="fa fa-calendar"></i> Update Next Date</a>
            </li>
        
    </ul>

</div>

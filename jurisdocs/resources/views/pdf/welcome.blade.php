<!DOCTYPE html>
<html>
    <head>
        <style>
            .container
            {
                margin:auto;
                width:95%;
            }
            table {
                border-collapse: collapse;
            }

            table, th, td {
                border: 1px solid black;
                text-align:center;
            }
            .remove-border
            {
                border:none !important;
                border-left: none !important;
                border-top: none !important;
                border-right: none !important;
                border-bottom: none !important;

            }

            .heading1{
                font-size:20px;
                font-style:bold;
                font-weight: bold;
                font-family:Arial, Helvetica, sans-serif;
            }

            .heading2{
                font-size:17px;
                font-style:bold;
                font-weight: bold;
                font-family:Arial, Helvetica, sans-serif;
            }

            .heading3{
                font-size:12px;
                font-style:bold;
                /*font-weight: bold;*/
                /*font-family:Arial, Helvetica, sans-serif;*/
            }
        </style>
        <title>Detalhes do processo | {{$case->no_processo ?? ''}}</title>
    </head>
    <body>
        <div class="container">

            <h1 class="heading1" style="text-align: center;"><b>{{$setting->nome_escritorio ?? ''}}</b></h1>
            <center><b>Bairro</b>: {{$setting->endereco->bairro->nome.',' ?? ''}} <b>Munic&iacute;pio</b>: {{$setting->endereco->municipio->nome.',' ?? '' }} <b>Rua</b>: {{$setting->endereco->rua.',' ?? ''}} N.&ordm;  {{$setting->endereco->numero ?? '' }}  </center>
            <hr>

            <table width="100%" border="0" style="border-style:none">

                <tr>
                    <td class="remove-border heading1" width="100%" ><b>{{$case->tribunal ?? ''}}</b></td>
                </tr>
                
                <tr>
                    <td class="remove-border heading2" width="100%" ><b>{{$case->seccao ?? ''}}</b></td>
                </tr>
                
            </table>


            <h1 class="heading2" style="text-align: center;">Detalhes do processo</h1>


            <table  width="100%" style="margin-top:12px; border-style: solid;">
                <tr>
                    <td class="heading3 " width="30%" style="text-align:left;border-right: none !important;"> Natureza do processo </td>
                    <td class="heading3" width="40%" style="text-align:left;border-left: none;border-right: none;">:
                        @if(isset($case->areaprocessual) && !empty($case->areaprocessual)) {{$case->areaprocessual ?? ''}} @endif</td>
                    <td class="heading3" width="30%" style="text-align:left;border-left: none;border-right: none;"></td>
                </tr>
                
                <tr>
                    <td class="heading3 " width="30%" style="text-align:left;border-right: none !important;"> N&ordm; do processo </td>
                    <td class="heading3" width="40%" style="text-align:left;border-left: none;border-right: none;">:
                        @if(isset($case->no_processo) && !empty($case->no_processo)) {{$case->no_processo ?? ''}} @endif</td>
                    <td class="heading3" width="30%" style="text-align:left;border-left: none;border-right: none;"></td>
                </tr>
                
                <tr>
                    <td class="heading3 " width="30%" style="text-align:left;border-right: none !important;"> Tipo do processo </td>
                    <td class="heading3" width="40%" style="text-align:left;border-left: none;border-right: none;">:
                        @if(isset($case->caseType) && !empty($case->caseType)) {{$case->caseType ?? ''}} @endif</td>
                    <td class="heading3" width="30%" style="text-align:left;border-left: none;border-right: none;"></td>
                </tr>
                
                <tr>
                    <td class="heading3 " width="30%" style="text-align:left;border-right: none !important;"> <b>Juiz da causa</b> </td>
                    <td class="heading3" width="60%" style="text-align:left;border-left: none;border-right: none;"> :  @if(isset($case->judgeType) && !empty($case->judgeType)) {{$case->judgeType ?? ''}} @endif</td>
                    <td class="heading3" width="10%" style="text-align:left;border-left: none;border-right: none;"></td>
                </tr>
                
                <tr>
                    <td class="heading3 " width="30%" style="text-align:left;border-right: none !important;"> <b>Estado do processo</b> </td>
                    <td class="heading3" width="60%" style="text-align:left;border-left: none;border-right: none;"> :  @if(isset($case->estado) && !empty($case->estado)) {{$case->estado ?? ''}} @endif</td>
                    <td class="heading3" width="10%" style="text-align:left;border-left: none;border-right: none;"></td>
                </tr>

            </table>

            
            <h1 class="heading2" style="text-align: center;">Petitioner and Advocate</h1>


            <div style="border: solid;border-width: thin;">
                @if(count($petitioner_and_advocate)>0 && !empty($petitioner_and_advocate))
                @php $i=1; @endphp
                @foreach($petitioner_and_advocate as $value)
                <span style="margin-left:10px; " class="heading3"> {{ $i.') '.$value['party_name'] }}</span><br/>
                <span style="margin-left:10px; " class="heading3"> Advogado -   {{$value['party_advocate'] }} </span><br/>
                @php $i++; @endphp
                @endforeach
                @endif
            </div>

            <h1 class="heading2" style="text-align: center;">Respondent and Advocate</h1>

            <div style="border: solid;border-width: thin;">
                @if(count($respondent_and_advocate)>0 && !empty($respondent_and_advocate))
                @php $j=1; @endphp
                @foreach($respondent_and_advocate as $value)
                <span style="margin-left:10px; " class="heading3"> {{ $j.') '.$value['party_name'] }}</span><br/>
                <span style="margin-left:10px;padding-bottom: 15px; " class="heading3"> Advocate -   {{$value['party_advocate'] }} </span><br/>
                @php $j++; @endphp
                @endforeach
                @endif
            </div>
            

            
            <h1 class="heading2" style="text-align: center;">History of Case Hearing</h1>

            <table  width="100%" style="margin-top:12px; border-style: solid;">
                <tr>
                    <td class="heading3 " width="20%" style="text-align:center !important;"><b>Registration Number </b></td>
                    <td class="heading3 " width="30%" style="text-align:center !important;"><b>Judge </b></td>
                    <td class="heading3 " width="10%" style="text-align:center !important;"><b>Business On Date </b></td>
                    <td class="heading3" width="10%" style="text-align: center;"><b>Hearing Date</b></td>
                    <td class="heading3" width="30%" style="text-align: center;"><b>Purpose of hearing</b></td>
                </tr>

                @if(count($history)>0 && !empty($history))
                @foreach($history as $h)

                <tr>
                    <td class="heading3 " width="20%" style="text-align:center !important;">{{$h->registration_number ?? '' }}</td>
                    <td class="heading3 " width="20%" style="text-align:center !important;">{{$h->judge_name ?? '' }} </td>
                    
                    <td class="heading3" width="20%" style="text-align: center;">{{$h->case_status_name ?? '' }}</td>
                </tr>

                @endforeach
                @endif

            </table>
            <h1 class="heading2" style="text-align: center;">Case Transfer Details Between The Courts</h1>

            <table  width="100%" style="margin-top:12px; border-style: solid;">
                <tr>
                    <td class="heading3 " width="15%" style="text-align:center !important;"><b>Regn. Number  </b></td>
                    <td class="heading3 " width="15%" style="text-align:center !important;"><b>Transfer Date</b></td>
                    <td class="heading3 " width="35%" style="text-align:center !important;"><b>From Court Numberand Judge</b></td>
                    <td class="heading3" width="35%" style="text-align: center;"><b>To Court Number and Judge</b></td>
                </tr>

                @if(count($transfer)>0 && !empty($transfer))
                @foreach($transfer as $t)
                <tr>
                    <td class="heading3 " width="15%" style="text-align:center !important;">{{$t->registration_number ?? '' }} </td>
                    <td class="heading3 " width="15%" style="text-align:center !important;">@if(isset($t->transferDate) && !empty($t->transferDate))  @endif</td>
                    <td class="heading3 " width="35%" style="text-align:center !important;">{{ $t->from_court_no ?? ''}} - {{ $t->judge_name ?? ''}}</td>
                    <td class="heading3" width="35%" style="text-align: center;">{{ $t->to_court_no ?? ''}} - {{ $t->transferJudge ?? ''}}
                    </td>
                </tr>
                1-3rd ADDL DISTRICT JUDGE
                @endforeach
                @endif

            </table>
        </div>
    </body>
</html>
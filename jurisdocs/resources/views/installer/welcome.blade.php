@extends('installer.app')
@section('title','Obrigado')
@section('content')
    <section class="login_content">

        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2 style="float: none;">Thank you for purchase</h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/login') }}">
                            {{ csrf_field() }}

                            <h2> Welcome to setup wizard </h2>
                            <p class="text-left">Thanks you for purchasing LawOffice,LawOffice System é um aplicativo baseado na Web para que advogados e escrit&oacute;rios de advocacia mantenham seus escrit&oacute;rios. Este software &eacute; muito f&aacute;cil de operar e ferramenta de sistema leve para manter informa&ccedil;&otilde;es sobre clientes, processos, audi&ecirc;ncias etc.</p>
                            <div>
                                <a href="{{route('check.requirements')}}" class="btn btn-default pull-right">
                                    Pr&oacute;ximo
                                </a>
                            </div>

                            <div class="clearfix"></div>

                            <div class="separator">

                                <div class="clearfix"></div>
                                <br/>

                                <div>

                                    <p>©2021 BS Advogados. Desenvolvido por Mwango Click</p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
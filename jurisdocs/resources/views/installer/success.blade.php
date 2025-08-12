@extends('installer.app')
@section('title','Sucesso')

@section('content')

    <section class="login_content">

        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2 style="float: none;">Sucesso</h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <!-- <h1>Advocate Diary</h1> -->
                        <h2> Instala&ccedil;&atilde;o do sistema com sucesso. </h2>
                        <p class="text-left">Sistema instalado com sucesso em seu servidor. Agora clique no bot&atilde;o de login abaixo e insere os seus dados de acesso.</p>
                        <div>
                            <a href="{{url('/')}}" class="btn btn-default pull-right">Login Now</a>
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
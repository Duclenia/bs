
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $image_logo->nome_escritorio ?? '' }} | Login </title>

        <link rel="icon" type="image/png" sizes="70x70" href="{{asset('img/ms-icon-70x70.png')}}">
        <link rel="icon" type="image/png" sizes="144x144" href="{{asset('img/ms-icon-144x144.png')}}">
        <link rel="icon" type="image/png" sizes="150x150" href="{{asset('img/ms-icon-150x150.png')}}">

        <link rel="manifest" href="{{asset('img/manifest.json')}}">

        <!-- Bootstrap -->
        <link href="{{URL::asset('assets/admin/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="{{URL::asset('assets/admin/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
        <!-- NProgress -->
        <link href="{{URL::asset('assets/admin/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
        <!-- Animate.css -->
        <link href="{{URL::asset('assets/admin/vendors/animate.css/animate.min.css') }}" rel="stylesheet">

        <!-- Custom Theme Style -->
        <link href="{{URL::asset('assets/admin/build/css/custom.min.css') }}" rel="stylesheet">
        <script>
            window.Laravel = <?php
echo json_encode([
    'csrfToken' => csrf_token(),
]);
?>
        </script>
        <style type="text/css">
            .login_content_btn a:hover{
                text-decoration: none;
            }
        </style>
    </head>

    <body class="login">
        <div>
            <a class="hiddenanchor" id="signup"></a>
            <a class="hiddenanchor" id="signin"></a>

            <div class="login_wrapper">
                <div class="animate form login_form">
                    <section class="login_content">
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                            @csrf

                            <img src="{{ asset('upload/logosistema.png') }}">

                            <h2> Acesso ao Sistema </h2>
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"  value="{{ old('email') }}" autocomplete="off" autofocus placeholder="E-mail" required="">

                                @error('email')
                                <span class="help-block text-left">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <input type="password" name="password" id="password"  class="form-control @error('password') is-invalid @enderror"  autocomplete="off" placeholder="Palavra-passe" required="">

                                @error('password')
                                <span class="help-block text-left">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div>
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                                
                                <a class="reset_pass"  href="{{ route('password.request') }}">Esqueceu-se da palavra-passe?</a>

                            </div>

                            <div class="clearfix"></div>

                            <div class="separator">

                                <div class="clearfix"></div>
                                <br />

                                <div>

                                    <p>©2021 JurisDocs - Gest&atilde;o de Escrit&oacute;rio de Advogados. Desenvolvido por Mwango Click</p>
                                </div>
                            </div>
                        </form>
                    </section>
                </div>
            </div>

        </div>


        <!-- jQuery -->
        <script src="{{asset('assets/admin/vendors/jquery/dist/jquery.min.js') }}"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                "use strict";
                $(".fill-login").click(function () {
                    $("#email").val($(this).data("email"));
                    $("#password").val($(this).data("password"));
                });

            });
        </script>
    </body>
</html>


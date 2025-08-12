<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $image_logo->nome_escritorio ?? '' }} | {{ __('Reset Password') }} </title>

        <link rel="icon" type="image/png" sizes="70x70" href="{{asset('img/ms-icon-70x70.png')}}">
        <link rel="icon" type="image/png" sizes="144x144" href="{{asset('img/ms-icon-144x144.png')}}">
        <link rel="icon" type="image/png" sizes="150x150" href="{{asset('img/ms-icon-150x150.png')}}">

        <link rel="manifest" href="{{asset('img/manifest.json')}}">

        <!-- Bootstrap -->
        <link href="{{asset('assets/admin/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="{{asset('assets/admin/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
        <!-- NProgress -->
        <link href="{{asset('assets/admin/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
        <!-- Animate.css -->
        <link href="{{asset('assets/admin/vendors/animate.css/animate.min.css') }}" rel="stylesheet">

        <!-- Custom Theme Style -->
        <link href="{{asset('assets/admin/build/css/custom.min.css') }}" rel="stylesheet">
        <script>
            window.Laravel = <?php
echo json_encode([
    'csrfToken' => csrf_token(),
]);
?>
        </script>
    </head>

    <body class="login">
        <div>
            <a class="hiddenanchor" id="signup"></a>
            <a class="hiddenanchor" id="signin"></a>

            <div class="login_wrapper">
                <div class="animate form login_form">
                    <section class="login_content">
                        @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                        @endif
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <img src="{{ asset('upload/logosistema.png') }}" style="margin-bottom: 20px;">

                            <h2> {{ __('Reset Password') }} </h2>
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <input  type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"  value="{{ old('email') }}" placeholder="E-mail" required autocomplete="email" autofocus>

                                @if ($errors->has('email'))
                                <span class="help-block text-left">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div>
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>

                                <a class="reset_pass pull-left"  href="{{ route('login') }}">{{ __('Login') }}</a>

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
    </body>
</html>
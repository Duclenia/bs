<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $image_logo->nome_escritorio ?? '' }} | Password Reset </title>
        @if($image_logo->favicon_img!='')
        <link rel="shortcut icon" href="{{asset(config('constants.FAVICON_FOLDER_PATH') .'/'. $image_logo->favicon_img)}}" >
        @else
        <link rel="icon" type="image/png" sizes="70x70" href="{{asset('img/ms-icon-70x70.png')}}">
        <link rel="icon" type="image/png" sizes="144x144" href="{{asset('img/ms-icon-144x144.png')}}">
        <link rel="icon" type="image/png" sizes="150x150" href="{{asset('img/ms-icon-150x150.png')}}">

        <link rel="manifest" href="{{asset('img/manifest.json')}}">
        @endif

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
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">
                            @if($image_logo->logo_img!='')
                            <img src="{{asset(config('constants.LOGO_FOLDER_PATH') .'/'. $image_logo->logo_img)}}" style="width: 308px;">
                            @else
                            <img src="{{ asset('upload/logosistema.png') }}" style="margin-bottom: 20px;">
                            @endif
                            <h2> Reset Your Account </h2>
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @if ($errors->has('email'))
                                <span class="help-block text-left">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{__('Password')}}" required autocomplete="new-password">

                                @if ($errors->has('password'))
                                <span class="help-block text-left">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="{{__('Confirm Password')}}" autocomplete="off">

                                @if ($errors->has('password_confirmation'))
                                <span class="help-block text-left">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div>
                                <button type="submit" class="btn btn-primary">
                                    {{__('Reset Password') }}
                                </button>

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



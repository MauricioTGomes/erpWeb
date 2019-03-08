<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Men Store MS | Entrar</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('layout/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('layout/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('layout/bower_components/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('layout/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('layout/plugins/iCheck/square/blue.css') }}">

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Men Store </b>MS</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Fazer login</p>


        {!! Form::open(array('method' => 'POST','router' => 'login','class'=>'form-horizontal')) !!}
        <meta name="csrf-token" content="{{ csrf_token() }}">
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} has-feedback">
            <input type="email" class="form-control" name="email" placeholder="Email">
            @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif

            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} has-feedback">
            <input type="password" class="form-control" placeholder="Senha" name="password">
                                   @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif

            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
            <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <label>
                        <input type="checkbox"> Relembrar
                    </label>
                </div>
            </div>
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Entrar</button>
            </div>
        </div>
        {!! Form::close() !!}

    </div>
</div>

<script src="{{ asset('layout/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('layout/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('layout/plugins/iCheck/icheck.min.js') }}"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' /* optional */
        });
    });
</script>
</body>
</html>
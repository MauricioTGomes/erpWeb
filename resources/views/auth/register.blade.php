@extends('layouts.app')


@section('conteudo')

        <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Nome do software | Registrar</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('layout/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('layout/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('layout/bower_components/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('layout/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('layout/plugins/iCheck/square/blue.css') }}">

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-box-body">
        <p class="login-box-msg">Registrar novo usuário</p>

        {!! Form::open(['route'=> 'gravar.usuario', 'method' => 'post']) !!}
            @include('auth.campos_form')
        {!! Form::close() !!}
    </div>
</div>

<script src="{{ asset('layout/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('layout/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('layout/plugins/iCheck/icheck.min.js') }}"></script>
</body>
</html>

@endsection
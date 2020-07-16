<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="token" content="{{ csrf_token() }}">
    <title>Men Store | MS</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('/layout/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('/layout/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/layout/bower_components/Ionicons/css/ionicons.min.css') }}">
    <script src="{{ asset('js/jquery-2.2.0.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/layout/dist/css/skins/skin-blue.min.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-blue sidebar-mini">

<div class="row">
    <div class="container">
        @yield('conteudo')
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/select2.js') }}"></script>
<script src="{{ asset('layout/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="{{ asset('layout/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('layout/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('layout/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
</body>

</html>
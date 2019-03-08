@extends('layouts/app')
@section('conteudo')

    {!! Form::open(['route'=>'pessoas.gravar','class'=>'form-pessoa','enctype'=>'multipart/form-data']) !!}

    @include('pessoas.arquivos.campos_form')

    {!! Form::close() !!}

@endsection
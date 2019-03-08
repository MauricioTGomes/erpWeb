@extends('layouts/app')
@section('conteudo')

    {!! Form::open(['route'=>'movimentacao.gravar','class'=>'form-movimentacao','enctype'=>'multipart/form-data']) !!}

    @include('caixa.arquivos.campos_form')

    {!! Form::close() !!}

@endsection
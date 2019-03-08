@extends('layouts/app')
@section('conteudo')

    {!! Form::open(['route'=>'produtos.gravar','class'=>'form-produto','enctype'=>'multipart/form-data']) !!}

    @include('produtos.arquivos.campos_form')

    {!! Form::close() !!}

@endsection
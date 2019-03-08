@extends('layouts/app')
@section('conteudo')

    {!! Form::model($produto,['route' => ['produtos.update', $produto->id], 'metho'=>'POST','class'=>'form-produto','enctype'=>'multipart/form-data']) !!}

    @include('produtos.arquivos.campos_form')

    {!! Form::close() !!}

@endsection
@extends('layouts/app')
@section('conteudo')

    {!! Form::model($pessoa,['route' => ['pessoas.update', $pessoa->id], 'metho'=>'POST','class'=>'form-pessoa','enctype'=>'multipart/form-data']) !!}

    @include('pessoas.arquivos.campos_form')

    {!! Form::close() !!}

@endsection
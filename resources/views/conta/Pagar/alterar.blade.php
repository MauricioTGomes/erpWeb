@extends('layouts/app')
@section('conteudo')

    @if (session('aviso'))
        <div class="alert alert-warning">
            {{ session('aviso') }}
        </div>
    @endif

    {!! Form::model($conta,['route' => ['contas.update', $conta->id], 'method'=>'post', 'class'=>'form-conta']) !!}

    <input type="hidden" value="{{ $conta->tipo_operacao }}" name="tipo_operacao">

    @include('conta.campos_form')

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <div class="form-actions">
                    {!! Form::submit('Salvar conta', ['class'=> 'contas btn-submit btn btn-effect-ripple btn-success']) !!}
                    <a href="{{route('contas.pagar.listar')}}" class="btn btn-effect-ripple btn-danger">Cancelar</a>
                </div>
            </div>
        </div>
    </div>

    {!! Form::close() !!}

@endsection
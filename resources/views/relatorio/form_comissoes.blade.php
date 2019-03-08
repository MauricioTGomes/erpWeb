@extends('layouts/app')
@section('conteudo')

{!! Form::open(['route'=>'relatorio.gravar', 'enctype'=>'multipart/form-data']) !!}
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="user_id">Vendedor <i class="text-danger" title="Campo obrigatório">*</i></label>
                        <select class="select2 form-control" name="user_id">
                            @foreach($usuarios as $user)
                                <option value="{{ $user->id }}">{{ $user->name . ' - ' . $user->tipo}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="data_inicial">Data inicial <i class="text-danger" title="Campo obrigatório">*</i></label>
                        <input class="form-control input-date" type="text" name="data_inicial"/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="data_final">Data final <i class="text-danger" title="Campo obrigatório">*</i></label>
                        <input class="form-control input-date" type="text" name="data_final"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group form-actions">
                {!! Form::submit('Imprimir', ['class'=> 'btn-submit btn btn-effect-ripple btn-success']) !!}
            </div>
        </div>
    </div>
{!! Form::close() !!}
@endsection
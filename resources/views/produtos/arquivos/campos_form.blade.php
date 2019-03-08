<div class="box box-default">
        <div class="box-header with-border">
            <h5 class="box-title">Dados</h5>
        </div>
        <div class="box-body">
            <div class="row">
        <div class="col-sm-4">
            <label for="ativo">Ativo <i class="text-danger" title="Campo obrigatório">*</i></label>
            <div class="form-group">
                {!! Form::select('ativo', [0=>'Sim',1=>'Não'],null, ['class'=>'select2 form-control']) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2">
            <div class="form-group">
                <label for="codigo">Código</label>
                {!! Form::text('codigo', null, ['class'=>'input-positive form-control']) !!}
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label for="cod_barras">Código de barras</label>
                {!! Form::text('cod_barras', null, ['class'=>'form-control']) !!}
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="nome">Nome <i class="text-danger" title="Campo obrigatório">*</i></label>
                {!! Form::text('nome', null, ['class'=>'form-control']) !!}
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="apelido_produto">Apelido produto<i class="text-danger"
                                                               title="Campo obrigatório">*</i></label>
                {!! Form::text('apelido_produto', isset($produto) ? $produto->apelido_produto : null,['class'=>'form-control']) !!}
            </div>
        </div>
    </div>
        </div>
    </div>


    <div class="box box-default">
        <div class="box-header with-border">
            <h5 class="box-title">Estoque</h5>
        </div>
        <div class="box-body">
            <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="qtd_estoque">Quantidade em estoque<i class="text-danger" title="campo obrigatório">*</i></label>
                <div class="input-group">
                    {!! Form::text('qtd_estoque', null, ['class'=>'input-positive form-control']) !!}
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="valor_compra">Valor de compra</label>
                {!! Form::text('valor_compra', null, ['class'=>'input-money form-control']) !!}
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="valor_venda">Valor de venda<i class="text-danger" title="campo obrigatório">*</i></label>
                {!! Form::text('valor_venda', null, ['class'=>'input-money form-control']) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group form-actions">
                {!! Form::submit('Gravar produto', ['class'=> 'btn-submit btn btn-effect-ripple btn-success']) !!}
                <a href="{{route('produtos.listar')}}" class="btn btn-effect-ripple btn-danger">Cancelar</a>
            </div>
        </div>
    </div>
        </div>
    </div>

<script src="{{ elixir('js/jquery-2.2.0.min.js') }}"></script>
<script src="{{ elixir('js/app.js') }}"></script>
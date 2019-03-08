{{--<div class="w3-container w3-light-grey">--}}


<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Movimentação no caixa</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="operacao">Operação de <i class="text-danger" title="Campo obrigatório">*</i></label>
                            {!! Form::select('operacao', ['0'=>'Entrada','1'=>'Saída'], null, ['class'=>'select2 form-control']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="valor">Valor <i class="text-danger" title="Campo obrigatório">*</i></label>
                            {!! Form::text('valor', null, ['class'=>'form-control input-money']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="descricao">Descrição<i class="text-danger" title="Campo obrigatório">*</i></label>
                    {!! Form::textarea('descricao', null,['class'=>'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

<div class="row">
    <div class="col-sm-12">
        <div class="form-group form-actions">
            {!! Form::submit('Gravar movimentação', ['class'=> 'btn-submit btn btn-effect-ripple btn-success']) !!}
            <a href="{{route('movimentacao.listar')}}" class="btn btn-effect-ripple btn-danger">Voltar</a>
        </div>
    </div>
</div>

<script src="{{ elixir('js/jquery-2.2.0.min.js') }}"></script>
<script src="{{ elixir('js/app.js') }}"></script>

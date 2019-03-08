<div class="faturamento">
    <div class="box box-default">
        <div class="box-header with-border">
            <h5 class="box-title">Dados</h5>
        </div>
        <div class="box-body">
        @if(!isset($conta))
            <div class="row">
                <div class="col-sm-6">
                    <label for="pessoa_id">Pessoa<i class="text-danger" title="Campo obrigatório">*</i></label>
                    <select name="pessoa_id" class="select2 select-pessoa form-control">
                        @foreach($pessoas as $pessoa)
                            <option value="{{ $pessoa->id }}">{{ $pessoa->nomeCompletoCpfCnpj() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @else
            <input type="hidden" name="pessoa_id" value="{{ $conta->pessoa_id }}">
            @endif

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="titulo">Título<i class="text-danger" title="Campo obrigatório">*</i></label>
                        {!! Form::text('titulo', null, ['class'=>'form-control']) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="data_emissao">Data de emissão <i class="text-danger" title="Campo obrigatório">*</i></label>
                        {!! Form::text('data_emissao', date('d/m/Y'), ['class'=>'input-data-emissao input-data form-control', 'title'=>'Data de emissão']) !!}
                    </div>
                </div>
            </div>

            <div id="div-valores-conta">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="vlr_total">Valor da conta <i class="text-danger" title="Campo obrigatório">*</i></label>
                            {!! Form::text('vlr_total', null, ['class'=>'input-valor-pago input-money form-control', 'title'=>'Valor da conta']) !!}
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="qtd_parcelas">Número de parcelas <i class="text-danger"
                                                                            title="Campo obrigatório">*</i></label>
                            {!! Form::text('qtd_parcelas', isset($conta) ? $conta->qtd_parcelas : 3, ['class'=>'input-qtd-parcelas input-positive form-control', 'title'=>'Número de parcelas', 'min' => '0']) !!}
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="qtd_dias">Dias entre as parcelas <i class="text-danger"
                                                                            title="Campo obrigatório">*</i></label>
                            {!! Form::text('qtd_dias', isset($conta) ? $conta->qtd_dias : 30, ['class'=>'input-qtd-dias input-positive form-control', 'title'=>'Dias entre as parcelas', 'min' => '0']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="box box-default">
        <div class="box-header with-border">
            <h5 class="box-title">Parcelas</h5>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <a id="js-btnCalculaParcelasConta" class="btn btn-effect-ripple btn-success btn-sm">Calcular
                            parcelas</a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th width="33%">Parcela</th>
                            <th width="33%">Data</th>
                            <th width="33%">Valor (R$)</th>
                        </tr>
                        </thead>
                        <tbody class="tbody-parcelas">
                        <tr>
                            <td colspan="3" class="text-center">Parcelas não calculadas</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::label('observacao','Observação') !!}
                        {!! Form::textarea('observacao', null, ['class'=>'form-control', 'rows'=>'2']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $('.select-pessoa').val([]);
</script>
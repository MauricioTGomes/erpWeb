@extends('layouts/app')
@section('conteudo')

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <a href="{{ route('movimentacao.incluir') }}" class="btn btn-effect-ripple btn-success">
                    <i class="fa fa-plus"></i> Adicionar movimentação
                </a>
            </h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="dataTable" class="table table-bordered table-striped dataTable" role="grid">
                            <thead>
                            <tr>
                                <th width="20%">Data e hora</th>
                                <th width="30%">Descrição</th>
                                <th width="20%">Valor</th>
                                <th width="20%">Usuário</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Totalizador</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <table class="table table-striped table-bordered table-responsive table-hover tabela-pesquisa">
                    <thead>
                        <tr>
                            <th colspan="2">Caixa</th>
                        </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td width="65%">Total de entradas</td>
                        <td width="35%" class="text-right">R$ {{ formatValueForUser($parametros['entradas']) }}</td>
                    </tr>
                    <tr>
                        <td>Total de saídas</td>
                        <td class="text-right">R$ {{ formatValueForUser($parametros['saidas']) }}</td>
                    </tr>
                    <tr>
                        <td>Total de contas recebidas</td>
                        <td class="text-right">R$ {{ formatValueForUser($parametros['contasReceber']) }}</td>
                    </tr>
                    <tr>
                        <td>Total de contas pagas</td>
                        <td class="text-right">R$ {{ formatValueForUser($parametros['contasPagar']) }}</td>
                    </tr>
                    <tr>
                        <td><b>Saldo do caixa</b></td>
                        <td class="text-right"><b>R$ {{ formatValueForUser($parametros['total']) }}</b></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            $('#dataTableSimples').DataTable()
            $('#dataTable').DataTable({
                'paging'      : true,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : true,
                'info'        : true,
                'autoWidth'   : false,
                'ajax': {
                    url: '{{route('movimentacao.datatable')}}'
                },
                'columns': [
                    {data: 'data', name: 'data'},
                    {data: 'descricao', name: 'descricao'},
                    {data: 'valor', name: 'valor'},
                    {data: 'user', name: 'user'}
                ],
                "language": {
                    "url": '{{asset('js/vendor/datatables/DataTable-pt-BR.json')}}'
                }
            })
        })
    </script>

@endsection
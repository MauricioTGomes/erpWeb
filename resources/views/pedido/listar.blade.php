@extends('layouts/app')
@section('conteudo')

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">
                <a href="{{ route('pedidos.incluir') }}" class="btn btn-effect-ripple btn-success">
                    <i class="fa fa-plus"></i> Adicionar novo pedido
                </a>
            </h3>
        </div>

        <div class="box-body">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="dataTable" class="table table-bordered table-striped dataTable" role="grid">
                            <thead>
                            <tr>
                                <th width="15%">Número</th>
                                <th width="20%">Nome cliente</th>
                                <th width="15">Quantidade de produtos</th>
                                <th width="15%">Status</th>
                                <th width="15%">Valor</th>
                                <th width="15%">Ações</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            $('#dataTable').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': false,
                'ajax': {
                    url: '{{route('pedidos.datatable')}}'
                },
                'columns': [
                    {data: 'numero', name: 'numero'},
                    {data: 'cliente', name: 'cliente'},
                    {data: 'qtd_produtos', name: 'qtd_produtos'},
                    {data: 'status', name: 'status', orderable: false, searchable: false},
                    {data: 'valor_venda', name: 'valor_venda'},
                    {data: 'action', name: 'Ações', orderable: false, searchable: false}
                ],
                "language": {
                    "url": '{{asset('js/vendor/datatables/DataTable-pt-BR.json')}}'
                }
            });
        })
    </script>

@endsection
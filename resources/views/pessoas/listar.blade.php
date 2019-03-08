@extends('layouts/app')
@section('conteudo')

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">
                <a href="{{ route('pessoas.incluir') }}" class="btn btn-effect-ripple btn-success">
                    <i class="fa fa-plus"></i> Adicionar pessoa
                </a>
            </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="dataTable" class="table table-bordered table-striped dataTable" role="grid">
                            <thead>
                            <tr>
                                <th width="30%">Nome / Razão social</th>
                                <th width="15%">CNPJ / CPF</th>
                                <th width="20%">Telefone</th>
                                <th width="15%">Ações</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
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
                    url: '{{route('pessoas.datatable')}}'
                },
                'columns': [
                    {data: 'nome', name: 'nome'},
                    {data: 'cpf', name: 'cpf'},
                    {data: 'fone', name: 'fone'},
                    {data: 'action', name: 'Ações', orderable: false, searchable: false}
                ],
                "language": {
                    "url": '{{asset('js/vendor/datatables/DataTable-pt-BR.json')}}'
                }
            })
        })
    </script>

@endsection
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
                                <th width="25%">Nome</th>
                                <th width="25%">Email</th>
                                <th width="15%">Comissao</th>
                                <th width="20%">Tipo</th>
                                <th width="15%">Açao</th>
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
            $('#dataTableSimples').DataTable()
            $('#dataTable').DataTable({
                'paging'      : true,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : true,
                'info'        : true,
                'autoWidth'   : false,
                'ajax': {
                    url: '{{route('datatable.usuario')}}'
                },
                'columns': [
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'porcentagem_comissao', name: 'porcentagem_comissao'},
                    {data: 'tipo', name: 'tipo'},
                    {data: 'action', name: 'action'}
                ],
                "language": {
                    "url": '{{asset('js/vendor/datatables/DataTable-pt-BR.json')}}'
                }
            })
        })
    </script>

@endsection
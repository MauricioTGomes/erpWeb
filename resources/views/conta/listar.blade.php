@extends('layouts/app')
@section('conteudo')

    <div id="app">
        <vc-contas
                :contas-listar="{{ $contas }}"
                tipo="{{ substr($_SERVER['REQUEST_URI'], 8, 7) == 'receber' ? 'R' : 'P' }}">
        </vc-contas>
    </div>

    <script>
        $(function () {
            $('#dataTable').DataTable({
                'paging'      : true,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : true,
                'info'        : true,
                'autoWidth'   : false,
                "language": {
                    "url": '{{asset('js/vendor/datatables/DataTable-pt-BR.json')}}'
                }
            });
        });

    </script>


@endsection
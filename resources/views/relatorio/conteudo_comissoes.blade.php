<html>
<head>
    <title>Nome do software - Relatório comissões</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="{{public_path('css/impressao_pdf.css')}}">
    <link rel="stylesheet" href="{{public_path('css/bootstrap.min.css')}}">
</head>

    @php
        $valorTotal = 0;
        $total = 0;
    @endphp

    <div class="row">
        <div class="col-xs-12">
            <table class="table table-condensed">
                <thead>
                <tr>
                    <th width="10%">Número</th>
                    <th width="30%">Nome</th>
                    <th width="20%" class="text-center">Valor unitário</th>
                    <th width="20%" class="text-center">Valor desconto</th>
                    <th width="20%" class="text-center">Valor total</th>
                </tr>
                </thead>

                <tbody>
                @foreach($pedidos as $pedido)
                @php
                    $valorTotal += $pedido->valor_liquido;
                    $total = $valorTotal * ($user->porcentagem_comissao / 100);
                @endphp
                    <tr>
                        <td class="text-center">{{$pedido->numero }}</td>
                        <td>{{$pedido->pessoa->nomeCompleto()}}</td>
                        <td class="text-center">{{ formatValueForUser($pedido->valor_total) }}</td>
                        <td class="text-center">{{ formatValueForUser($pedido->valor_desconto) }}</td>
                        <td class="text-center">{{formatValueForUser($pedido->valor_liquido)}}</td>
                    </tr>
                @endforeach

                <tr colspan="12">
                    <td class="text-center" colspan="2"><b>Valor total de venda</b></td>
                    <td class="text-center" colspan="1"><b>R$ {{formatValueForUser($valorTotal)}}</b></td>
                    <td class="text-center" colspan="1"><b>Valor de comissão</b></td>
                    <td class="text-center" colspan="1"><b>R$ {{formatValueForUser($total)}}</b></td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>


</body>

</html>
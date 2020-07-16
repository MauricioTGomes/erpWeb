@extends('layouts.app')

@section('conteudo')
{{--
@if (auth()->user()->tipo == 'GERENTE')
<div class="col-lg-12 col-xs-12">
    <div class="small-box bg-red">
        <div class="inner">
            <h3>Contas pagar</h3>
            <p>Vencimento até dia {{ (new \Carbon\Carbon($valores['dataBase']))->format('d/m/Y') }}</p>
            @if(is_null($parcelasP->first()))
                <p>Nenhuma conta com previsão de vencimento</p>
            @endif
            @foreach($parcelasP as $parcela)
                <p>Fornecedor: {{ $parcela->conta->pessoa->nomeCompleto() }} Vencimento: {{ $parcela->data_vencimento }} Valor R$: {{ $parcela->valor }}</p>
            @endforeach
        </div>
        <div class="icon">
        HOJE: R$ {{ formatValueForUser($valores['valorPagar']) }}
        </div>
        <a href="{{ route('contas.pagar.listar') }}" class="small-box-footer">
        Ver mais <i class="fa fa-arrow-circle-right"></i>
        </a>
    </div>
</div>
@endif

<div class="col-lg-12 col-xs-12">
    <div class="small-box bg-green">
        <div class="inner">
            <h3>Contas receber</h3>
            <p>Vencimento até dia {{ (new \Carbon\Carbon($valores['dataBase']))->format('d/m/Y') }}</p>
            @if(is_null($parcelasR->first()))
                <p>Nenhuma conta com previsão de vencimento</p>
            @endif
            @foreach($parcelasR as $parcela)
                <p>Cliente: {{ $parcela->conta->pessoa->nomeCompleto() }} Vencimento: {{ $parcela->data_vencimento }} Valor R$: {{ $parcela->valor }}</p>
            @endforeach
        </div>

        <div class="icon">
            HOJE: R$ {{ formatValueForUser($valores['valorReceber']) }}
        </div>
        <a href="{{ route('contas.receber.listar') }}" class="small-box-footer">
            Ver mais <i class="fa fa-arrow-circle-right"></i>
        </a>
    </div>
</div>
--}}
@endsection

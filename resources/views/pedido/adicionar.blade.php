@extends('layouts/app')
@section('conteudo')

    <div id="app">
        <vc-form-pedido
                @if(isset($pedido))
                    @if(!is_null($pedido->conta))
                        :parcelas-selecionada="{{ json_encode($pedido->conta->parcelas) }}"
                        forma-pag="PRAZO"
                    @endif
                    :pedido-selecionado="{{ json_encode($pedido) }}"
                @endif
        ></vc-form-pedido>
    </div>

@endsection
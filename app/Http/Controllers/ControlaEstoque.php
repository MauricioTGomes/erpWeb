<?php
/**
 * Created by PhpStorm.
 * User: mauricio
 * Date: 15/05/18
 * Time: 21:58
 */

namespace App\Http\Controllers;

use App\Conta;
use App\Itens;
use App\MovimentacaoCaixa;
use App\Parcela;
use App\Pedido;
use App\Produto;
use Illuminate\Support\Facades\Auth;
use Webpatser\Uuid\Uuid;

class ControlaEstoque {
	private $produtoModel;
	private $pedidoModel;
	private $contaModel;
	private $itemModel;
	private $parcelaModel;
	private $moviCaixaModel;

	public function __construct(Produto $produto, Pedido $pedido, Conta $conta, Itens $itens, Parcela $parcela, MovimentacaoCaixa $movimentacaoCaixa) {
		$this->produtoModel   = $produto;
		$this->pedidoModel    = $pedido;
		$this->contaModel     = $conta;
		$this->itemModel      = $itens;
		$this->parcelaModel   = $parcela;
		$this->moviCaixaModel = $movimentacaoCaixa;
	}

	public function baixaEstoque($item, $pedido) {
		$this->itemModel->create([
				'produto_id'     => $item['id'],
				'pedido_id'      => $pedido->id,
				'valor_total'    => $item['valor_total'],
				'quantidade'     => isset($item['quantidade'])?$item['quantidade']:1,
				'valor_unitario' => $item['valor_unitario'],
				'valor_desconto' => $item['valor_desconto']
			]);

		$produto              = $this->produtoModel->find($item['id']);
		$produto->qtd_estoque = $produto->qtd_estoque-$item['quantidade'];
		$produto->update($produto->toArray());
	}

	public function retornaEstoque($item) {
		$produto              = $this->produtoModel->find($item->produto_id);
		$produto->qtd_estoque = $produto->qtd_estoque+$item->quantidade;
		$produto->update($produto->toArray());
		$item->delete();
	}

	private function verificaItemEliminado($itensNovos, $itemAntigo) {
		foreach ($itensNovos as $indexNovo => $itemNovo) {
			if ($itemNovo['produto']['id'] == $itemAntigo->id) {
				return $itemNovo;
			}
		}
		return false;
	}

	public function verificaAlteracaoEstoque($itensNovos, $pedido, $itensAntigos) {
		foreach ($itensAntigos as $indexAntigo => $itemAntigo) {
			$recriar = $this->verificaItemEliminado($itensNovos, $itemAntigo->produto);

			$this->retornaEstoque($itemAntigo);

			if (is_array($recriar)) {
				$this->baixaEstoque($recriar, $pedido);
			}
		}

		foreach ($itensNovos as $item) {
			if (isset($item['baixar']) && $item['baixar']) {
				$this->baixaEstoque($item, $pedido);
			}
		}
	}

	private function controlaFormaPagPedido($pedido, $input) {
		if ($input['formaPagamento'] == 'VISTA') {
			$this->moviCaixaModel->create([
					'pedido_id'      => $pedido->id,
					'user_id'        => Auth::user()->id,
					'valor_total'    => $pedido->valor_total,
					'valor_desconto' => $pedido->valor_desconto,
					'valor_pago'     => $pedido->valor_liquido,
					'descricao'      => "Lançamento pedido de número: ".$pedido->numero." para cliente ".$pedido->pessoa->nomeCompleto(),
					'estornado'      => '0'
				]);
		} else {
			$conta = $this->contaModel->create([
					'pessoa_id'     => $pedido->pessoa_id,
					'pedido_id'     => $pedido->id,
					'titulo'        => strtoupper(Uuid::generate()),
					'data_emissao'  => $input['pedido']['primeira_cobranca'],
					'user_id'       => Auth::user()->id,
					'vlr_total'     => $input['pedido']['valor_liquido'],
					'vlr_restante'  => $input['pedido']['valor_liquido'],
					'qtd_parcelas'  => $input['pedido']['nro_parcelas'],
					'observacao'    => isset($input['pedido']['observacoes'])?$input['pedido']['observacoes']:null,
					'tipo_operacao' => 'R', // P ou R
					'qtd_dias'      => $input['pedido']['qtd_dias']
				]);
			foreach ($input['parcelas'] as $index => $parcela) {
				$this->parcelaModel->create([
						'nro_parcela'      => $index,
						'valor'            => $parcela['valor'],
						'valor_pago'       => '0.00',
						'data_vencimento'  => $parcela['data_vencimento'],
						'data_recebimento' => null,
						'valor_desconto'   => '0.00',
						'conta_id'         => $conta->id
					]);
			}
		}

	}

	public function controlaMovimentacaoFianceira($pedido, $input = false, $deletar = false) {
		if ($deletar) {
			if (is_null($pedido->conta)) {
				$movimentação            = $pedido->movimentacaoCaixa->first();
				$movimentação->pedido_id = null;
				$movimentação->update($movimentação->toArray());
				$this->moviCaixaModel->create([
						'user_id'        => Auth::user()->id,
						'valor_total'    => $movimentação->valor_total,
						'valor_desconto' => $movimentação->valor_desconto,
						'valor_pago'     => $movimentação->valor_pago,
						'descricao'      => "Estorno pedido de número: ".$pedido->numero." para cliente ".$pedido->pessoa->nomeCompleto(),
						'estornado'      => '1'
					]);
			} else {
				$pedido->conta->parcelas()->delete();
				$pedido->conta()->delete();
			}
			$pedido->estornado = '1';
			$pedido->update($pedido->toArray());
		}

		if (is_array($input)) {
			$this->controlaFormaPagPedido($pedido, $input);
		}
	}
}
<?php

namespace App\Http\Controllers;

use App\Conta;

use App\Itens;
use App\MovimentacaoCaixa;
use App\Parcela;
use App\Pedido;
use App\Pessoa;
use App\Produto;
use Artesaos\SEOTools\Facades\SEOTools;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;
use Yajra\DataTables\Facades\DataTables;

class PedidoController extends Controller {

	private $produtoModel;
	private $pedidoModel;
	private $contaModel;
	private $itemModel;
	private $parcelaModel;
	private $moviCaixaModel;
	private $controlaEstoque;

	public function __construct(ControlaEstoque $controlaEstoque, Produto $produto, Pedido $pedido, Conta $conta, Itens $itens, Parcela $parcela, MovimentacaoCaixa $movimentacaoCaixa) {
		$this->produtoModel    = $produto;
		$this->pedidoModel     = $pedido;
		$this->contaModel      = $conta;
		$this->itemModel       = $itens;
		$this->parcelaModel    = $parcela;
		$this->moviCaixaModel  = $movimentacaoCaixa;
		$this->controlaEstoque = $controlaEstoque;
		$this->middleware('auth');
	}

	public function gravar(Request $request) {
		try {
			DB::beginTransaction();
			$input = $request->all();

			$pedido = $this->pedidoModel->create([
					'pessoa_id'          => $input['pessoaId'],
					'user_abertura_id'   => Auth::user()->id,
					'user_fechamento_id' => $input['faturado'] == 'true'?Auth::user()->id:null,
					'valor_total'        => $input['pedido']['valor'],
					'valor_desconto'     => $input['pedido']['valor_desconto'],
					'valor_liquido'      => $input['pedido']['valor_liquido'],
					'qtd_produtos'       => count($input['itens']),
					'faturado'           => $input['faturado'] == 'true'?'1':'0',
					'data_faturamento'   => $input['faturado'] == 'true'?date('d/m/Y'):null,
					'numero'             => count($this->pedidoModel->all())+1,
					'observacoe'         => isset($input['pedido']['observacoes'])?$input['pedido']['observacoes']:''
				]);

			foreach ($input['itens'] as $item) {
				$this->controlaEstoque->baixaEstoque($item, $pedido);
			}

			if ($input['faturado'] == 'true') {
				$this->controlaEstoque->controlaMovimentacaoFianceira($pedido, $input);
			}
			DB::commit();
			return response()->json(['erro' => 0, 'msg' => "Pedido emitido com sucesso!  Deseja imprimir o pedido?", 'pedido' => $pedido]);
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json(['erro' => 1, 'msg' => $e->getMessage()]);
		}

	}

	public function imprimePedido($id) {
		$parametros['pedido'] = $this->pedidoModel->find($id);
		$snappy               = App::make('snappy.pdf.wrapper');
		$snappy->setOption('header-html', view('layouts.header_relatorios')->render());
		$snappy->setOption('footer-html', view('layouts.footer_relatorios')->render());
		$snappy->loadView('pedido.conteudo', $parametros);

		return $snappy->download('Pedido - '.$parametros['pedido']->numero);
	}

	public function deletar($id) {
		try {
			DB::beginTransaction();
			$pedido = $this->pedidoModel->find($id);
			if (isset($pedido->conta) && !is_null($pedido->conta->parcelasPagas->first())) {
				throw new Exception("Venda com movimentação financeira.");
			} else {
				foreach ($pedido->itens as $item) {
					$this->controlaEstoque->retornaEstoque($item);
				}
				$this->controlaEstoque->controlaMovimentacaoFianceira($pedido, false, true);

			}
			DB::commit();
			return response()->json(['erro' => 0, 'mensagem' => "Sucesso ao eliminar pedido"]);
		} catch (\Exception $exception) {
			DB::rollBack();
			return response()->json(['erro' => 1, 'mensagem' => "Erro ao eliminar, ".$exception->getMessage()]);
		}
	}

	public function update($id, Request $request) {
		try {
			DB::beginTransaction();
			$input          = $request->all();
			$pedidoAnterior = $this->pedidoModel->find($id);
			$this->controlaEstoque->verificaAlteracaoEstoque($request->input('itens'), $pedidoAnterior, $pedidoAnterior->itens);

			$this->controlaEstoque->controlaMovimentacaoFianceira($pedidoAnterior, $input);

			$pedidoAnterior->user_fechamento_id = $input['faturado']?Auth::user()->id:null;
			$pedidoAnterior->valor_total        = $input['pedido']['valor'];
			$pedidoAnterior->valor_desconto     = $input['pedido']['valor_desconto'];
			$pedidoAnterior->valor_liquido      = $input['pedido']['valor_liquido'];
			$pedidoAnterior->qtd_produtos       = count($input['itens']);
			$pedidoAnterior->faturado           = $input['faturado'] == 'false'?'0':'1';
			$pedidoAnterior->data_faturamento   = $input['faturado'] == 'true'?date('d/m/Y'):null;
			$pedidoAnterior->update($pedidoAnterior->toArray());
			DB::commit();
			return response()->json(['erro' => 0, 'msg' => 'Sucesso ao alterar pedido, deseja imprimir?', 'pedido' => $pedidoAnterior]);
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json(['erro' => 1, 'msg' => $e->getMessage(), 'pedido' => $pedidoAnterior]);
		}

	}

	public function listar() {
		SEOTools::setTitle('Listagem de pedido');
		return view('pedido/listar');
	}

	public function getFormIncluir() {
		SEOTools::setTitle('Adicionar novo pedido');
		return view('pedido/adicionar');
	}

	public function getFormAlterar($id) {
		$pedido = $this->pedidoModel->newQuery()->where('id', $id)->with('itens.produto', 'conta.parcelas')->get()->first();
		if ($pedido->faturado) {
			throw new Exception("Pedido não pode ser alterado pois já foi faturado!");

		}
		SEOTools::setTitle('Alterando pedido de número: '.$pedido->numero);
		SEOTools::setDescription('Cliente: '.$pedido->pessoa->nomeCompleto());
		return view('pedido/adicionar', compact('pedido'));
	}

	public function datatableAjax() {
		$query = $this->pedidoModel->newQuery()->where('estornado', '0')->get();
		return Datatables::of($query)
			->editColumn('numero', function ($registro) {
				return $registro->numero;
			})
			->editColumn('cliente', function ($registro) {
				if (is_null($registro->pessoa)) {
					return 'Não informado';
				}
				return $registro->pessoa->nomeCompleto();
			})
			->editColumn('qtd_produtos', function ($registro) {
				return $registro->qtd_produtos;
			})
			->addColumn('status', function ($registro) {
				if ($registro->faturado) {
					return 'FATURADO';
				}
				return 'CONDICIONAL';
			})
			->editColumn('valor_venda', function ($registro) {
				return formatValueForUser($registro->valor_liquido);
			})
			->addColumn('action', function ($registro) {
				$string = '<a a-href="/pedidos/deletar/'.$registro->id.'" title="Excluir"
                           class="btn-confirm-operation btn btn-effect-ripple btn-xs btn-danger"
                           data-original-title="Deletar"><i class="fa fa-times"></i></a>

                           <a href="/pedidos/imprimir/'.$registro->id.'" title="Imprimir"
                           class="btn btn-effect-ripple btn-xs btn-info"
                           data-original-title="Imprimir"><i class="fa fa-print"></i></a>
                           ';

				if (!$registro->faturado) {
					return '<a href="/pedidos/alterar/'.$registro->id.'" title="Alterar"
                           class="btn btn-effect-ripple btn-xs btn-success"
                           data-original-title="Alterar"><i class="fa fa-pencil"></i></a> '.$string;
				}

				return $string;
			})
			->make(true);
	}

	public function atualizaValoresPedido(Request $request) {
		$item       = $request->input('item');
		$desconto   = isset($item['valor_desconto'])?formatValueForMysql($item['valor_desconto']):0.00;
		$quantidade = isset($item['quantidade'])?$item['quantidade']:1;

		$valorTotal = (formatValueForMysql($item['valor_unitario'])*$quantidade)-$desconto;

		$item['valor_total']    = formatValueForUser($valorTotal);
		$item['quantidade']     = $quantidade;
		$item['valor_desconto'] = $desconto;

		return response()->json($item);
	}

	public function calculaParcelas(Request $request) {
		$pedido = $request->input('pedido');

		$vlrTotal    = formatValueForMysql($pedido['valor_liquido']);
		$qtdDias     = $pedido['qtd_dias'];
		$qtdParcelas = $pedido['nro_parcelas'];
		$dataEmissao = Carbon::createFromFormat('d/m/Y', $pedido['primeira_cobranca']);

		$vlr_parcela   = $vlrTotal/$qtdParcelas;
		$arrayParcelas = [];
		$somaParcelas  = 0;

		for ($i = 1; $i <= $qtdParcelas; $i++) {
			$dataParcela = $dataEmissao->addDays($qtdDias);
			array_push($arrayParcelas, [
					'data_vencimento' => $dataParcela->format('d/m/Y'),
					'valor'           => number_format(round($vlr_parcela, 2), 2, ',', '.'),
					'nro_parcela'     => $i]);
			$somaParcelas += round($vlr_parcela, 2);
		}

		$diferenca = number_format($vlrTotal-$somaParcelas, 2);

		if ($diferenca > 0) {
			$arrayParcelas[$qtdParcelas-1]['valor'] = number_format(
				formatValueForMysql($arrayParcelas[$qtdParcelas-1]['valor'])+$diferenca, 2, ',', '.');
		}
		if ($diferenca < 0) {
			$arrayParcelas[$qtdParcelas-1]['valor'] = number_format(
				formatValueForMysql($arrayParcelas[$qtdParcelas-1]['valor'])-($diferenca*-1), 2, ',', '.');
		}

		return $arrayParcelas;
	}

}

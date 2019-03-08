<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovimentacaoRequest;
use App\MovimentacaoCaixa;
use App\Parcela;

use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;

use Yajra\DataTables\Facades\DataTables;

class MovimentacaoController extends Controller {

	private $movimentacaoModel;
	private $parcelaModel;

	public function __construct(MovimentacaoCaixa $movimentação, Parcela $parcelaModel) {
		$this->middleware('auth');
		$this->movimentacaoModel = $movimentação;
		$this->parcelaModel      = $parcelaModel;
	}

	public function datatableAjax() {
		$query = $this->movimentacaoModel->all();
		return Datatables::of($query)
			->editColumn('data', function ($registro) {
				return $registro->created_at->format('d/m/Y h:m:s');
			})
			->editColumn('descricao', function ($registro) {
				return $registro->descricao;
			})
			->editColumn('valor', function ($registro) {
				return formatValueForUser($registro->valor_pago);
			})
			->editColumn('user', function ($registro) {
				return $registro->user->name;
			})
			->make(true);
	}

	public function listar() {
		if (Auth::user()  ->tipo != 'gerente') {
			return redirect()->route('index')->with(['erro' => "Seu usuário não tem permissão para acessar esta área"]);
		}
		SEOTools::setTitle('Controle de caixa');
		$movimentacoes = $this->movimentacaoModel->all();
		$parametros    = [
			'entradas'      => 0,
			'saidas'        => 0,
			'contasReceber' => 0,
			'contasPagar'   => 0,
			'total'         => 0
		];

		foreach ($movimentacoes as $key => $movi) {
			if (is_null($movi->parcela) && $movi->estornado == '0') {
				$parametros['entradas'] += $movi->valor_pago;
			}
			if (is_null($movi->parcela) && $movi->estornado == '1') {
				$parametros['saidas'] += $movi->valor_pago;
			}
			if (!is_null($movi->parcela) && $movi->parcela->conta->tipo_operacao == 'P') {
				$parametros['contasPagar'] += $movi->valor_pago;
			}
			if (!is_null($movi->parcela) && $movi->parcela->conta->tipo_operacao == 'R') {
				$parametros['contasReceber'] += $movi->valor_pago;
			}
			$parametros['total'] += $movi->valor_total;
		}
		return view('caixa/listar', compact('parametros'));
	}

	public function getFormIncluir() {
		if (Auth::user()  ->tipo != 'gerente') {
			return redirect()->route('index')->with(['erro' => "Seu usuário não tem permissão para acessar esta área"]);
		}

		SEOTools::setTitle('Adicionar movimentação financeira');
		return view('caixa.adicionar');
	}

	public function gravar(MovimentacaoRequest $request) {
		try {
			DB::beginTransaction();
			$input = $request->all();
			if ($input['valor'] == '0,00') {
				throw new Exception("Valor não pode ser menor ou igual a zero.");
			}
			$input['user_id']     = Auth::user()->id;
			$input['valor_pago']  = $input['valor'];
			$input['valor_total'] = $input['valor'];
			MovimentacaoCaixa::create($input);
			DB::commit();
			return redirect()->route('movimentacao.listar')->with(['sucesso' => "Sucesso ao lançar movimentação"]);
		} catch (\Exception $e) {
			DB::rollback();
			return back()->with('erro', 'Erro ao lançar movimentação'."\n".$e->getMessage());
		}
	}
}
<?php

namespace App\Http\Controllers;

use App\Conta;
use App\MovimentacaoCaixa;
use App\Parcela;
use App\Pedido;
use App\User;
use Artesaos\SEOTools\Facades\SEOTools;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;
use Yajra\DataTables\Facades\DataTables;

class IndexController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	private $pedidoModel;

	public function __construct(Pedido $pedidoModel) {
		$this->pedidoModel = $pedidoModel;
		$this->middleware('auth');
	}

	public function register() {
		SEOTools::setTitle('Cadastrar usuário');
		return view('auth/register');
	}

	public function listar() {
		SEOTools::setTitle('Listar usuários');
		return view('auth/listar');
	}

	public function alterar($id) {
		SEOTools::setTitle('Alterar dados');
		try {
			$user = User::find($id);
			if (auth()->user()->tipo != 'GERENTE' && $user->id != auth()->user()->id) {
				throw new Exception("usuário com movimentação financeira, não é possível elimina-lo");
			}
			return view('auth/alterar', compact('user'));
		} catch (\Exception $exception) {
			return redirect()->route('index')->with(['erro' => "Erro ao alterar usuário ".$exception->getMessage()]);
		}
	}

	public function update($id, Request $request) {
		$usuario = User::find($id);
		$input   = $request->all();

		try {
			DB::beginTransaction();
			if (!password_verify($input['password_antigo'], $usuario->password)) {
				throw new Exception("Senha antiga não confere");
			}

			if (!$input['password'] == $input['password2']) {
				throw new Exception("Senhas novas não conferem");
			}
			$input['password']  = bcrypt($input['password']);
			$input['password2'] = bcrypt($input['password2']);
			$usuario->update($input);
			DB::commit();
			return redirect()->route('index')->with(['sucesso' => "Usuário alterado com sucesso."]);
		} catch (\Exception $e) {
			DB::rollBack();
			return back()->with('erro', "Erro ao salvar usuário."."\n".$e->getMessage())->withInput();
		}
	}

	protected function create(Request $request) {
		try {
			DB::beginTransaction();
			$input = $request->all();
			if ($input['password'] != $input['password2']) {
				throw new Exception("Senhas não conferem");
			}
			$input['password']  = bcrypt($input['password']);
			$input['password2'] = bcrypt($input['password2']);
			User::create($input);
			DB::commit();
			return redirect()->route('index')->with(['sucesso' => "Usuário cadastrada com sucesso."]);
		} catch (\Exception $e) {
			DB::rollBack();
			return back()->with('erro', "Erro ao salvar usuário."."\n".$e->getMessage())->withInput();
		}
	}

	public function index(Conta $contaModel, Parcela $parcelaModel, MovimentacaoCaixa $moviCaixaModel) {
		SEOTools::setTitle('Inicial');
		$dia       = Carbon::now()->addDays(5)->format('Y-m-d');
		$parcelasR = $parcelaModel->getTotalDia('R', $dia);
		$parcelasP = $parcelaModel->getTotalDia('P', $dia);
		$valores   = ['valorReceber' => 0, 'valorPagar' => 0, 'dataBase' => $dia];
		foreach ($parcelasR as $parcela) {
			if ($parcela->data_vencimento > Carbon::now()->format('Y-m-d')) {
				continue;
			}
			$valores['valorReceber'] += $parcela['valor'];
		}

		foreach ($parcelasP as $parcela) {
			if ($parcela->data_vencimento > Carbon::now()->format('Y-m-d')) {
				continue;
			}
			$valores['valorPagar'] += $parcela['valor'];
		}
		return view('home', compact('valores', 'parcelasP', 'parcelasR'));
	}

	public function getFormRelatorio() {
		SEOTools::setTitle('Relatório de comissões');
		$usuarios = User::all();
		return view('relatorio/form_comissoes', compact('usuarios'));
	}

	public function imprimir(Request $request) {
		$parametros['user']    = User::find($request->input('user_id'));
		$parametros['pedidos'] = $this->pedidoModel->buscaRelatorio($request->input('user_id'), $request->input('data_final'), $request->input('data_inicial'));
		$snappy                = App::make('snappy.pdf.wrapper');
		$snappy->setOption('header-html', view('layouts.header_relatorios')->render());
		$snappy->setOption('footer-html', view('layouts.footer_relatorios')->render());
		$snappy->loadView('relatorio.conteudo_comissoes', $parametros);

		return $snappy->download('Usuário - '.$parametros['user']->name);
	}

	public function deletaUser($id) {
		try {
			DB::beginTransaction();
			$user = User::find($id);
			$user->load('pedido', 'conta', 'movimentacao');
			if (!is_null($user->pedido->first()) || !is_null($user->conta->first()) || !is_null($user->movimentacao->first())) {
				throw new Exception("Usuário com movimentação financeira, não é possível elimina-lo");
			}
			$user->delete();
			DB::commit();
			return response()->json(['erro' => 0, 'mensagem' => "Sucesso ao eliminar usuário"]);
		} catch (\Exception $exception) {
			DB::rollBack();
			return response()->json(['erro' => 1, 'mensagem' => "Erro ao eliminar, ".$exception->getMessage()]);
		}
	}

	public function datatableAjax() {
		$query = User::all();
		return Datatables::of($query)
			->editColumn('name', function ($registro) {
				return $registro->name;
			})
			->editColumn('email', function ($registro) {
				return $registro->email;
			})
			->editColumn('porcentagem_comissao', function ($registro) {
				return $registro->porcentagem_comissao;
			})
			->editColumn('tipo', function ($registro) {
				return $registro->tipo == 'GERENTE' ? 'Gerente' : 'Vendedor';
			})
			->addColumn('action', function ($registro) {
				return '    <a a-href="/auth/deletar/'.$registro->id.'" title="Excluir"
                           class="btn-confirm-operation btn btn-effect-ripple btn-xs btn-danger"
                           data-original-title="Deletar"><i class="fa fa-times"></i></a>
                           <a href="/auth/alterar/'.$registro->id.'" title="Alterar"
                           class="btn btn-effect-ripple btn-xs btn-success"
                           data-original-title="Alterar"><i class="fa fa-pencil"></i></a>';
			})
			->make(true);
	}

}

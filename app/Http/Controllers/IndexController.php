<?php

namespace App\Http\Controllers;

use App\Pedido;
use App\User;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;

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

	public function alterar($id) {
		SEOTools::setTitle('Alterar dados');
		if ($id != auth()->user()->id) {
			throw new Exception("Operação não permitida");
		}
		$user = User::find($id);
		return view('auth/alterar', compact('user'));
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
			$data = $request->all();
			if ($data->password != $data->password2) {
				throw new Exception("Senhas não conferem");
			}
			User::create();
			DB::commit();
			return redirect()->route('index')->with(['sucesso' => "Usuário cadastrada com sucesso."]);
		} catch (\Exception $e) {
			DB::rollBack();
			return back()->with('erro', "Erro ao salvar usuário."."\n".$e->getMessage())->withInput();
		}
	}

	public function index() {
		SEOTools::setTitle('Inicial');
		return view('home');
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

}

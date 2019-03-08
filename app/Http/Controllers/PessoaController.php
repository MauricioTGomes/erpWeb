<?php

namespace App\Http\Controllers;

use App\Cidade;
use App\Http\Requests\PessoaRequest;
use App\Pessoa;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;
use Yajra\DataTables\Facades\DataTables;

class PessoaController extends Controller {

	private $pessoaModel;

	public function __construct(Pessoa $pessoa) {
		$this->pessoaModel = $pessoa;
		$this->middleware('auth');
	}

	public function gravar(PessoaRequest $request) {
		try {
			DB::beginTransaction();
			Pessoa::create($request->all());
			DB::commit();
			return redirect()->route('pessoas.listar')->with(['sucesso' => "Sucesso ao gravar pessoa"]);
		} catch (\Exception $e) {
			DB::rollback();
			return back()->with('erro', 'Erro ao gravar pessoa'."\n".$e->getMessage());
		}

	}

	public function deletar($id) {
		try {
			DB::beginTransaction();
			$pessoa = $this->pessoaModel->find($id);
			if (!is_null($pessoa->pedidos->first()) || !is_null($pessoa->contas->first())) {
				throw new Exception("pessoa com movimentação financeira, cancele e apague as contas e vendas antes de continuar");
			}
			$pessoa->delete();
			DB::commit();
			return response()->json(['erro' => 0, 'mensagem' => "Sucesso ao eliminar pessoa"]);
		} catch (\Exception $exception) {
			DB::rollBack();
			return response()->json(['erro' => 1, 'mensagem' => "Erro ao eliminar, ".$exception->getMessage()]);
		}
	}

	public function update($id, PessoaRequest $request) {
		try {
			DB::beginTransaction();
			$pessoa = $this->pessoaModel->find($id);
			$pessoa->update($request->all());
			DB::commit();
			return redirect()->route('pessoas.listar')->with(['sucesso' => "Sucesso ao editar pessoa"]);
		} catch (\Exception $e) {
			DB::rollback();
			return back()->with('erro', 'Erro ao editar pessoa'."\n".$e->getMessage());
		}

	}

	public function listar() {
		SEOTools::setTitle('Listagem de pessoas');
		return view('pessoas/listar');
	}

	public function getFormIncluir(Cidade $cidade) {
		SEOTools::setTitle('Adicionar pessoas');
		$cidades = $cidade->all();
		return view('pessoas/adicionar', compact('cidades'));
	}

	public function buscaPessoa(Request $request) {

		if ($request->input('tipo') == 'unico') {
			$pessoa = $this->pessoaModel->find($request->input('id'));
			return response()->json($pessoa);
		}

		$pessoas     = $this->pessoaModel->buscaPessoasPesquisa($request->input('term'));
		$arrayPessoa = [];
		foreach ($pessoas as $pessoa) {
			array_push($arrayPessoa, ['id' => $pessoa->id, 'text' => $pessoa->nomeCompleto().' - '.$pessoa->cpfCnpj()]);
		}
		return response()->json(['pessoas' => $arrayPessoa]);
	}

	public function getFormAlterar($id, Cidade $cidade) {
		SEOTools::setTitle('Alterar pessoa');
		$pessoa  = $this->pessoaModel->find($id);
		$cidades = $cidade->all();
		return view('pessoas/editar', compact('cidades', 'pessoa'));
	}

	public function datatableAjax() {
		$query = $this->pessoaModel->all();
		return Datatables::of($query)
			->editColumn('nome', function ($registro) {
				return $registro->nomeCompleto();
			})
			->editColumn('cpf', function ($registro) {
				return $registro->cpfCnpj();
			})
			->editColumn('fone', function ($registro) {
				return $registro->fone;
			})
			->addColumn('action', function ($registro) {
				return '    <a a-href="/pessoas/deletar/'.$registro->id.'" title="Excluir"
                           class="btn-confirm-operation btn btn-effect-ripple btn-xs btn-danger"
                           data-original-title="Deletar"><i class="fa fa-times"></i></a>
                           <a href="/pessoas/alterar/'.$registro->id.'" title="Alterar"
                           class="btn btn-effect-ripple btn-xs btn-success"
                           data-original-title="Alterar"><i class="fa fa-pencil"></i></a>';
			})
			->make(true);
	}

}

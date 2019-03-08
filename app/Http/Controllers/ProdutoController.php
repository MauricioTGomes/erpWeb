<?php

namespace App\Http\Controllers;

use App\Cidade;
use App\Http\Requests\PessoaRequest;
use App\Http\Requests\ProdutoRequest;
use App\Produto;
use Artesaos\SEOTools\Facades\SEOTools;
use App\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;
use Yajra\DataTables\Facades\DataTables;

class ProdutoController extends Controller {

    private $produtoModel;

	public function __construct(Produto $produto) {
	    $this->produtoModel = $produto;
		$this->middleware('auth');
	}

	public function buscaProduto(Request $request) {

	    if ($request->input('tipo') == 'unico') {
	        $produto = $this->produtoModel->find($request->input('id'));
            return response()->json($produto);
        }

        $produtos = $this->produtoModel->buscaProdutosPesquisa($request->input('term'), $request->input('ativo'));
        $arrayProdutos = [];
        foreach ($produtos as $prod) {
            array_push($arrayProdutos, ['id'=>$prod->id, 'text'=> 'Código: ' . $prod->codigo . '  Nome: ' . $prod->nome]);
        }
        return response()->json(['produtos' => $arrayProdutos]);
    }

	public function gravar(ProdutoRequest $request){
        try {
            DB::beginTransaction();
	        Produto::create($request->all());
            DB::commit();
            return redirect()->route('produtos.listar')->with(['sucesso' => "Sucesso ao gravar produto"]);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('erro', 'Erro ao gravar produto' . "\n" . $e->getMessage());
        }

    }

    public function deletar($id){
        try {
            DB::beginTransaction();
            if (false) {
                throw new Exception("Produto relacionado com alguma venda, inative o mesmo no editar.");
            }
            $this->produtoModel->find($id)->delete();
            DB::commit();
            return response()->json(['erro' => 0, 'mensagem' => "Sucesso ao eliminar produto"]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['erro' => 1, 'mensagem' => "Erro ao eliminar, " . $exception->getMessage()]);
        }
    }

    public function update($id, ProdutoRequest $request){
        try {
            DB::beginTransaction();
            $produto = $this->produtoModel->find($id);
            $produto->update($request->all());
            DB::commit();
            return redirect()->route('produtos.listar')->with(['sucesso' => "Sucesso ao editar produto"]);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('erro', 'Erro ao editar produto' . "\n" . $e->getMessage());
        }

    }

    public function listar() {
        SEOTools::setTitle('Listagem de produtos');
        return view('produtos/listar');
    }

    public function getFormIncluir(){
        SEOTools::setTitle('Adicionar produto');
        return view('produtos/adicionar');
    }

    public function getFormAlterar($id){
        SEOTools::setTitle('Alterar produto');
        $produto = $this->produtoModel->find($id);
        return view('produtos/editar', compact('produto'));
    }

    public function datatableAjax()
    {
        $query = $this->produtoModel->all();
        return Datatables::of($query)
            ->editColumn('codigo', function ($registro) {
                return $registro->codigo;
            })
            ->editColumn('nome', function ($registro) {
                return $registro->nome;
            })
            ->editColumn('quantidade', function ($registro) {
                return $registro->qtd_estoque;
            })
            ->editColumn('valor_venda', function ($registro) {
                return $registro->valor_venda;
            })
            ->addColumn('action', function ($registro) {
                return '    <a a-href="/produtos/deletar/' . $registro->id . '" title="Excluir"
                           class="btn-confirm-operation btn btn-effect-ripple btn-xs btn-danger"
                           data-original-title="Deletar"><i class="fa fa-times"></i></a>
                           <a href="/produtos/alterar/' . $registro->id . '" title="Alterar"
                           class="btn btn-effect-ripple btn-xs btn-success"
                           data-original-title="Alterar"><i class="fa fa-pencil"></i></a>';
            })
            ->make(true);
    }

}

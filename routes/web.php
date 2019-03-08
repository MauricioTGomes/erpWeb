<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Auth::routes();

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/', ['as' => 'index', 'uses' => 'IndexController@index']);

Route::group(['where'                 => ['id'                 => '[0-9]+', 'middleware'                 => 'auth']], function () {
		Route::group(['prefix'              => 'auth'], function () {
				Route::get('/register', ['as'     => 'cadastrar.usuario', 'uses'     => 'IndexController@register']);
				Route::get('/alterar/{id}', ['as' => 'alterar.usuario', 'uses' => 'IndexController@alterar']);
				Route::post('/register', ['as'    => 'gravar.usuario', 'uses'    => 'IndexController@create']);
				Route::post('/update/{id}', ['as' => 'update.usuario', 'uses' => 'IndexController@update']);
			});

		Route::group(['prefix'                    => 'pessoas'], function () {
				Route::get('/listar', ['as'             => 'pessoas.listar', 'uses'             => 'PessoaController@listar']);
				Route::get('/incluir', ['as'            => 'pessoas.incluir', 'uses'            => 'PessoaController@getFormIncluir']);
				Route::get('/alterar/{id}', ['as'       => 'pessoas.alterar', 'uses'       => 'PessoaController@getFormAlterar']);
				Route::post('/deletar/{id}', ['as'      => 'pessoas.alterar', 'uses'      => 'PessoaController@deletar']);
				Route::post('/gravar', ['as'            => 'pessoas.gravar', 'uses'            => 'PessoaController@gravar']);
				Route::post('/lists/getToSelect', ['as' => 'pessoas.alterar', 'uses' => 'PessoaController@buscaPessoa']);
				Route::post('/update/{id}', ['as'       => 'pessoas.update', 'uses'       => 'PessoaController@update']);
				Route::get('/listar/datatable', ['as'   => 'pessoas.datatable', 'uses'   => 'PessoaController@datatableAjax']);
			});

		Route::group(['prefix'                    => 'produtos'], function () {
				Route::get('/listar', ['as'             => 'produtos.listar', 'uses'             => 'ProdutoController@listar']);
				Route::get('/incluir', ['as'            => 'produtos.incluir', 'uses'            => 'ProdutoController@getFormIncluir']);
				Route::get('/alterar/{id}', ['as'       => 'produtos.alterar', 'uses'       => 'ProdutoController@getFormAlterar']);
				Route::post('/deletar/{id}', ['as'      => 'produtos.alterar', 'uses'      => 'ProdutoController@deletar']);
				Route::post('/gravar', ['as'            => 'produtos.gravar', 'uses'            => 'ProdutoController@gravar']);
				Route::post('/lists/getToSelect', ['as' => 'pedidos.alterar', 'uses' => 'ProdutoController@buscaProduto']);
				Route::post('/update/{id}', ['as'       => 'produtos.update', 'uses'       => 'ProdutoController@update']);
				Route::get('/listar/datatable', ['as'   => 'produtos.datatable', 'uses'   => 'ProdutoController@datatableAjax']);
			});

		Route::group(['prefix'                      => 'pedidos'], function () {
				Route::post('/lists/getToSelect', ['uses' => 'PedidoController@atualizaValoresPedido']);
				Route::get('/listar', ['as'               => 'pedidos.listar', 'uses'               => 'PedidoController@listar']);
				Route::get('/incluir', ['as'              => 'pedidos.incluir', 'uses'              => 'PedidoController@getFormIncluir']);
				Route::get('/imprimir/{id}', ['as'        => 'pedido.imprimir', 'uses'        => 'PedidoController@imprimePedido']);
				Route::get('/alterar/{id}', ['as'         => 'pedidos.alterar', 'uses'         => 'PedidoController@getFormAlterar']);
				Route::post('/deletar/{id}', ['as'        => 'pedidos.deletar', 'uses'        => 'PedidoController@deletar']);
				Route::post('/gravar', ['as'              => 'pedidos.gravar', 'uses'              => 'PedidoController@gravar']);
				Route::post('/update/{id}', ['as'         => 'pedidos.update', 'uses'         => 'PedidoController@update']);
				Route::post('/calculaParcelas', ['as'     => 'pedidos.calculaParcelas', 'uses'     => 'PedidoController@calculaParcelas']);
				Route::get('/listar/datatable', ['as'     => 'pedidos.datatable', 'uses'     => 'PedidoController@datatableAjax']);
			});

		Route::group(['prefix'                => 'contas'], function () {
				Route::group(['prefix'              => 'receber'], function () {
						Route::get('/listar', ['as'       => 'contas.receber.listar', 'uses'       => 'ContaController@listarReceber']);
						Route::get('/incluir', ['as'      => 'contas.receber.incluir', 'uses'      => 'ContaController@getFormAdicionarReceber']);
						Route::get('/alterar/{id}', ['as' => 'contas.receber.alterar', 'uses' => 'ContaController@getFormAlterarReceber']);
					});

				Route::group(['prefix'                  => 'pagar'], function () {
						Route::get('/listar', ['as'           => 'contas.pagar.listar', 'uses'           => 'ContaController@listarPagar']);
						Route::get('/incluir', ['as'          => 'contas.pagar.incluir', 'uses'          => 'ContaController@getFormAdicionarPagar']);
						Route::get('/alterar/{id}', ['as'     => 'contas.pagar.alterar', 'uses'     => 'ContaController@getFormAlterarPagar']);
						Route::get('/listar/datatable', ['as' => 'contas.pagar.datatable', 'uses' => 'ContaController@datatableAjax']);
					});
				Route::post('/deletar/{id}', ['as' => 'contas.deletar', 'uses' => 'ContaController@deletar']);
				Route::post('/buscaContas', ['as'  => 'contas.buscar', 'uses'  => 'ContaController@buscaContas']);
				Route::post('/gravar', ['as'       => 'contas.gravar', 'uses'       => 'ContaController@gravar']);
				Route::post('/update/{id}', ['as'  => 'contas.update', 'uses'  => 'ContaController@update']);

				Route::group(['prefix'                => 'parcelas/'], function () {
						Route::post('/buscaParcelas', ['as' => 'contas.buscar', 'uses' => 'ParcelaController@buscaParcelas']);
						Route::post('/calcular', ['as'      => 'parcelas.calcular', 'uses'      => 'ContaController@calculaParcela']);
						Route::post('/baixar', ['as'        => 'parcelas.baixar', 'uses'        => 'ParcelaController@baixarParcela']);
						Route::post('/estornar/{id}', ['as' => 'estornos.executa', 'uses' => 'ParcelaController@estornoParcela']);
					});
			});

		Route::group(['prefix'                  => 'movimentacao'], function () {
				Route::get('/listar', ['as'           => 'movimentacao.listar', 'uses'           => 'MovimentacaoController@listar']);
				Route::get('/incluir', ['as'          => 'movimentacao.incluir', 'uses'          => 'MovimentacaoController@getFormIncluir']);
				Route::post('/gravar', ['as'          => 'movimentacao.gravar', 'uses'          => 'MovimentacaoController@gravar']);
				Route::post('/deletar/{id}', ['as'    => 'movimentacao.deletar', 'uses'    => 'MovimentacaoController@deletar']);
				Route::get('/listar/datatable', ['as' => 'movimentacao.datatable', 'uses' => 'MovimentacaoController@datatableAjax']);
			});
		Route::group(['prefix'           => 'relatorios'], function () {
				Route::get('/comissao', ['as'  => 'relatorio.comissao', 'uses'  => 'IndexController@getFormRelatorio']);
				Route::post('/imprimir', ['as' => 'relatorio.gravar', 'uses' => 'IndexController@imprimir']);
			});

	});

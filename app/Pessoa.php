<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model {
	protected $table = 'pessoa';

	protected $fillable = [
		'nome',
		'razao_social',
		'fantasia',
		'cpf',
		'rg',
		'cnpj',
		'ie',
		'ativo',
		'fone',
		'email',
		'endereco',
		'cep',
		'endereco_nro',
		'bairro',
		'complemento',
		'sexo',
		'cliente',
		'tipo',
		'fornecedor',
		'cidade_id',
	];

	public function cidade() {
		return $this->hasOne(Cidade::class , 'id', 'cidade_id');
	}

	public function pedidos() {
		return $this->hasMany(Pedido::class , 'pessoa_id');
	}

	public function contas() {
		return $this->hasMany(Conta::class , 'pessoa_id');
	}

	public function nomeCompleto() {
		if ($this->nome == '') {
			return $this->razao_social.' - '.$this->fantasia;
		}
		return $this->nome;
	}

	public function nomeCompletoCpfCnpj() {
		if ($this->nome == '') {
			return 'Razão social/fantasia: '.$this->razao_social.' - '.$this->fantasia.' CNPJ: '.$this->cnpj;
		}
		return $this->nome.' - '.$this->cpf;
	}

	public function cpfCnpj() {
		if ($this->cpf == '') {
			return $this->cnpj;
		}
		return $this->cpf;
	}

	public function buscaPessoasPesquisa($parametro = null) {
		$query = $this->newQuery();

		if ($parametro == null) {
			return $query->paginate(10);
		}

		$query->where(function ($q) use ($parametro) {
				$q->where('ativo', '1')
					->where('nome', 'like', "%$parametro%")
				->orWhere('cpf', 'like', "%$parametro%");
			});

		return $query->paginate(10);
	}
}

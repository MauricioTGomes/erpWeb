<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model {

	protected $fillable = [
		'pessoa_id',
		'user_abertura_id',
		'user_fechamento_id',
		'valor_total',
		'valor_desconto',
		'valor_liquido',
		'qtd_produtos',
		'observacoes',
		'faturado',
		'estornado',
		'numero',
		'data_faturamento'
	];

	protected $dates = ['updated_at', 'created_at'];

	protected $table = 'pedidos';

	public function setValorTotalAttribute($value) {
		if (substr_count($value, ',') == 0) {
			return $this->attributes['valor_total'] = $value;
		} else {
			return $this->attributes['valor_total'] = formatValueForMysql($value);
		}
	}

	public function setValorDescontoAttribute($value) {
		if (substr_count($value, ',') == 0) {
			return $this->attributes['valor_desconto'] = $value;
		} else {
			return $this->attributes['valor_desconto'] = formatValueForMysql($value);
		}
	}

	public function setValorLiquidoAttribute($value) {
		if (substr_count($value, ',') == 0) {
			return $this->attributes['valor_liquido'] = $value;
		} else {
			return $this->attributes['valor_liquido'] = formatValueForMysql($value);
		}
	}

	public function conta() {
		return $this->belongsTo(Conta::class , 'id', 'pedido_id');
	}

	public function pessoa() {
		return $this->hasOne(Pessoa::class , 'id', 'pessoa_id');
	}

	public function movimentacaoCaixa() {
		return $this->hasMany(MovimentacaoCaixa::class , 'pedido_id');
	}

	public function itens() {
		return $this->hasMany(Itens::class , 'pedido_id');
	}

	public function buscaRelatorio($usuario, $dataFinal = null, $dataInicial = null) {
		$query = $this->newQuery();
		$query->where('user_abertura_id', $usuario)
		      ->where('faturado', 1);

		if (!is_null($dataInicial) && !is_null($dataFinal)) {
			$query->where('data_faturamento', '>=', $dataInicial)
			      ->where('data_faturamento', '<=', $dataFinal);
		}

		return $query->get();

	}

}

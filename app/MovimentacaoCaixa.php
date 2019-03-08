<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovimentacaoCaixa extends Model {

	protected $fillable = [
		'pedido_id',
		'user_id',
		'parcela_id',
		'valor_total',
		'valor_desconto',
		'valor_pago',
		'descricao',
		'tipo',
		'estornado'
	];

	protected $table = 'movimentacao_caixa';

	protected $dates = ['data_pagamento', 'data_estorno', 'updated_at', 'created_at'];

	public function setValorTotalAttribute($value) {
		if (substr_count($value, ',') == 0) {
			return $this->attributes['valor_total'] = $value;
		} else {
			return $this->attributes['valor_total'] = formatValueForMysql($value);
		}
	}

	public function setValorPagoAttribute($value) {
		if (substr_count($value, ',') == 0) {
			return $this->attributes['valor_pago'] = $value;
		} else {
			return $this->attributes['valor_pago'] = formatValueForMysql($value);
		}
	}

	public function setValorDescontoAttribute($value) {
		if (substr_count($value, ',') == 0) {
			return $this->attributes['valor_desconto'] = $value;
		} else {
			return $this->attributes['valor_desconto'] = formatValueForMysql($value);
		}
	}

	public function parcela() {
		return $this->belongsTo(Parcela::class , 'parcela_id');
	}

	public function pedido() {
		return $this->belongsTo(Pedido::class , 'pedido_id');
	}

	public function user() {
		return $this->belongsTo(User::class , 'user_id');
	}
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Itens extends Model {
	protected $table = 'itens';

	protected $fillable = array(
		'produto_id',
		'pedido_id',
		'valor_total',
		'quantidade',
		'valor_unitario',
		'valor_desconto',
	);

	public $timestamps    = false;
	protected $primaryKey = "id";

	public function produto() {
		return $this->hasOne(Produto::class , 'id', 'produto_id');
	}

	public function setValorTotalAttribute($value) {
		if (substr_count($value, ',') == 0) {
			return $this->attributes['valor_total'] = $value;
		} else {
			return $this->attributes['valor_total'] = formatValueForMysql($value);
		}
	}

	public function setValorUnitarioAttribute($value) {
		if (substr_count($value, ',') == 0) {
			return $this->attributes['valor_unitario'] = $value;
		} else {
			return $this->attributes['valor_unitario'] = formatValueForMysql($value);
		}
	}

	public function setValorDescontoAttribute($value) {
		if (substr_count($value, ',') == 0) {
			return $this->attributes['valor_desconto'] = $value;
		} else {
			return $this->attributes['valor_desconto'] = formatValueForMysql($value);
		}
	}

}

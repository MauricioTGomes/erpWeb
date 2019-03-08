<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Conta extends Model {

	protected $fillable = [
		'pessoa_id',
		'pedido_id',
		'user_id',
		'titulo',
		'data_emissao',
		'vlr_total',
		'vlr_restante',
		'qtd_parcelas',
		'observacao',
		'tipo_operacao', // P ou R
		'qtd_dias'
	];

	protected $table = 'contas_receber_pagar';

	public function setVlrTotalAttribute($value) {
		return $this->attributes['vlr_total'] = formatValueForMysql($value);
	}

	public function setVlrRestanteAttribute($value) {
		return $this->attributes['vlr_restante'] = formatValueForMysql($value);
	}

	public function getVlrRestanteAttribute($value) {
		return $this->attributes['vlr_restante'] = formatValueForUser($value);
	}

	public function pessoa() {
		return $this->belongsTo(Pessoa::class );
	}

	public function parcelas() {
		return $this->hasMany(Parcela::class );
	}

	public function pedido() {
		return $this->hasOne(Pedido::class , 'pedido_id');
	}

	public function parcelasPagas() {
		return $this->hasMany(Parcela::class )->where('baixada', '1');
	}

	public function setDataEmissaoAttribute($value) {
		if (strlen($value) > 0) {
			try {
				$this->attributes['data_emissao'] = Carbon::createFromFormat('d/m/Y', $value);
			} catch (\Exception $e) {
				$this->attributes['data_emissao'] = date('Y-m-d');
			}
		} else {
			return null;
		}
	}

}

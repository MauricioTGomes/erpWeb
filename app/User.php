<?php

namespace App;

use App\Conta;
use App\Pedido;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password', 'porcentagem_comissao', 'tipo'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	public function conta() {
		return $this->hasMany(Conta::class , 'user_id');
	}

	public function pedido() {
		return $this->hasMany(Pedido::class , 'user_abertura_id');
	}

	public function movimentacao() {
		return $this->hasMany(MovimentacaoCaixa::class , 'user_id');
	}
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovimentacaoRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		$tipo = $this->tipo;

		return [
			'descricao' => 'required|min:3',
			'valor'     => 'required',
			'operacao'  => 'required',
		];
	}

	public function messages() {
		// mensagens de erro personalizadas!
		return [
			'descricao.required' => 'O campo :attribute é obrigatório',
			'valor.required'     => 'O campo :attribute é obrigatório',
			'operacao.required'  => 'O campo :attribute é obrigatório',
		];
	}
}

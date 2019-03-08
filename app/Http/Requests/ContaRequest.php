<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContaRequest extends FormRequest {
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
		return [
			'pessoa_id'     => 'required',
			'data_emissao'  => 'required',
			'vlr_total'     => 'required',
			'qtd_dias'      => 'required',
			'array_parcela' => 'required'
		];
	}

	public function messages() {
		// mensagens de erro personalizadas!
		return [
			'pessoa_id.required'     => 'O campo pessoa é obrigatório',
			'data_emissao.required'  => 'O campo :attribute é obrigatório',
			'vlr_total.required'     => 'O campo :attribute é obrigatório',
			'qtd_dias.required'      => 'O campo :attribute é obrigatório',
			'array_parcela.required' => 'O campo parcelas é obrigatório',
		];
	}
}
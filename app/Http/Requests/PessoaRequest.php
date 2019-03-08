<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PessoaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $tipo = $this->tipo;


        return [
            'nome' => $tipo == 1 ? 'required|min:3' : '',
            'cpf' => $tipo == 1 ? 'required' : '',
            'sexo' => $tipo == 1 ? 'required' : '',
            'cnpj' => $tipo == 2 ? 'required' : '',
            'razao_social' => $tipo == 2 ? 'required|min:3' : '',
            'fone' => 'required',
            'cidade_id' => 'required',
            'bairro' => 'required|min:3',
        ];
    }

    public function messages()
    {
        // mensagens de erro personalizadas!
        return [
            'nome.required' => 'O campo :attribute é obrigatório',
            'cpf.required' => 'O campo :attribute é obrigatório',
            'sexo.required' => 'O campo :attribute é obrigatório',
            'cnpj.required' => 'O campo :attribute é obrigatório',
            'fone.required' => 'O campo :attribute é obrigatório',
            'razao_social.required' => 'O campo :attribute é obrigatório',
            'cidade_id.required' => 'O campo cidade é obrigatório',
            'bairro.required' => 'O campo :attribute é obrigatório',
        ];
    }
}

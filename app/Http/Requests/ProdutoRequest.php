<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdutoRequest extends FormRequest
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
            'nome' => 'required|min:3',
            'apelido_produto' => 'required|min:3',
            'qtd_estoque' => 'required',
            'valor_venda' => 'required',
        ];
    }

    public function messages()
    {
        // mensagens de erro personalizadas!
        return [
            'nome.required' => 'O campo :attribute é obrigatório',
            'apelido_produto.required' => 'O campo apelido produto é obrigatório',
            'qtd_estoque.required' => 'O campo quantidade estoque é obrigatório',
            'valor_venda.required' => 'O campo valor venda é obrigatório'
        ];
    }
}

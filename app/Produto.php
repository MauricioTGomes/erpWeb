<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produtos';

    protected $fillable = [
        'nome',
        'apelido_produto',
        'qtd_estoque',
        'valor_compra',
        'valor_venda',
        'valor_unitario',
        'codigo',
        'cod_barras',
        'ativo'
    ];

    protected $dates = ['updated_at', 'created_at'];


    public function buscaProdutosPesquisa($parametro = null, $ativo = 0)
    {
        $query = $this->newQuery();

        if ($parametro == null) {
            return $query->paginate(10);
        }

        $query->where(function ($q) use ($parametro, $ativo) {
            $q->where('ativo', $ativo)
                ->where('cod_barras', 'like', "%$parametro%")
                ->orWhere('nome', 'like', "%$parametro%")
                ->orWhere('codigo', 'like', "%$parametro%")
                ->orWhere('nome', 'like', "%$parametro%")
                ->orWhere('apelido_produto', 'like', "%$parametro%");

        });

        return $query->paginate(10);
    }

    public function setValorVendaAttribute($value) {
        return $this->attributes['valor_venda'] = formatValueForMysql($value);
    }

    public function setValorUnitarioAttribute() {
        return $this->attributes['valor_unitario'] = formatValueForMysql($this->attributes['valor_venda']);
    }

    public function setValorCompraAttribute($value) {
        return $this->attributes['valor_compra'] = formatValueForMysql($value);
    }
}

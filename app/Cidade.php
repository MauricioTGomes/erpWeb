<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{

    protected $table = 'cidades';

    protected $fillable = [
        'nome',
        'codigo_ibge',
        'estado_id'
    ];

    protected $primaryKey = "id";
    public $timestamps = false;

    public function estado() {
        return $this->belongsTo(Estado::class);
    }

    public function pessoas() {
        return $this->hasMany(Pessoa::class);
    }

}

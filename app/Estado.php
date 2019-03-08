<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'estados';

    protected $fillable = array(
        'nome',
        'uf',
        'codigo_estado',
        'pais_id',
    );

    protected $primaryKey = "id";

    public $timestamps = false;

    public function cidades() {
        return $this->hasMany(Cidade::class);
    }

    public function pais() {
        return $this->belongsTo(Pais::class);
    }

}

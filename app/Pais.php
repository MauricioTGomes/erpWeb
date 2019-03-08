<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table = 'paises';

    protected $fillable = array(
        'nome',
        'codigo',
        'abreviacao',
    );

    public $timestamps = false;
    protected $primaryKey = "id";

    public function estados() {
        return $this->hasMany(Estado::class);
    }

}

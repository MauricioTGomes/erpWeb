<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePessoa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

		Schema::create('pessoa', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cidade_id')->unsigned();
            $table->string('razao_social');
            $table->string('fantasia');
            $table->string('cpf');
            $table->string('cnpj');
            $table->string('ie');
            $table->string('rg');
            $table->string('email');
            $table->string('endereco');
            $table->string('cep');
            $table->integer('endereco_nro');
            $table->string('bairro');
            $table->string('complemento');
            $table->enum('sexo', array('Masculino', 'Feminino'));
            $table->string('fone');
            $table->enum('ativo', array('1', '0'))->defalt('1');
            $table->enum('cliente', array('1', '0'))->defalt('1');
            $table->enum('fornecedor', array('1', '0'))->defalt('1');
            $table->enum('tipo', array('1', '0'))->defalt('1');
            $table->timestamps();
        });

        Schema::table('pessoa', function ($table) {
            $table->foreign('cidade_id')->references('id')->on('cidade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

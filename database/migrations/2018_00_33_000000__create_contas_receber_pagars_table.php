<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContasReceberPagarsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('contas_receber_pagar', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pessoa_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('titulo')->index();
            $table->date('data_emissao');
            $table->decimal('vlr_total', 10, 2)->default(0.00);
            $table->decimal('vlr_restante', 10, 2)->default(0.00);
            $table->integer('qtd_parcelas');
            $table->integer('qtd_dias');
            $table->string('observacao');
            $table->char('tipo_operacao', 1); //P ou R);
            $table->string('tipo_documento'); //CHEQUE, TITULO, BOLETO
            $table->timestamps();
        });

        Schema::table('contas_receber_pagar', function ($table) {
            $table->foreign('pessoa_id')->references('id')->on('pessoas');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('contas_receber_pagar');
    }
}

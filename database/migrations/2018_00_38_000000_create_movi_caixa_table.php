<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateMoviCaixaTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('movimentacao_caixa', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pedido_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('parcela_id')->unsigned();
            $table->decimal('valor_total', 10, 2)->default(0.00);
            $table->decimal('valor_desconto', 10, 2)->default(0.00);
            $table->decimal('valor_pago', 10, 2)->default(0.00);
            $table->string('descricao', 255);
            $table->char('estornado', 1)->default(0);
            $table->char('numero', 255);

            $table->timestamps();
        });

        Schema::table('movimentacao_caixa', function (Blueprint $table) {
            $table->foreign('pedido_id')->references('id')->on('pedidos');
            $table->foreign('parcela_id')->references('id')->on('parcelas_receber_pagar');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('pedidos');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreatePedidoTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('pedidos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pessoa_id')->unsigned();
            $table->integer('user_abertura_id')->unsigned();
            $table->integer('user_fechamento_id')->default(null);
            $table->integer('conta_id')->unsigned();
            $table->decimal('valor_total', 10, 2)->default(0.00);
            $table->decimal('valor_desconto', 10, 2)->default(0.00);
            $table->decimal('valor_liquido', 10, 2)->default(0.00);
            $table->char('qtd_produtos', 255);
            $table->string('observacoes', 255);
            $table->char('faturado', 1)->default(0);
            $table->char('numero', 255);
            $table->timestamps();
        });

        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreign('pessoa_id')->references('id')->on('pessoa');
            $table->foreign('conta_id')->references('id')->on('contas_receber_pagar')
                ->onDelete('cascade');
            $table->foreign('user_abertura_id')->references('id')->on('users');
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

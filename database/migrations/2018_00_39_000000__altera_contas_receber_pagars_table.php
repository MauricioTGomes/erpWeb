<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlteraContasReceberPagarsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('contas_receber_pagar', function ($table) {
            $table->integer('pedido_id')->unsigned()->index('contas_receber_pagar_pedido_id_foreign')->nullable();
            $table->foreign('pedido_id')->references('id')->on('pedidos');

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

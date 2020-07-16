<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlteraUsersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('users', function ($table) {
            $table->decimal('porcentagem_comissao', 10, 2)->nullable()->default(0.00);
            $table->enum('tipo', ['GERENTE', 'USUARIO', 'VENDEDOR'])->nullable()->default('GERENTE');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('users', function ($table) {
            $table->dropColumn('porcentagem_comissao');
            $table->dropColumn('tipo');
        });
    }
}

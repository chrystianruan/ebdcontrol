<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financeiro_transacaos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('descricao');
            $table->decimal('valor', 10, 2);
            $table->date('data_cad');
            $table->integer('situacao');

            $table->unsignedBigInteger('id_financeiro')->unsigned();
            $table->foreign('id_financeiro')->references('id')->on('financeiros');

            $table->unsignedBigInteger('id_cat')->unsigned();
            $table->foreign('id_cat')->references('id')->on('financeiro_cats');

            $table->unsignedBigInteger('id_tipo')->unsigned();
            $table->foreign('id_tipo')->references('id')->on('financeiro_tipos');

            $table->unsignedBigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');



            $table->text('descricao_original')->nullable();
            $table->decimal('valor_original', 10, 2)->nullable();
            $table->date('data_cad_original')->nullable();
            $table->integer('id_cat_original')->nullable();



            $table->integer('id_tipo_original')->nullable();;

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('financeiro_transacaos');
    }
};

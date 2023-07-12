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
        Schema::create('chamadas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('id_sala')->unsigned();
            $table->foreign('id_sala')->references('id')->on('salas')->onDelete('cascade');
            $table->json('nomes');
            $table->integer('matriculados');
            $table->integer('presentes');
            $table->integer('visitantes');
            $table->integer('assist_total');
            $table->integer('biblias');
            $table->integer('revistas');
            $table->foreignId('congregacao_id')->constrained('congregacaos');
            $table->text('observacoes')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chamadas');
    }
};

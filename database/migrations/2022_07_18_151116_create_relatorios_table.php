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
        Schema::create('relatorios', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->json('salas');
            $table->integer('matriculados');
            $table->integer('presentes');
            $table->integer('visitantes');
            $table->foreignId('congregacao_id')->constrained('congregacaos');
            $table->integer('assist_total');
            $table->integer('biblias');
            $table->integer('revistas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relatorios');
    }
};

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
        Schema::create('pessoas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nome');
            $table->boolean('sexo');
            $table->string('paternidade_maternidade')->nullable();
            $table->date('data_nasc');
            $table->string('responsavel')->nullable();
            $table->string('ocupacao')->nullable();
            $table->string('cidade')->nullable();
            $table->unsignedBigInteger('id_uf')->unsigned();
            $table->foreign('id_uf')->references('id')->on('ufs');
            $table->string('telefone')->unique()->nullable();
            $table->unsignedBigInteger('id_formation')->unsigned();
            $table->foreign('id_formation')->references('id')->on('formations');
            $table->text('cursos')->nullable();
            $table->json('id_sala');
            $table->unsignedBigInteger('id_funcao')->unsigned();
            $table->foreign('id_funcao')->references('id')->on('funcaos');
            $table->integer('interesse');
            $table->integer('frequencia_ebd')->nullable();
            $table->boolean('curso_teo')->nullable();
            $table->boolean('prof_ebd')->nullable();
            $table->boolean('prof_comum')->nullable();
            $table->boolean('situacao');
            $table->unsignedBigInteger('id_public')->unsigned()->nullable();
            $table->foreign('id_public')->references('id')->on('publicos');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pessoas');
    }
};

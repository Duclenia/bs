<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEscalaTrabalhoDiaSemanaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('escalatrabalho_diasemana', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            
            $table->unsignedBigInteger('escala');
            $table->unsignedBigInteger('dia');
            
            $table->foreign('escala')
                    ->references('id')
                    ->on('escala_trabalho');
            
            $table->foreign('dia')
                    ->references('id')
                    ->on('dia_semana');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('escalatrabalho_diasemana');
    }
}

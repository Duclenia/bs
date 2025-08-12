<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarefaMembroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarefa_membro', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('tarefa_id');
            
            $table->foreign('tarefa_id')
                  ->references('id')
                  ->on('tarefa');
            
            $table->unsignedBigInteger('membro_id');
            
            $table->foreign('membro_id')
                  ->references('id')
                  ->on('admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tarefa_membro');
    }
}

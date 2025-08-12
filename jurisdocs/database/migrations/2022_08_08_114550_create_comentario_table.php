<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComentarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comentario', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            
            $table->text('conteudo');
            
            $table->unsignedBigInteger('processo_id');
            
            $table->unsignedBigInteger('comentado_por');
            
            $table->foreign('processo_id')
                    ->references('id')
                    ->on('processo');
            
            $table->foreign('comentado_por')
                    ->references('id')
                    ->on('users');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comentario');
    }
}

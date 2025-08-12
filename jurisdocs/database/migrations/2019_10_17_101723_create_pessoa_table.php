<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePessoaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pessoa', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tipo');
            $table->string('foto',100)->nullable();
            
            $table->unsignedBigInteger('endereco_id')->nullable();
            
            $table->foreign('tipo')
                   ->references('id')
                   ->on('tipopessoa');
            
            $table->foreign('endereco_id')
                   ->references('id')
                   ->on('endereco');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pessoa');
    }
}

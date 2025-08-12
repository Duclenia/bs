<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePessoaSingularTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pessoasingular', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            
            $table->string('nome', 50);
            $table->string('sobrenome', 50);
            $table->enum('sexo', ['M','F']);
            $table->enum('estado_civil', ['S', 'C','D','V'])->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('nome_pai',100)->nullable();
            $table->string('nome_mae',100)->nullable();
            
            $table->unsignedBigInteger('pais_id')->nullable();
            
            $table->foreign('pais_id')
                   ->references('id')
                   ->on('pais');
            
            $table->unsignedBigInteger('municipio_id')->nullable();
            
            $table->foreign('municipio_id')
                   ->references('id')
                   ->on('municipio');
            
            $table->unsignedBigInteger('pessoa_id');
            
            $table->foreign('pessoa_id')
                   ->references('id')
                   ->on('pessoa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pessoasingular');
    }
}

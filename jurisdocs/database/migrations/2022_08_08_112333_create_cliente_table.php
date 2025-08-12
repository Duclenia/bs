<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliente', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('nome', 50)->nullable();
            $table->string('sobrenome', 50)->nullable();
            $table->string('instituicao', 100)->nullable();
            $table->unsignedBigInteger('tipo');
            $table->enum('estado_civil', ['C', 'D', 'S', 'V'])->nullable();
            $table->enum('regime_casamento', ['CB', 'SB'])->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('nif', 16);
            
            $table->unsignedBigInteger('documento_id')->nullable();
           
            $table->string('nome_pai')->nullable();
            $table->string('nome_mae')->nullable();
            $table->string('telefone', 20);
            $table->string('alternate_no', 20)->nullable();
            $table->text('endereco');
            $table->unsignedBigInteger('pais_id');
            $table->unsignedBigInteger('provincia_id')->nullable();
            $table->unsignedBigInteger('municipio_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('nome_referencia', 30)->nullable();
            $table->string('reference_mobile',20)->nullable();
            $table->enum('activo', ['S', 'N'])->default('S');
            
            $table->enum('client_type', ['single', 'multiple'])->default('single');
            
            $table->string('foto',100)->nullable();
            $table->string('codigo_verificacao', 10)->nullable();
            
            
            $table->foreign('tipo')
                    ->references('id')
                    ->on('tipopessoa');
            
            $table->foreign('documento_id')
                    ->references('id')
                    ->on('documento');
            
            $table->foreign('pais_id')
                    ->references('id')
                    ->on('pais');
            
            $table->foreign('provincia_id')
                    ->references('id')
                    ->on('provincia');
            
            $table->foreign('municipio_id')
                    ->references('id')
                    ->on('municipio');
            
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cliente');
    }
}

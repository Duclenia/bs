<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFornecedorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fornecedor', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->string('nome',35)->nullable();
            $table->string('nome_meio', 35)->nullable();
            $table->string('sobrenome', 35)->nullable();
            $table->enum('tipo', ['P', 'F']);
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('telefone', 15);
            $table->string('nif', 15)->unique();
            $table->string('alternate_no')->nullable();
            $table->unsignedBigInteger('endereco_id');
   
            $table->enum('activo', ['S','N'])->default('S');
            
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
        Schema::dropIfExists('fornecedor');
    }
}

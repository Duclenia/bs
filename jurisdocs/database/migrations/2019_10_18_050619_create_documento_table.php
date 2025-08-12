<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documento', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            
            $table->string('ndi',15)->unique();
            $table->unsignedBigInteger('tipo');
            $table->string('anexo', 100)->nullable();
            $table->enum('activo', ['S','N'])->default('S');
  
            $table->foreign('tipo')
                    ->references('id')
                    ->on('tipodocumento');
            
            $table->date('data_validade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documento');
    }
}

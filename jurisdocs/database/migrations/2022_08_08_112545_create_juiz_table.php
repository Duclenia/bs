<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJuizTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('juiz', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome', 100);
            
            $table->enum('activo', ['S','N'])->default('S');
            
            $table->unsignedBigInteger('tribunal');
            
            $table->foreign('tribunal')
                   ->references('id')
                   ->on('tribunal_areaprocessual_seccao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('juiz');
    }
}

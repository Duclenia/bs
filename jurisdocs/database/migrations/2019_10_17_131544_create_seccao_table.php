<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeccaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seccao', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->string('nome',60)->unique();
            $table->enum('activo', ['S','N'])->default('S');
            
            $table->unsignedBigInteger('areaprocessual_id');

            $table->foreign('areaprocessual_id')
                    ->references('id')
                    ->on('areaprocessual');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seccao');
    }
}

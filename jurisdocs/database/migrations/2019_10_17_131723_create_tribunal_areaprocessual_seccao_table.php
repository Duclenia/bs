<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTribunalAreaProcessualSeccaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tribunal_areaprocessual_seccao', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('trib_areaprocessual');

            $table->foreign('trib_areaprocessual')
                    ->references('id')
                    ->on('tribunal_areaprocessual');

            $table->unsignedBigInteger('seccao_id');

            $table->foreign('seccao_id')
                    ->references('id')
                    ->on('seccao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tribunal_areaprocessual_seccao');
    }
}

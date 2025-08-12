<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTribunalAreaProcessualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tribunal_areaprocessual', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('tribunal_id');

            $table->foreign('tribunal_id')
                    ->references('id')
                    ->on('tribunal');

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
        Schema::dropIfExists('tribunal_areaprocessual');
    }
}

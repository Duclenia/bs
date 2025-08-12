<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstadoprocessoAreaprocessualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estadoprocesso_areaprocessual', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('estadoprocesso_id');

            $table->foreign('estadoprocesso_id')
                    ->references('id')
                    ->on('estadoprocesso');
            
            $table->unsignedBigInteger('areaprocessual_id');

            $table->foreign('areaprocessual_id')
                    ->references('id')
                    ->on('areaprocessual');
            
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
        Schema::dropIfExists('estadoprocesso_areaprocessual');
    }
}

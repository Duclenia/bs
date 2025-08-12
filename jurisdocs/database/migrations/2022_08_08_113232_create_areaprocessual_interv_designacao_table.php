<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreaprocessualIntervDesignacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areaprocessual_intervdesignacao', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('areaprocessual_id');

            $table->foreign('areaprocessual_id')
                    ->references('id')
                    ->on('areaprocessual');

            $table->unsignedBigInteger('intervdesignacao_id');

            $table->foreign('intervdesignacao_id')
                    ->references('id')
                    ->on('intervdesignacao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('areaprocessual_intervdesignacao');
    }
}

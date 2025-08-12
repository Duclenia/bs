<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreaprocessualTipoprocessoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areaprocessual_tipoprocesso', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('areaprocessual_id');
            
            $table->unsignedBigInteger('tipoprocesso_id');
            
            $table->foreign('areaprocessual_id')
                    ->references('id')
                    ->on('areaprocessual');
            
            $table->foreign('tipoprocesso_id')
                    ->references('id')
                    ->on('tipoprocesso');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('areaprocessual_tipoprocesso');
    }
}

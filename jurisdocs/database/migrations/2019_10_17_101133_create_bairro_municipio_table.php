<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBairroMunicipioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bairro_municipio', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('bairro_id');
            
            $table->unsignedBigInteger('municipio_id');

            $table->foreign('bairro_id')
                    ->references('id')
                    ->on('bairro');
            
            $table->foreign('municipio_id')
                    ->references('id')
                    ->on('municipio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bairro_municipio');
    }
}

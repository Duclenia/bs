<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnderecoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('endereco', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            
            $table->string('numero', 10)->nullable()->default('S/N');
            $table->string('rua', 100)->nullable();
            
            $table->unsignedBigInteger('bairro_id')->nullable();

            $table->foreign('bairro_id')
                    ->references('id')
                    ->on('bairro');

            $table->unsignedBigInteger('municipio_id');

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
        Schema::dropIfExists('endereco');
    }
}

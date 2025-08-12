<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfiguracaoGeralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracao_geral', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome_escritorio', 60)->nullable();
            $table->unsignedBigInteger('endereco_id')->nullable();
            $table->integer('pincode')->unsigned();
            $table->integer('formato_data')->unsigned();
            $table->string('logo_img', 100)->nullable();
            $table->string('favicon_img', 100)->nullable();
            $table->text('timezone')->nullable();
            $table->integer('no_interno_processo')->unsigned()->default(0);
            
            $table->foreign('endereco_id')
                    ->references('id')
                    ->on('endereco');
            
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
        Schema::dropIfExists('configuracao_geral');
    }
}

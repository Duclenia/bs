<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePessoaContactoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pessoa_contacto', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('pessoa_id');

            $table->foreign('pessoa_id')
                    ->references('id')
                    ->on('pessoa')
                    ->onDelete('cascade');

            $table->unsignedBigInteger('contacto_id');

            $table->foreign('contacto_id')
                    ->references('id')
                    ->on('contacto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pessoa_contacto');
    }
}

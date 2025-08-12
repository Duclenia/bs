<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscricaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscricao', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            
            $table->date('data_inicio');
            $table->date('data_termino');
            
            $table->enum('periodicidade', ['M','T', 'A']);
            
            $table->integer('processo_registado')->unsigned();
            
            $table->integer('total_processo')->unsigned();
            
            $table->unsignedBigInteger('plano_id');
            
            $table->foreign('plano_id')
                    ->references('id')
                    ->on('plano');
            
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
        Schema::dropIfExists('subscricao');
    }
}

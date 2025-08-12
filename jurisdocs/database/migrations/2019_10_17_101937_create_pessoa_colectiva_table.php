<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePessoaColectivaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pessoacolectiva', function (Blueprint $table) {
           
           $table->bigIncrements('id');
            
           $table->unsignedBigInteger('pessoa_id');
    
            $table->foreign('pessoa_id')
                   ->references('id')
                   ->on('pessoa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pessoacolectiva');
    }
}

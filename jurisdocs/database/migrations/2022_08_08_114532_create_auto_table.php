<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('descricao', 100);
            
            $table->string('anexo', 100);
            
            $table->unsignedBigInteger('processo_id');
            
            $table->unsignedBigInteger('autor');
            
            $table->foreign('processo_id')
                    ->references('id')
                    ->on('processo');
            
            $table->foreign('autor')
                    ->references('id')
                    ->on('users');
            
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
        Schema::dropIfExists('auto');
    }
}

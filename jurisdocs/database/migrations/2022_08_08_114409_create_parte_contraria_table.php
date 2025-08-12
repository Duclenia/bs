<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParteContrariaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parte_contraria', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('processo_id');
            $table->unsignedBigInteger('qualidade');
            $table->string('nome', 100);
            $table->string('party_advocate',100)->nullable();
            
            $table->timestamps();
            
            
            $table->foreign('processo_id')
                    ->references('id')
                    ->on('processo');
            
            
            $table->foreign('qualidade')
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
        Schema::dropIfExists('parte_contraria');
    }
}

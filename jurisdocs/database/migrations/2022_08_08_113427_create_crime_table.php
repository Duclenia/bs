<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crime', function (Blueprint $table) {
            
            $table->bigIncrements('id');
           
            $table->string('designacao',100)->unique();
            
            $table->unsignedBigInteger('idEnq');
            
            $table->foreign('idEnq')
                   ->references('id')
                   ->on('crimEnquad');
            
            $table->unsignedBigInteger('idSubEnq');
            
            $table->foreign('idSubEnq')
                   ->references('id')
                   ->on('crimSubEnquad');
            
            $table->string('artigo',20);
            
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crime');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrimSubEnquadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crimSubEnquad', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            
            $table->string('designacao', 100)->unique();
            
            $table->unsignedBigInteger('idEnq');
            
            $table->foreign('idEnq')
                    ->references('id')
                    ->on('crimEnquad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crimSubEnquad');
    }
}

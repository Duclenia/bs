<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaseTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_transfer', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('advocate_id');
            $table->unsignedBigInteger('processo_id');
            $table->unsignedBigInteger('from_juiz');
            $table->unsignedBigInteger('to_juiz');
            $table->unsignedBigInteger('from_tribunal');
            $table->unsignedBigInteger('to_tribunal');
            $table->dateTime('transfer_date');
      
            $table->timestamps();
            
            $table->foreign('advocate_id')
                    ->references('id')
                    ->on('admin');
            
            
            $table->foreign('processo_id')
                    ->references('id')
                    ->on('processo');
            
            $table->foreign('from_juiz')
                    ->references('id')
                    ->on('juiz');
            
            $table->foreign('to_juiz')
                    ->references('id')
                    ->on('juiz');
            
            $table->foreign('from_tribunal')
                    ->references('id')
                    ->on('tribunal');
            
            $table->foreign('to_tribunal')
                    ->references('id')
                    ->on('tribunal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_transfer');
    }
}

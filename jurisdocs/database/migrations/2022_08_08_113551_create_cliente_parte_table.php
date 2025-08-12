<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteParteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliente_parte', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cliente_id');
            $table->text('party_firstname')->nullable();
            $table->text('party_middlename')->nullable();
            $table->text('party_lastname')->nullable();
            $table->text('party_mobile')->nullable();
            $table->text('party_address')->nullable();
            $table->text('party_advocate')->nullable();
            
            $table->foreign('cliente_id')
                  ->references('id')
                  ->on('cliente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cliente_parte');
    }
}

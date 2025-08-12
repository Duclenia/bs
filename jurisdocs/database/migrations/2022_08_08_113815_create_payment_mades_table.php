<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_mades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fornecedor_id');
            $table->unsignedBigInteger('factura_id');
            $table->float('valor', 8, 2)->default('0');
            $table->date('receiving_date')->nullable();
            $table->enum('payment_type', ['Cash','Cheque','Net Banking','Other'])->default('Cash');
            $table->text('no_referencia')->nullable();
            $table->dateTime('cheque_date')->nullable();
            $table->text('observacao')->nullable();
            $table->integer('payment_received_by')->unsigned();
            
            
            $table->foreign('fornecedor_id')
                  ->references('id')
                  ->on('fornecedor');
            
            $table->foreign('factura_id')
                  ->references('id')
                  ->on('factura');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_mades');
    }
}

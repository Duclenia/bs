<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentReceivedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_receiveds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('factura_id');
            $table->integer('receipt_number')->unsigned()->nullable();
            $table->string('amount', 40);
            $table->dateTime('receiving_date');
            $table->enum('payment_type', ['Cash','Cheque','Net Banking','Other'])->default('Cash');
            $table->date('cheque_date')->nullable();
            $table->string('reference_number', 40)->nullable();
            $table->text('note');
            $table->string('status', 20)->default('pendente');
            $table->string('comprovativo', 100)->nullable();
            $table->integer('payment_received_by')->unsigned();
            $table->foreign('cliente_id')
                    ->references('id')
                    ->on('cliente');

            $table->foreign('factura_id')
                    ->references('id')
                    ->on('factura');

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
        Schema::dropIfExists('payment_receiveds');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemFacturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_factura', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('factura_id');
            $table->unsignedBigInteger('imposto_id')->nullable();
            $table->double('tax')->nullable()->default('0');
            $table->text('item_descricao')->nullable();
            $table->integer('iteam_qty')->unsigned();
            $table->text('item_amount');
            $table->text('item_rate');
            $table->text('hsn')->nullable();
            $table->unsignedBigInteger('servico_id');
            
            
            $table->foreign('factura_id')
                   ->references('id')
                   ->on('factura');
            
            $table->foreign('imposto_id')
                   ->references('id')
                   ->on('imposto');
            
            $table->foreign('servico_id')
                  ->references('id')
                  ->on('servico');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_factura');
    }
}

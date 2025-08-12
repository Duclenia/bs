<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cliente_id');
            $table->string('factura_no', 20);
            $table->text('sub_total_amount');
            $table->text('tax_amount')->nullable();
            $table->text('total_amount');
            $table->enum('inv_status', ['Due','Partially Paid','Paid'])->default('Due');
            $table->date('due_date');
            $table->date('inv_date');
            $table->text('remarks')->nullable();
            $table->string('tax_type')->nullable();
            $table->integer('tax_id')->unsigned()->nullable();
            $table->text('json_content')->nullable();
            $table->integer('invoice_created_by')->unsigned();
            $table->enum('activo',array('S','N'))->default('S');
            
            $table->unsignedBigInteger('despesa_id')->nullable();
            
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
        Schema::dropIfExists('factura');
    }
}

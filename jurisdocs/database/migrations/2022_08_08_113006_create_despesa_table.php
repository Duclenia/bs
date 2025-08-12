<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDespesaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('despesa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fornecedor_id');
            $table->string('factura_no', 20);
            $table->text('sub_total_amount');
            $table->text('tax_amount')->nullable();
            $table->text('total_amount');
            $table->integer('tax_id')->unsigned()->nullable();
            $table->enum('inv_status', ['Due','Partially Paid','Paid'])->default('Due');
            $table->date('due_date');
            $table->date('inv_date');
            $table->text('remarks')->nullable();
            $table->string('tax_type')->nullable();
            $table->text('json_content')->nullable();
            $table->integer('invoice_created_by')->unsigned();
            $table->enum('activo', ['S','N'])->default('S');
            
            $table->foreign('fornecedor_id')
                   ->references('id')
                   ->on('fornecedor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('despesa');
    }
}

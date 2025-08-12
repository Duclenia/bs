<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemDespesaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_despesa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('tax_id')->unsigned()->nullable();
            $table->double('tax')->nullable()->default('0');
            $table->text('category_id')->nullable();
            $table->text('descricao')->nullable();
            $table->integer('iteam_qty')->unsigned();
            $table->text('item_amount');
            $table->text('item_rate');
            
            $table->unsignedBigInteger('despesa_id');
            
            $table->foreign('despesa_id')
                   ->references('id')
                   ->on('despesa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_despesa');
    }
}

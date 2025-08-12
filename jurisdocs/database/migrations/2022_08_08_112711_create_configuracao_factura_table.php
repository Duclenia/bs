<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfiguracaoFacturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracao_factura', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('formato_factura')->default(1);
            $table->string('prefixo', 20)->nullable();
            $table->string('client_note', 30)->nullable();
            $table->text('termos_condicoes')->nullable();
            $table->integer('factura_no')->unsigned()->default(0);
            $table->integer('receipt_no')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuracao_factura');
    }
}

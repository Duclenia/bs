<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoDespesaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipodespesa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome', 30)->unique();
            $table->text('descricao')->nullable();

            $table->enum('activo', ['S', 'N'])->default('S');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipodespesa');
    }
}

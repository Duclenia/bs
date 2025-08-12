<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImpostoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imposto', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome', 40);
            $table->string('per',20);
            $table->text('note')->nullable();
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
        Schema::dropIfExists('imposto');
    }
}

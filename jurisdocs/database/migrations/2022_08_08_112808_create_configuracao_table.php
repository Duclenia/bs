<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfiguracaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracao', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('facebook', 40)->nullable();
            $table->string('google', 40)->nullable();
            $table->string('youtub', 40)->nullable();
            $table->string('linked_in', 40)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('telefone', 20)->nullable();
            $table->unsignedBigInteger('endereco_id')->nullable();
            $table->text('about_us')->nullable();
            
            $table->foreign('endereco_id')
                    ->references('id')
                    ->on('endereco');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuracao');
    }
}

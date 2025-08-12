<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDumpsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('dumps', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->string('file', 255);
            $table->string('file_name', 255);
            $table->string('prefix', 255)->nullable();
            $table->integer('encrypted')->unsigned()->default('0');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('dumps');
    }

}

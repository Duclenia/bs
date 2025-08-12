<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoricoProcessoTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('historico_processo', function (Blueprint $table) {
            
            $table->bigIncrements('id');

            $table->unsignedBigInteger('advogado_id');
            $table->unsignedBigInteger('processo_id');

            $table->dateTime('bussiness_on_date')->nullable();
            $table->dateTime('hearing_date')->nullable();
            $table->enum('is_transfer', ['S', 'N'])->default('N');
            $table->unsignedBigInteger('transfer_juiz')->nullable();

            $table->integer('updated_by')->unsigned()->nullable();

            $table->timestamps();

            $table->foreign('advogado_id')
                    ->references('id')
                    ->on('admin');

            $table->foreign('processo_id')
                    ->references('id')
                    ->on('processo');

            $table->foreign('transfer_juiz')
                    ->references('id')
                    ->on('juiz');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('historico_processo');
    }

}

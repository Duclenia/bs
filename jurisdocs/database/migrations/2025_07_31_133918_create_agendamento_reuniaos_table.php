<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendamentoReuniaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamento_reuniaos', function (Blueprint $table) {
            $table->id();
            $table->string('vc_entidade');
            $table->longText('vc_motivo')->nullable()->default('text');
            $table->string('vc_pataforma');
            $table->longText('vc_nota')->nullable()->default('text');
            $table->boolean('it_termo')->default(false);
            $table->unsignedBigInteger('agenda_id');
            $table->foreign('agenda_id')->references('id')->on('agenda');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agendamento_reuniaos');
    }
}

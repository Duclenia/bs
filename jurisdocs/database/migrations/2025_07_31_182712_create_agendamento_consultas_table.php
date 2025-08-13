<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendamentoConsultasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamento_consultas', function (Blueprint $table) {
            $table->id();
            $table->string('vc_tipo');
            $table->string('vc_area');
            $table->string('vc_pataforma');
            $table->longText('vc_nota')->nullable()->default('text');
            $table->boolean('it_termo')->default(false);
            $table->boolean('it_envDocs')->default(false);
            $table->unsignedBigInteger('agenda_id');
            $table->foreign('agenda_id')
                ->references('id')
                ->on('agenda')
                ->onUpdate('cascade')
                ->onDelete('cascade');

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
        Schema::dropIfExists('agendamento_consultas');
    }
}

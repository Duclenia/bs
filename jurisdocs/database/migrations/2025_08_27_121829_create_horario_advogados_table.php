<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorarioAdvogadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horario_advogados', function (Blueprint $table) {

            $table->id();
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time')->nullable();        // Horário de início
            $table->time('end_time')->nullable();          // Horário de fim
            $table->integer('interval_minutes')->nullable(); // Intervalo entre atendimentos (ex: 30 min)
            $table->json('breaks')->nullable(); // Pausas durante o expediente (ex: ["12:00-13:00"])
            $table->boolean('day_off')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('advogado_id');
            $table->foreign('advogado_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('horario_advogados');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agenda', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->string('assunto', 50);
            $table->enum('type', ['new', 'exists'])->default('new');
            $table->date('data');
            $table->time('hora');
            $table->string('telefone');
            $table->string('nome', 100)->nullable();
            $table->text('observacao')->nullable();
            $table->enum('activo', ['OPEN', 'CANCEL BY CLIENT', 'CANCEL BY ADVOCA'])->default('OPEN');
            $table->string('vc_pataforma')->nullable();
            $table->string('meeting_id')->nullable();
            $table->string('join_url')->nullable();
            $table->string('start_url')->nullable(); // só no zoom

            $table->foreign('cliente_id')
                ->references('id')
                ->on('cliente');

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
        Schema::dropIfExists('agenda');
    }
}

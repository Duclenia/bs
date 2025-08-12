<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarefaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarefa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('rel_type');
            $table->integer('rel_id')->unsigned();
            $table->integer('customer_id')->unsigned()->nullable();
            $table->integer('project_type_task_id')->unsigned()->nullable();
            $table->string('task_subject');
            $table->string('project_status');
            $table->string('prioridade');
            $table->date('inicio');
            $table->date('termino')->nullable();
            $table->time('hora_inicio');
            $table->time('hora_termino')->nullable();
            $table->text('descricao')->nullable();
            $table->text('checklist_complete_remarks')->nullable();
            $table->text('checklist_complete_signature')->nullable();
            $table->enum('activo', ['S','N'])->default('S');
            $table->text('critical_comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tarefa');
    }
}

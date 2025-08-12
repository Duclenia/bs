<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processo', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('client_position')->nullable();
            $table->text('party_name')->nullable();
            $table->text('party_lawyer')->nullable();
            $table->string('no_processo',30)->nullable();
            $table->unsignedBigInteger('areaprocessual_id');
            $table->unsignedBigInteger('tipoprocesso_id');
            $table->unsignedBigInteger('estado');
            $table->float('valor_causa')->nullable();
            $table->enum('orgao', ['Judiciário','Judicial', 'Extrajudicial']);
            $table->unsignedBigInteger('orgaojudiciario_id')->nullable();
            $table->string('orgao_extrajudicial', 60)->nullable();
            $table->string('tipo_crime', 100)->nullable();
            $table->unsignedBigInteger('tribunal_id')->nullable();
            $table->unsignedBigInteger('seccao_id')->nullable();
            $table->enum('prioridade', ['Baixa','Média','Alta'])->nullable();
            $table->string('instrutor', 100)->nullable();
            $table->string('procurador', 100)->nullable();
            $table->unsignedBigInteger('juiz_id')->nullable();
            $table->string('escrivao', 100)->nullable();
            $table->string('mandatario_judicial', 100)->nullable();
            $table->dateTime('data_registo');
            $table->text('descricao')->nullable();
            $table->integer('no_interno')->unsigned()->default(0)->unique();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->enum('activo', ['S','N'])->default('S');
            $table->timestamps();
            
            $table->foreign('cliente_id')
                    ->references('id')
                    ->on('cliente');
            
            $table->foreign('client_position')
                    ->references('id')
                    ->on('intervdesignacao');
            
            $table->foreign('areaprocessual_id')
                    ->references('id')
                    ->on('areaprocessual');
            
            $table->foreign('tipoprocesso_id')
                    ->references('id')
                    ->on('tipoprocesso');
            
            $table->foreign('estado')
                    ->references('id')
                    ->on('estadoprocesso');
            
            $table->foreign('orgaojudiciario_id')
                    ->references('id')
                    ->on('orgaojudiciario');
            
            $table->foreign('tribunal_id')
                    ->references('id')
                    ->on('tribunal');
            
            $table->foreign('seccao_id')
                    ->references('id')
                    ->on('seccao');
            
            $table->foreign('juiz_id')
                    ->references('id')
                    ->on('juiz');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processo');
    }
}

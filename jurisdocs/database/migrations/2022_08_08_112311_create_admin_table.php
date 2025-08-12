<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table){
            
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pessoasingular_id');
            $table->unsignedBigInteger('user_id');
            $table->string('no_registo', 20)->nullable();
            $table->string('associated_name', 60)->nullable();
            $table->integer('is_activated')->default(0);
            $table->integer('is_account_setup')->default(0);
            $table->enum('is_user_type', ['SUPERADMIN', 'ADVOCATE', 'STAFF'])->default('ADVOCATE');
            $table->enum('invitation_status', ['accepted', 'sent'])->default('sent');
            $table->dateTime('accepted_at')->nullable();
            $table->string('current_package', 60)->nullable();
            $table->integer('payment_id')->default(0);
            $table->date('started_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 40)->nullable();
            $table->enum('activo', ['S', 'N'])->default('S');
            $table->enum('is_expired', ['Yes', 'No'])->default('No');
            $table->string('otp', 60)->nullable();
            $table->timestamp('otp_date')->nullable();
            $table->enum('is_otp_verify', ['0', '1'])->default('0');
            $table->enum('plataforma', ['App', 'Web'])->default('Web');
            
            
            $table->foreign('pessoasingular_id')
                    ->references('id')
                    ->on('pessoasingular');
            
            
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users');
            
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
        Schema::dropIfExists('admin');
    }
}

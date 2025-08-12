<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailsetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailsetups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mail_host', 40);
            $table->string('mail_port', 40);
            $table->string('mail_username', 30);
            $table->string('mail_email', 40);
            $table->string('mail_password', 30);
            $table->string('mail_driver', 40);
            $table->string('mail_encryption', 40);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mailsetups');
    }
}

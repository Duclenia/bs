<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToPaymentHistoryTable extends Migration
{
    public function up()
    {
        Schema::table('payment_history', function (Blueprint $table) {
            $table->enum('status', ['pendente', 'aprovado', 'rejeitado'])->default('pendente')->after('note');
        });
    }

    public function down()
    {
        Schema::table('payment_history', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
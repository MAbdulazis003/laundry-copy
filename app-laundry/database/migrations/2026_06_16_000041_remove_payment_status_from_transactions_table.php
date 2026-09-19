<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('payment_status', ['unpaid','partial','paid'])->default('unpaid')->after('status');
        });
    }
};

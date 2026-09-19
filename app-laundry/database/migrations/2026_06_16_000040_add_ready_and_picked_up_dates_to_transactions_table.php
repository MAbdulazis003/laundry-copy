<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->timestamp('ready_at')->nullable()->after('status');
            $table->timestamp('picked_up_at')->nullable()->after('ready_at');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['ready_at', 'picked_up_at']);
        });
    }
};
